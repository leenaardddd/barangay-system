<?php
session_start();
include 'db_connect.php';

// Debugging: Print session variables
echo '<div class="alert alert-info">You are logged in as ' . $_SESSION['username'] . ' (' . $_SESSION['role'] . ')</div>';

// Check if the user is an official
$is_official = isset($_SESSION['role']) && $_SESSION['role'] === 'official';
if (!$is_official) {
    echo '<div class="alert alert-danger">You are not authorized to perform this action.</div>';
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE a complaint
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_complaint'])) {
    $resident_name = $_POST['resident_name'];
    $details = $_POST['details'];

    $stmt = $conn->prepare("INSERT INTO complaints (resident_name, details, status) VALUES (?, ?, 'Pending')");
    $stmt->bind_param("ss", $resident_name, $details);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Complaint submitted and pending approval!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// UPDATE complaint status (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_complaint'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE complaints SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        echo "Complaint status updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// DELETE a complaint (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_complaint'])) {
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

// DELETE all complaints (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all_complaints'])) {
    $sql = "DELETE FROM complaints";
    if ($conn->query($sql) === TRUE) {
        echo "All complaints deleted!";
    } else {
        echo "Error: " . $conn->error;
    }
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
        <?php if ($is_official) { ?>
        <form method="POST" class="mb-4">
            <input type="hidden" name="delete_all_complaints" value="1">
            <button type="submit" class="btn btn-danger">Delete All Complaints</button>
        </form>
        <?php } ?>
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
                            <?php if ($is_official) { ?>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="<?php echo $row['id']; ?>" data-status="<?php echo $row['status']; ?>">Update</button>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="delete_complaint" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Complaint Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="update_complaint" value="1">
                        <input type="hidden" name="id" id="update-id">
                        <div class="mb-3">
                            <label for="update-status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="update-status" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var updateModal = document.getElementById('updateModal');
        updateModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var status = button.getAttribute('data-status');

            var modalIdInput = updateModal.querySelector('#update-id');
            var modalStatusInput = updateModal.querySelector('#update-status');

            modalIdInput.value = id;
            modalStatusInput.value = status;
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>