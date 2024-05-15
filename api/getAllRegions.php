<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: GET"); // Allow GET method only

include_once '../config/Database.php';

$database = new Database(); // Create a new database object
$db = $database->getConnection(); // Get database connection

$db_table = "regions"; // Set the database table name

// Query to get all regions
$query = "SELECT * FROM " . $db_table;

$stmt = $db->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Go through each row
    extract($row); // Extract row data

    // Store region data in an array
    $region = array(
        "code" => $code,
        "name" => $name
    );
    $regions[] = $region; // Append region data to regions array
}

echo json_encode($regions); // Send regions array as JSON response