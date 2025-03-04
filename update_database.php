<?php
require 'db_connect.php';

$sql = "ALTER TABLE users ADD COLUMN role VARCHAR(50) NOT NULL DEFAULT 'resident'";
if ($conn->query($sql) === TRUE) {
    echo "Table users updated successfully";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
