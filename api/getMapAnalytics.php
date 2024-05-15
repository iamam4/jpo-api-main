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

$db_table = "regions"; // Set the database table name

// Query to get all regions
$query = "SELECT * FROM " . $db_table;
$stmt = $db->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Go through each row
    extract($row); // Extract row data

    // Query to get the attendee count per region
    $query2 = "SELECT COUNT(*) AS attendeeCount FROM attendees WHERE regionalCode = " . "'" . $code . "'";
    $stmt2 = $db->prepare($query2);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    extract($row2);

    // Append data to regions array even if attendeeCount is 0
    $region = array(
        $row["code"],
        $attendeeCount,
    );

    $regions[] = $region; // Append region data to regions array
}

echo json_encode($regions); // Send regions array as JSON response


