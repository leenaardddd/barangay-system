<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Directory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 40px;
            max-width: 800px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .list-group-item {
            margin-bottom: 10px;
        }
        .navbar {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">Online Directory</a>
            <div class="text-white">
                <i class="fas fa-user-check"></i> Welcome, <strong><?php echo $_SESSION['username'] ?? 'Guest'; ?></strong>!
                <a class="btn btn-outline-light" href="index.php">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="card p-4">
            <div>
                <button class="btn btn-link" onclick="window.history.back()"><i class="bi bi-arrow-left" style="color: #6c757d;"></i></button>
            </div>
            <h1 class="text-center mb-4"><strong>DIRECTORY</strong></h1>
            <h2>Barangay Officials</h2>
            <div class="list-group mb-4">
                <!-- Example official entry -->
                <div class="list-group-item">
                    <h5 class="mb-1">Official Name</h5>
                    <p class="mb-1">Position: Barangay Captain</p>
                    <p class="mb-1">Contact: 123-456-7890</p>
                </div>
                <!-- Add more official entries as needed -->
            </div>
            <h2>Services</h2> 
            <div class="list-group mb-4">
                <!-- Example service entry -->
                <div class="list-group-item">
                    <h5 class="mb-1">Service Name</h5>
                    <p class="mb-1">Description: Health Clinic</p>
                    <p class="mb-1">Contact: 098-765-4321</p>
                </div>
                <!-- Add more service entries as needed -->
            </div>
            <h2>Emergency Contacts</h2>
            <div class="list-group">
                <!-- Example emergency contact entry -->
                <div class="list-group-item">
                    <h5 class="mb-1">Emergency Service</h5>
                    <p class="mb-1">Contact: 911</p>
                </div>
                <!-- Add more emergency contact entries as needed -->
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
