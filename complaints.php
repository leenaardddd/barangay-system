<?php
session_start();
include 'db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE a complaint
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_complaint'])) {
    $resident_name = $_POST['resident_name'];
    $details = $_POST['details'];

    $stmt = $conn->prepare("INSERT INTO complaints (resident_name, details) VALUES (?, ?)");
    $stmt->bind_param("ss", $resident_name, $details);
    if ($stmt->execute()) {
        echo "Complaint submitted!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// UPDATE complaint status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_complaint'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE complaints SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        echo "Complaint updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// DELETE a complaint
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_complaint'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM complaints WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Complaint deleted!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// READ all complaints
$sql = "SELECT * FROM complaints";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Submit a Complaint</h2>
        <form method="POST" class="mb-4">
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

        <h2>Complaints</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Resident Name</th>
                    <th>Complaint Details</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['resident_name']; ?></td>
                        <td><?php echo $row['details']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="In Progress" <?php if ($row['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                    <option value="Resolved" <?php if ($row['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                </select>
                                <input type="hidden" name="update_complaint" value="1">
                                <button type="submit" class="btn btn-warning btn-sm mt-1">Update</button>
                            </form>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="delete_complaint" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>