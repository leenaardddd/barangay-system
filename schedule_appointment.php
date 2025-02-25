<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resident_id = $_POST['resident_id'];
    $appointment_date = $_POST['appointment_date'];
    $purpose = $_POST['purpose'];

    $stmt = $conn->prepare("INSERT INTO appointments (resident_id, appointment_date, purpose) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $resident_id, $appointment_date, $purpose);
    if ($stmt->execute()) {
        echo "Appointment scheduled!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>