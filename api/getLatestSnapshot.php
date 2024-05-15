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

$db_table = "analyticsSnapshots"; // Set the database table name

// Query to get latest snapshot
$query = "SELECT * FROM " . $db_table . " ORDER BY date DESC LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();

// Check if a row is returned
if ($stmt->rowCount() > 0) {

    // Get the snapshot details
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Create an array with snapshot data
    $response = array(
        'status' => 'success',
        'statusMessage' => 'Snapshot récupérée.',
        'date' => $row["date"],
        'attendeesCount' => $row["attendeesCount"],
        'numberOfNewAttendees' => $row["numberOfNewAttendees"]
    );
} else {
    // Create an array with error message if no snapshot is found
    $response = array(
        'status' => 'error',
        'statusMessage' => 'Aucune snapshot trouvée.'
    );
}
echo json_encode($response); // Send response as JSON
?>
