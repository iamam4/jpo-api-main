<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: POST"); // Allow POST method only

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

global $isAuth; // Create a global variable to store the authentication status
$isAuth = false; // Set the authentication status to false by default

$showPercentagesOnCharts = $_POST['showPercentagesOnCharts'] == "true" ? 1 : 0; // Get the showPercentagesOnCharts value from the POST request
$showLegendOnCharts = $_POST['showLegendOnCharts'] == "true" ? 1 : 0; // Get the showLegendOnCharts value from the POST request
$defaultTheme = $_POST['defaultTheme']; // Get the defaultTheme value from the POST request

// Get the token from the request headers
foreach (getallheaders() as $name => $value) { // Go through each header
    if ($name == "Authorization") { // Check if the header is the Authorization header
        $token = $value; // Set the token variable to the value of the Authorization header
    }
}

// Query to check if the token exists in the database
$query = "SELECT * FROM sessions
WHERE token = :token";

$stmt = $db->prepare($query);
$stmt->bindParam(':token', $token); // Bind the token value to the query
$stmt->execute();

// Retrieve the user id
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Go through each row
    extract($row); // Extract row data
    $userId = $adminId; // Set the user id to the admin id
}

// Query to update user preferences
$query = "UPDATE userPreferences SET 
        defaultTheme = :defaultTheme,
        showPercentagesOnCharts = :showPercentagesOnCharts,
        showLegendOnCharts = :showLegendOnCharts
        WHERE adminId = :adminId";

$stmt = $db->prepare($query);

// Bind the values to the query
$stmt->bindParam(':showPercentagesOnCharts', $showPercentagesOnCharts);
$stmt->bindParam(':showLegendOnCharts', $showLegendOnCharts);
$stmt->bindParam(':defaultTheme', $defaultTheme);
$stmt->bindParam(':adminId', $userId);
$stmt->execute(); // Execute the query

// Return the user preferences
$query = "SELECT * FROM userPreferences WHERE adminId = :adminId"; // Query to get the user preferences
$stmt = $db->prepare($query);
$stmt->bindParam(':adminId', $userId); // Bind the user id to the query
$stmt->execute();
$row["userPreferences"] = $stmt->fetch(PDO::FETCH_ASSOC); // Get the user preferences

// Go through each user preference except the first one and set the value to true if it is 1 and false if it is 0
foreach ($row["userPreferences"] as $key => $value) {
    if ($key != "adminId") { // Check if the key is not id or adminId
        if ($key == 'defaultTheme') { // Check if the key is defaultTheme
            $row["userPreferences"][$key] = $value; // Set the value to the value in the database
        } else {
            if ($value == 1) {
                $row["userPreferences"][$key] = true; // Set the value to true
            } else {
                $row["userPreferences"][$key] = false; // Set the value to false
            }
        }
    }
}

// Set response as an array with user preferences
$response = array(
    'status' => 'success',
    'message' => 'Préférences enregistrées',
    'userPreferences' => $row["userPreferences"]
);

echo json_encode($response); // Send response as JSON
