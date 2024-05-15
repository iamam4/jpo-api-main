<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: GET"); // Allow GET method only

include_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();
$db_table = "diplomaCategories";

// Query to get all diploma categories inner join diplomas and order by id
$query = "SELECT * FROM " . $db_table . " ORDER BY id ASC";
$stmt = $db->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Go through each row
    unset($diplomas); // Clean up the array before we use it to store diplomas

    extract($row); // extract the category data

    // Query to get all diplomas for the category
    $query2 = "SELECT * FROM diplomaTypes WHERE categoryId = " . $id;
    $stmt2 = $db->prepare($query2);
    $stmt2->execute();
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) { // Go through each row
        extract($row2);

        // Store diploma data in an array
        $diploma = array(
            "id" => $diplomaId,
            "name" => $diplomaName
        );

        // Add the diploma to the diplomas array
        $diplomas[] = $diploma;
    }

    // Create the diploma category array with category data and corresponding diplomas array
    $diplomaCategory = array(
        "id" => $id,
        "name" => $categoryName,
        "diplomas" => $diplomas
    );

    // Add the category to the diplomaCategories array
    $diplomaCategories[] = $diplomaCategory;
}

echo json_encode($diplomaCategories); // Send diplomaCategories array as JSON response
?>