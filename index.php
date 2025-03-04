<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Barangay Information System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #0A2A56;
            color: white;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #103D6B; 
            padding: 10px 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
        }
        .selection {
            display: flex;
            justify-content: center;
            gap: 15px; /* Reduced spacing */
        }
        .option {
            width: 220px;
            padding: 20px;
            background: #103D6B;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            font-size: 20px; /* Increased text size */
            font-weight: bold;
        }
        .option:hover {
            transform: scale(1.05);
            background: #0C3A63;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
        }
        .btn-logout {
            background-color: white;
            color: #D32F2F;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-logout:hover {
            background-color: #f2f2f2;
        }
        footer {
            background-color: #103D6B;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand text-white fw-bold">Digital Barangay Information System</a>
            <a class="btn btn-logout" href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Welcome!</h2>
        <div class="selection">
            <div class="option" onclick="location.href='login_resident.php'">
                Resident
            </div>
            <div class="option" onclick="location.href='login_official.php'">
                Barangay Official
            </div>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Digital Barangay Information System. All rights reserved.
    </footer>
</body>
</html>
