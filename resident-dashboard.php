<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(175, 174, 174);
        }
        .resident-info {
        margin-top: 32px; 
        }

        .resident-card {
            background-color: #d6d6d6;
            padding: 20px;
            border-radius: 10px;
        }
        .resident-info p {
            margin-bottom: 10px;
        }
        .btn-custom {
            font-size: 12px;
            padding: 14px 24px;
            border-radius: 8px;
            width: 100%;
            color: white;
        }
        .btn-lightblue {
            background-color:rgb(96, 155, 201);
        }
        .btn-darkblue {
            background-color: #1e3a8a;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Resident Dashboard</a>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="resident-card p-4 mb-4 d-flex align-items-center">
            <div>
                <img src="quiboloy.jpg" alt="Resident Photo" class="rounded-circle" style="width: 160px; height: 160px; background: gray;">
            </div>
            <div class="ms-4">
                <h2 class="fw-bold">ROBERTO MAGALINGSANA</h2>
                <p><strong>ID:</strong> 12345678</p>
                <div class="resident-info">
                    <h6><strong>Resdient Info</strong></h6>
                    <p><strong>Birthday:</strong> Feb 27, 2005</p>
                    <p><strong>Contact Number:</strong> 09278535325</p>
                    <p><strong>Address:</strong> Sampong liko sa kana pag dating sa likod uwi kana</p>
                </div>
            </div>
        </div>
        <div class="mb-4 d-flex btn-group-spacing">
            <button class="btn btn-custom btn-lightblue" style="margin-right: 20px;">View Barangay Announcements</button>
            <button class="btn btn-custom btn-lightblue" style="margin-right: 20px;">Check Online Directory</button>
            <button class="btn btn-custom btn-lightblue">Track Request/Complaint Status</button>
        </div>
        <div class="row">
            <div class="col-md-4">
                <button class="btn btn-custom btn-darkblue" data-bs-toggle="collapse" data-bs-target="#complaintForm">Submit a Complaint</button>
                <div class="collapse mt-3" id="complaintForm">
                    <div class="card p-3">
                        <form action="complaints.php" method="POST">
                            <input type="hidden" name="submit_complaint" value="1">
                            <div class="mb-3">
                                <label for="resident_name" class="form-label">Resident Name</label>
                                <input type="text" class="form-control" name="resident_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="details" class="form-label">Complaint Details</label>
                                <textarea class="form-control" name="details" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-custom btn-darkblue" data-bs-toggle="collapse" data-bs-target="#appointmentForm">Schedule Appointment</button>
                <div class="collapse mt-3" id="appointmentForm">
                    <div class="card p-3">
                        <form action="appointments.php" method="POST">
                            <input type="hidden" name="schedule_appointment" value="1">
                            <div class="mb-3">
                                <label for="resident_id" class="form-label">Resident ID</label>
                                <input type="text" class="form-control" name="resident_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="appointment_date" class="form-label">Appointment Date</label>
                                <input type="date" class="form-control" name="appointment_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose</label>
                                <textarea class="form-control" name="purpose" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Schedule</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-custom btn-darkblue" data-bs-toggle="collapse" data-bs-target="#businessForm">Register a Business</button>
                <div class="collapse mt-3" id="businessForm">
                    <div class="card p-3">
                        <form action="businesses.php" method="POST">
                            <input type="hidden" name="register_business" value="1">
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" name="business_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="owner_name" class="form-label">Owner Name</label>
                                <input type="text" class="form-control" name="owner_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" name="contact" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
