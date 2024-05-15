<?php
header("Access-Control-Allow-Origin: *"); // Allow access from any origin for CORS. TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: POST"); // Allow POST method only

include_once '../config/Database.php';
include_once '../class/DiplomaCategory.php';
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
$diplomaCategoryName = $_POST['diplomaCategoryName']; // Get the diploma category name from the POST request

$diplomaCategory = new DiplomaCategory($db); // Create a new diploma category object

// Set all diploma category values
$diplomaCategory->setAll($diplomaCategoryName);

$response = $diplomaCategory->modifyDiplomaCategory($id); // Modify the diploma category in the database

echo json_encode($response); // Send the response as JSON

?>