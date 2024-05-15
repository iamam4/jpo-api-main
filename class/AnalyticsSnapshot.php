<?php
class AnalyticsSnapshot
{
    // Connection
    private $conn;
    // Table
    private string $db_table = "analyticsSnapshots";
    // Columns
    private $id;

    private datetime $date;

    private int $attendeesCount;

    private int $numberOfNewAttendees;

    // Db connection
    public function __construct($db, $date, $attendeesCount, $numberOfNewAttendees)
    {
        $this->conn = $db;
        $this->date = $date;
        $this->attendeesCount = $attendeesCount;
        $this->numberOfNewAttendees = $numberOfNewAttendees;
    }
}
