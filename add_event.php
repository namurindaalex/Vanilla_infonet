<?php
// add_event.php

// Database connection
$mysqli = new mysqli("localhost", "root", "", "VANILLA_INFONET");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get form data
$day = $_POST['day'];
$month = $_POST['month'];
$description = $_POST['description'];
$venue = $_POST['venue'];
$more_info = $_POST['more_info'];

// Prepare and execute the insert query
$stmt = $mysqli->prepare("INSERT INTO Events (day, month, Description, Venue, More_info) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $day, $month, $description, $venue, $more_info);

if ($stmt->execute()) {
    echo "New event added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$mysqli->close();
?>
