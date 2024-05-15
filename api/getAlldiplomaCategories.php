<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: GET"); // Allow GET method only

include_once '../config/Database.php';

$database = new Database(); // Create a new database object
$db = $database->getConnection(); // Get database connection
$db_table = "diplomaCategories"; // Set the database table name

// Query to get all diploma categories and order by id
$query = "SELECT * FROM " . $db_table . " ORDER BY id ASC";
$stmt = $db->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Go through each row
    extract($row); // extract the category data

    // Store diploma category data in an array
    $diplomaCategory = array(
        "id" => $id,
        "name" => $categoryName
    );

    $diplomaCategories[] = $diplomaCategory; // Append diploma category data to diplomaCategories array
}

echo json_encode($diplomaCategories); // Send diplomaCategories array as JSON response
?>