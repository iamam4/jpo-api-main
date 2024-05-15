<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: POST"); // Allow POST method only

include_once '../config/Database.php';
include_once '../class/Attendee.php';

$database = new Database(); // Create a new database object
$db = $database->getConnection(); // Get database connection
$firstName = $_POST['firstName']; // Get the first name from the POST request
$lastName = $_POST['lastName']; // Get the last name from the POST request
$email = $_POST['email']; // Get the email from the POST request
$diplomaId = intval($_POST['diplomaId']); // Get the diploma id from the POST request
$diplomaCategoryId = intval($_POST['diplomaCategoryId']); // Get the diploma category id from the POST request
$isIrlAttendee = $_POST['isIrlAttendee'] == "true" ? 1 : 0; // Get the isIrlAttendee value from the POST request and set it to 1 if it is true otherwise set it to 0
$regionalCode = $_POST['regionalCode']; // Get the regional code from the POST request
$virtualTourSatisfaction = $_POST['virtualTourSatisfaction']; // Get the virtual tour satisfaction from the POST request
$websiteSatisfaction = $_POST['websiteSatisfaction']; // Get the website satisfaction from the POST request

if ($virtualTourSatisfaction !== null) { // If the virtualTourSatisfaction value is set then set it to an integer otherwise set it to null
    $virtualTourSatisfaction = intval($_POST['virtualTourSatisfaction']);
} else {
    $virtualTourSatisfaction = null;
}

if ($websiteSatisfaction !== null) { // If the websiteSatisfaction value is set then set it to an integer otherwise set it to null
    $websiteSatisfaction = intval($_POST['websiteSatisfaction']);
} else {
    $websiteSatisfaction = null;
}

$attendee = new Attendee($db); // Create a new attendee object

// Set all attendee values
$attendee->setAllValues($firstName, $lastName, $email, $diplomaId, $diplomaCategoryId, $isIrlAttendee, $regionalCode, $virtualTourSatisfaction, $websiteSatisfaction);

$response = $attendee->createAttendee(); // Create the attendee in the database

echo json_encode($response); // Send the response as JSON
?>
