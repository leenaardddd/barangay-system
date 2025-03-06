<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Official Dashboard</title>
    <link rel="icon" type="image/png" href="assets/brgy_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, #007bff, #6610f2);
        }

        .container {
            margin-top: 40px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, height 0.3s ease-in-out;
            height: 120px; /* Adjust as needed */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
        }

        .card.expanded {
            height: 300px !important; /* Adjust for expanded state */
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h5 {
            font-weight: bold;
            color: #333;
        }

        .toggle-btn {
            width: 100%;
            text-align: center;
        }

        .form-container {
            display: none;
            width: 100%;
            padding: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold" href="#">Barangay Official Dashboard</a>
            <a class="btn btn-outline-light" href="index.php">Logout</a>

        </div>
    </nav>
    
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center p-3" id="reviewComplaintsCard">
                    <h5>Review Complaints</h5>
                    <button class="btn btn-primary toggle-btn" onclick="window.location.href='complaints.php'">Check</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="manageAppointmentsCard">
                    <h5>Manage Appointments</h5>
                    <button class="btn btn-primary toggle-btn" onclick="window.location.href='appointments.php'">Manage</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="manageBusinessesCard">
                    <h5>Manage Businesses</h5>
                    <button class="btn btn-primary toggle-btn" onclick="window.location.href='businesses.php'">Manage</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="postAnnouncementsCard">
                    <h5>Post Announcements</h5>
                    <button class="btn btn-primary toggle-btn" onclick="window.location.href='announcements.php'">Update</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="manageResidentsCard">
                    <h5>Manage Residents</h5>
                    <button class="btn btn-primary toggle-btn" onclick="window.location.href='manage_residents.php'">View</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
