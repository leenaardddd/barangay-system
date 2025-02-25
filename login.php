<?php
session_start();
$conn = new mysqli("localhost", "root", "", "barangay_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve the user from the database using prepared statements
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            echo "Invalid login credentials!";
        }
    } else {
        echo "Invalid login credentials!";
    }

    // Close connections
    $stmt->close();
}
$conn->close();
?>