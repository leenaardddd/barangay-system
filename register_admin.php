<?php
// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "barangay_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set username and hashed password
$username = "user"; // Change this if needed
$password = password_hash("asd", PASSWORD_DEFAULT); // Securely hash password

// Insert into the users table
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
