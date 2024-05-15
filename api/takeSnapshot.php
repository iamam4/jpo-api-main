<?php
include_once '../config/Database.php';

$database = new Database(); // Create a new database object
$db = $database->getConnection(); // Get database connection

// Get latest snapshot if there is one
$query = "SELECT * FROM analyticsSnapshots ORDER BY id DESC LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Select total number of attendees
$query2 = "SELECT COUNT(*) FROM attendees";
$stmt2 = $db->prepare($query2);
$stmt2->execute();
$totalAttendees = $stmt2->fetchColumn();

if ($row) { // If there is a snapshot
    $newAttendees = $totalAttendees - $row['attendeesCount']; // Calculate number of new attendees
    // Insert new snapshot with updated data
    $query3 = "INSERT INTO analyticsSnapshots (attendeesCount, numberOfNewAttendees) VALUES (:attendeesCount, :numberOfNewAttendees)";
    $stmt3 = $db->prepare($query3);

    $stmt3->bindParam(":attendeesCount", $totalAttendees);
    $stmt3->bindParam(":numberOfNewAttendees", $newAttendees);

    $stmt3->execute();
} else { // If there is no snapshot
    $newAttendees = 0; // Set number of new attendees to 0

    // Insert new snapshot with updated data
    $query3 = "INSERT INTO analyticsSnapshots (attendeesCount, numberOfNewAttendees) VALUES (:attendeesCount, :numberOfNewAttendees)";
    $stmt3 = $db->prepare($query3);

    $stmt3->bindParam(":attendeesCount", $totalAttendees);
    $stmt3->bindParam(":numberOfNewAttendees", $newAttendees);

    $stmt3->execute();
}
?>