<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Official Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            padding: 1rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .btn-danger {
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: bold;
        }
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .dashboard-item {
            width: 300px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold" href="#">Barangay Official Dashboard</a>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="dashboard-container">
            <div class="dashboard-item">
                <div class="card text-center p-4">
                    <h5>Manage Residents</h5>
                    <a href="manage_residents.php" class="btn btn-danger mt-2">View</a>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="card text-center p-4">
                    <h5>Review Complaints</h5>
                    <a href="complaints.php" class="btn btn-danger mt-2">Check</a>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="card text-center p-4">
                    <h5>Post Announcements</h5>
                    <a href="announcements.php" class="btn btn-danger mt-2">Update</a>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="card text-center p-4">
                    <h5>Manage Businesses</h5>
                    <a href="businesses.php" class="btn btn-danger mt-2">Manage</a>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="card text-center p-4">
                    <h5>Manage Appointments</h5>
                    <a href="appointments.php" class="btn btn-danger mt-2">Manage</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
