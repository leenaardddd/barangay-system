<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Official Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Barangay Official Dashboard</a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Manage Residents</h5>
                    <a href="manage_residents.php" class="btn btn-danger">View</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Review Complaints</h5>
                    <a href="complaints.php" class="btn btn-danger">Check</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Post Announcements</h5>
                    <a href="announcements.php" class="btn btn-danger">Update</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
