<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $business_name = $_POST['business_name'];
    $owner_name = $_POST['owner_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO businesses (business_name, owner_name, address, contact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $business_name, $owner_name, $address, $contact);
    if ($stmt->execute()) {
        echo "Business registered!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
