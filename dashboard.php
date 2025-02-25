<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Barangay Information System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0A2A56;
            color: white;
            text-align: center;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 65vh;
            flex-direction: column;
        }
        .welcome-container {
            height: auto;
            padding: 20px 0;
        }
        .selection {
            display: flex;
            justify-content: space-around;
            width: 80%;
            flex-wrap: wrap;
        }
        .option {
            flex: 1;
            padding: 50px;
            margin: 20px;
            background-color: #103D6B;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        .option:hover {
            transform: scale(1.05);
            background-color: #0A2A56;
        }
        .option img {
            width: 100px;
            margin-bottom: 20px;
        }
        .navbar-brand {
            font-size: 1.5rem;
        }
        .btn-logout {
            background-color: #f8f9fa;
            color: #0A2A56;
            border: none;
        }
        .btn-logout:hover {
            background-color: #e2e6ea;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Digital Barangay Information System</a>
            <a class="btn btn-logout" href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="container welcome-container mt-4">
        <h2>Welcome, <?php echo $_SESSION["user"]; ?>!</h2>
    </div>
    <div class="container">
        <div class="selection">
            <div class="option" onclick="location.href='resident-dashboard.php'">
                <img src="resident_icon.png" alt="Resident">
                <h2>Resident</h2>
            </div>
            <div class="option" onclick="location.href='barangay-official-dashboard.php'">
                <img src="official_icon.png" alt="Barangay Official">
                <h2>Barangay Official</h2>
            </div>
        </div>
    </div>
</body>
</html>
