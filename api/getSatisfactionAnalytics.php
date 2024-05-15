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

$db_table = "attendees"; // Set the database table name

$satisfactionAnalytics = array(
    "labels" => [
        "Agréable",
        "Neutre",
        "Désagréable",
    ],
    "virtualTourSatisfaction" => [],
    "websiteSatisfaction" => [],
);

for ($i = 0; $i < 3; $i++) {
    // Query to get the virtual tour satisfaction count per option
    $query = "SELECT COUNT(*) AS satisfactionCount FROM " . $db_table . " WHERE virtualTourSatisfaction = " . $i;
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    // Append data to satisactionsAnalytics array
    array_push($satisfactionAnalytics["virtualTourSatisfaction"], $satisfactionCount); // Append satisfaction count to virtualTourSatisfaction array
}

for ($i = 0; $i < 3; $i++) {
    // Query to get the website satisfaction count per option
    $query = "SELECT COUNT(*) AS satisfactionCount FROM " . $db_table . " WHERE websiteSatisfaction = " . $i;
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    // Append data to satisactionsAnalytics array
    array_push($satisfactionAnalytics["websiteSatisfaction"], $satisfactionCount); // Append satisfaction count to websiteSatisfaction array
}

echo json_encode($satisfactionAnalytics); // Send satisfactionAnalytics array as JSON response
