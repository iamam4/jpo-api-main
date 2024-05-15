<?php
header("Access-Control-Allow-Origin: *"); // Allow access from any origin for CORS. TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: POST"); // Allow POST method only

include_once '../config/Database.php';
include_once '../class/Attendee.php';
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

$id = intval($_POST['id']); // Get the id from the POST request
$firstName = $_POST['firstName']; // Get the first name from the POST request
$lastName = $_POST['lastName']; // Get the last name from the POST request
$email = $_POST['email']; // Get the email from the POST request
$diplomaId = intval($_POST['diplomaId']); // Get the diploma id from the POST request
$diplomaCategoryId = intval($_POST['diplomaCategoryId']); // Get the diploma category id from the POST request
$isIrlAttendee = $_POST['isIrlAttendee'] == "true"? 1 : 0; // if $_POST['isIrlAttendee'] is true then set $isIrlAttendee to 1 otherwise set it to 0
$regionalCode = $_POST['regionalCode']; // Get the regional code from the POST request

$attendee = new Attendee($db); // Create a new attendee object

// Set all attendee values
$attendee->setAllValues($firstName, $lastName, $email, $diplomaId, $diplomaCategoryId, $isIrlAttendee, $regionalCode, null, null);

$response = $attendee->updateAttendee($id); // Delete the attendee from the database

echo json_encode($response); // Send the response as JSON

?>