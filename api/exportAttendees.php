<?php
header("Access-Control-Allow-Origin: *"); // Allow access from any origin for CORS. TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: GET"); // Allow GET method only

include_once '../config/Database.php';
include_once 'checkAuthentication.php'; // Check if the user is authenticated
include_once '../vendor/autoload.php';

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

$query = "SELECT * FROM attendees"; // Query to get all attendees
$stmt = $db->prepare($query);
$stmt->execute();

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add headers 
$sheet->setCellValue('A1', 'ID');

$sheet->setCellValue('B1', 'Prenom');
// Set the width of the column to 20
$sheet->getColumnDimension('B')->setWidth(20);

$sheet->setCellValue('C1', 'Nom');
// Set the width of the column to 20
$sheet->getColumnDimension('C')->setWidth(20);

$sheet->setCellValue('D1', 'Email');
// Set the width of the column to 50
$sheet->getColumnDimension('D')->setWidth(50);

$sheet->setCellValue('E1', 'Diplome');
// Set the width of the column to 20
$sheet->getColumnDimension('E')->setWidth(20);

$sheet->setCellValue('F1', 'Categorie de diplome');
// Set the width of the column to 20
$sheet->getColumnDimension('F')->setWidth(20);

$sheet->setCellValue('G1', 'Participation');
// Set the width of the column to 20
$sheet->getColumnDimension('G')->setWidth(20);

$sheet->setCellValue('H1', 'Region');
// Set the width of the column to 30
$sheet->getColumnDimension('H')->setWidth(30);

$sheet->setCellValue('I1', 'Satisfaction visite virtuelle');
// Set the width of the column to 30
$sheet->getColumnDimension('I')->setWidth(30);

$sheet->setCellValue('J1', 'Satisfaction site web');
// Set the width of the column to 20
$sheet->getColumnDimension('J')->setWidth(20);

// Add data from the database
$row = 2; 
while ($row_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Get the name of the diploma
    $query = "SELECT diplomaName FROM diplomaTypes WHERE diplomaId = " . $row_data['diplomaId'];
    $stmt2 = $db->prepare($query);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $diplomaName = $row2['diplomaName'];

    // Get the name of the diploma category
    $query = "SELECT categoryName FROM diplomaCategories WHERE id = " . $row_data['diplomaCategoryId'];
    $stmt2 = $db->prepare($query);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $diplomaCategoryName = $row2['categoryName'];

    // Get the region name
    $query = "SELECT name FROM regions WHERE code = " . '"' . $row_data['regionalCode'] . '"';
    $stmt2 = $db->prepare($query);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $regionName = $row2['name'];
    
    $sheet->setCellValue('A' . $row, $row_data['id']); 
    $sheet->setCellValue('B' . $row, $row_data['firstName']); 
    $sheet->setCellValue('C' . $row, $row_data['lastName']); 
    $sheet->setCellValue('D' . $row, $row_data['email']);
    $sheet->setCellValue('E' . $row, $diplomaName);
    $sheet->setCellValue('F' . $row, $diplomaCategoryName);
    // If the value is 1 then set the value to "Présentielle", if the value is 0 then set the value to "Distancielle"
    $sheet->setCellValue('G' . $row, $row_data['isIrlAttendee'] == 1 ? "Présentielle" : "Distancielle");
    $sheet->setCellValue('H' . $row, $regionName);
    // If the value is 0 then set the value to "Agréable", if the value is 1 then set the value to "Neutre" and if the value is 2 then set the value to "Désagréable"
    $sheet->setCellValue('I' . $row, $row_data['virtualTourSatisfaction'] == 0 ? "Agréable" : ($row_data['virtualTourSatisfaction'] == 1 ? "Neutre" : "Désagréable"));
    // If the value is 0 then set the value to "Agréable", if the value is 1 then set the value to "Neutre" and if the value is 2 then set the value to "Désagréable"
    $sheet->setCellValue('J' . $row, $row_data['websiteSatisfaction'] == 0 ? "Agréable" : ($row_data['websiteSatisfaction'] == 1 ? "Neutre" : "Désagréable"));
    $row++;
}

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
// Save the file as 'liste_des_participants_' + the current date and time + '.xlsx'
$fileName = 'liste_des_participants_jpo_' . date('Y-m-d_H-i-s') . '.xlsx';
$writer->save('../documents/' . $fileName);

$response = array(
    "status" => "success",
    "message" => "Le fichier a été exporté avec succès",
    "fileName" => $fileName
);

echo json_encode($response); // Send the response as JSON
?>