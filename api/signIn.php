<?php
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests from any domain TODO: Change this to the domain of the website when deploying
header("Content-Type: application/json; charset=UTF-8"); // Set the response type to JSON and set charset to UTF-8
header("Access-Control-Allow-Methods: POST"); // Allow POST method only

include_once '../config/Database.php';
include_once '../class/Admin.php';

$database = new Database();
$db = $database->getConnection();
$login = $_POST['login'];
$password = $_POST['password'];

$admin = new Admin($db, $login, $password);
$response = $admin->login();

echo json_encode($response);
?>