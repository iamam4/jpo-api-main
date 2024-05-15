<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: GET"); // Allow GET method only

include_once '../config/Database.php';
include_once 'checkAuthentication.php'; // Check if the user is authenticated

if (!$isAuth) { // Check if the user is authenticated
    $response = array(
        "status" => "error",
        "message" => "Vous n'êtes pas authentifié"
    ); // Create an error response
    echo json_encode($response); // Send the response as JSON
    die(); // Stop executing the script
}

$database = new Database(); // Create a new database object
$db = $database->getConnection(); // Get database connection
$db_table = "diplomaCategories"; // Set the database table name

// Initialize diplomas array to store diploma data
$diplomaCategories = array(
    "names" => [],
    "counts" => [],
);

// Query to get all diploma types and inner join with diplomaCategories and order by diplomaId
$query = "SELECT * FROM " . $db_table . " ORDER BY id ASC";
$stmt = $db->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Go through each row
    extract($row); // Extract row data

    // Query to get the diploma category count accross all attendees
    $query2 = "SELECT COUNT(*) AS diplomaCategoryCount FROM attendees WHERE diplomaCategoryId = " . $id;
    $stmt2 = $db->prepare($query2);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    extract($row2);
    
    // Append data to diplomas array
    if ($diplomaCategoryCount == 0) {
        continue; // Skip diplomas with 0 attendees
    } else {
        array_push($diplomaCategories["names"], $categoryName); // Append diploma name to diplomas array
        array_push($diplomaCategories["counts"], $diplomaCategoryCount); // Append diploma count to diplomas array
    }
}
echo json_encode($diplomaCategories); // Send diplomas array as JSON response
?>