<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Resident Dashboard</a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>File a Complaint</h5>
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
                        <button type="submit" class="btn btn-primary">Submit Complaint</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Book an Appointment</h5>
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
                        <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Register a Business</h5>
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
                        <button type="submit" class="btn btn-primary">Register Business</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
