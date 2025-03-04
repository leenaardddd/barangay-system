<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Track Request/Complaint Status</h1>
        <?php
        if (isset($_GET['tracking_id'])) {
            $tracking_id = $_GET['tracking_id'];
            // Example status data
            $status = "In Progress"; // Replace with actual status retrieval logic
            echo "<p>Status for Tracking ID <strong>$tracking_id</strong>: $status</p>";
        } else {
            echo "<p>No tracking ID provided.</p>";
        }
        ?>
    </div>
</body>
</html>
