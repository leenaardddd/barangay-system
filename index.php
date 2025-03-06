<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Barangay Information System</title>
    <link rel="icon" type="image/png" href="assets/brgy_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .option {
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            background-color: #007BFF; /* Updated to match login button */
            color: white;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            margin-bottom: 10px;
            border: none;
        }
        .option:hover {
            background-color: #0066FF; /* Slightly darker shade on hover */
        }
    </style>
</head>
<body>
    <div class="container-box">
        <h2>Welcome!</h2>
        <div class="d-grid gap-3">
            <button class="option" onclick="location.href='login_resident.php'">Resident</button>
            <button class="option" onclick="location.href='login_official.php'">Barangay Official</button>
        </div>
    </div>
</body>
</html>
