<?php 
session_start();
require 'db_connect.php';

$error_message = ""; // Variable to store error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? AND role = 'resident'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Set the role in the session
            header("Location: resident_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid login credentials!";
        }
    } else {
        $error_message = "Invalid login credentials!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Login</title>
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
        .login-container {
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
        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
    <script>
        function togglePassword() {
            let password = document.getElementById("password");
            password.type = password.type === "password" ? "text" : "password";
        }
    </script>
</head>
<body>
    <div class="login-container">
    <button class="btn btn-link" onclick="window.history.back()"><i class="bi bi-arrow-left" style="color: #6c757d;"></i></button> 
        <h2 class="text-center">Resident Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group mb-3">
                <div class="form-floating flex-grow-1">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁</button>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="mt-3 text-center">
            <a href="register_resident.php" class="btn btn-secondary w-100">Create a Resident Account</a>
        </div>
    </div>
</body>
</html>

