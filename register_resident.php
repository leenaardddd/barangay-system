<?php
// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "barangay_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start session for flash messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and trim spaces
    $full_name = trim($_POST['full_name']);
    $birthdate = trim($_POST['birthdate']);
    $address = trim($_POST['address']);
    $contact_number = trim($_POST['contact_number']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($full_name) || empty($birthdate) || empty($address) || empty($contact_number) || empty($username) || empty($password)) {
        $_SESSION['message'] = "All fields are required.";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $birthdate)) {
        $_SESSION['message'] = "Invalid birthdate format. Use YYYY-MM-DD.";
    } elseif (strtotime($birthdate) > time()) {
        $_SESSION['message'] = "Birthdate cannot be in the future.";
    } elseif (!preg_match("/^\d{11}$/", $contact_number)) {
        $_SESSION['message'] = "Invalid contact number format. Use 11 digits.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement for residents
        $stmt = $conn->prepare("INSERT INTO residents (full_name, birthdate, address, contact_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $birthdate, $address, $contact_number);

        // Execute query for residents
        if ($stmt->execute()) {
            // Prepare SQL statement for users
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'resident')");
            $stmt->bind_param("ss", $username, $hashed_password);

            // Execute query for users
            if ($stmt->execute()) {
                // Set session variables
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['username'] = $username;
                // Redirect to resident dashboard
                header("Location: resident_dashboard.php");
                exit();
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
            }
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close database connection
    $conn->close();

    // Redirect to the same page to show message
    header("Location: register_resident.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Resident</title>
    <link rel="icon" type="image/png" href="assets/brgy_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .register-container {
            background-color: #fff;
            padding-top: 10px;
            padding-left: 35px;
            padding-right: 35px;
            padding-bottom: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            width: 400px; /* Better width */
        }
        h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
        }
        .btn-primary {
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
        }
        .btn-secondary {
            padding: 12px;
            font-size: 14px;
            border-radius: 8px;
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
    <button class="btn btn-link" onclick="window.history.back()"><i class="bi bi-arrow-left" style="color: #6c757d;"></i></button>  
        <h2 class="text-center">Register Resident</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<p class="message">' . $_SESSION['message'] . '</p>';
            unset($_SESSION['message']); // Remove message after displaying
        }
        ?>
        <form method="post" action="register_resident.php">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" required>
                <label for="full_name">Full Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Birthdate" required>
                <label for="birthdate">Birthdate</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" maxlength="255" required>
                <label for="address">Address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number" pattern="\d{11}" maxlength="11" required>
                <label for="contact_number">Contact Number</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
            <a class="btn btn-secondary w-100" href="login_resident.php">Login</a>
            <p class="text-center">Have an account?</p>
        </form>
    </div>
</body>
</html>