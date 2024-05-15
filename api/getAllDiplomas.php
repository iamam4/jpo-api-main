<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: GET"); // Allow GET method only

include_once '../config/Database.php';

$database = new Database(); // Create a new database object
$db = $database->getConnection(); // Get database connection

$db_table = "diplomaTypes"; // Set the database table name

// Query to get all diploma types and inner join with diplomaCategories and order by diplomaId
$query = "SELECT * FROM " . $db_table . " 
INNER JOIN diplomaCategories ON diplomaTypes.categoryId = diplomaCategories.id 
ORDER BY diplomaTypes.diplomaId ASC";

$stmt = $db->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Go through each row
    extract($row); // Extract row data

    // Store diploma data in an array
    $diplomaType = array(
        "id" => $diplomaId,
        "name" => $diplomaName,
        "category" => $category[] = array( // Store diploma category data in an array
            "id" => $categoryId,
            "name" => $categoryName
        )
    );
    $diplomasTypes[] = $diplomaType; // Append diploma data to diplomas array
}
echo json_encode($diplomasTypes); // Send diplomas array as JSON response
?>

