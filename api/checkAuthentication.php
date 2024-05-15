<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8

include_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();
$db_table = "sessions";

global $isAuth;
$isAuth = false;

// Get the token from the request headers
foreach (getallheaders() as $name => $value) { // Go through each header
    if ($name == "Authorization") { // Check if the header is the Authorization header
        $token = $value; // Set the token variable to the value of the Authorization header
    }
}

// Check if the token exists in database
$query = "SELECT * FROM " . $db_table . "
WHERE token = :token";

$stmt = $db->prepare($query);
$stmt->bindParam(':token', $token); // Bind the token value to the query
$stmt->execute(); // Execute the query

if ($stmt->rowCount() > 0) { // Check if the token exists in the database
    $isAuth = true; // Set the authentication status to true
} else {
    $isAuth = false; // Set the authentication status to false
}
?>