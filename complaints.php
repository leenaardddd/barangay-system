<?php
session_start();
include 'db_connect.php';

// Ensure `$is_official` is always defined
$is_official = false;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'official') {
    $is_official = true;
}

// Prevent unauthorized access
if (!$is_official) {
    $notification = '<div class="alert alert-danger popup-notification">You are not authorized to perform this action.</div>';
}

if ($conn->connect_error) {
    die('<div class="alert alert-danger popup-notification">Database Connection Failed: ' . $conn->connect_error . '</div>');
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_complaint']) && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $delete_sql = "DELETE FROM complaints WHERE id = $id";
        if ($conn->query($delete_sql) === TRUE) {
            $popup_message = 'Complaint deleted successfully.';
        } else {
            $popup_message = 'Error deleting complaint: ' . $conn->error;
        }
    }

    if (isset($_POST['update_complaint']) && isset($_POST['id']) && isset($_POST['status'])) {
        $id = intval($_POST['id']);
        $status = $conn->real_escape_string($_POST['status']);
        $update_sql = "UPDATE complaints SET status = '$status' WHERE id = $id";
        if ($conn->query($update_sql) === TRUE) {
            $popup_message = 'Complaint status updated successfully.';
        } else {
            $popup_message = 'Error updating complaint status: ' . $conn->error;
        }
    }

    if (isset($_POST['delete_all_complaints'])) {
        $delete_all_sql = "DELETE FROM complaints";
        if ($conn->query($delete_all_sql) === TRUE) {
            $popup_message = 'All complaints deleted successfully.';
        } else {
            $popup_message = 'Error deleting all complaints: ' . $conn->error;
        }
    }

    if (isset($_POST['submit_complaint']) && isset($_POST['resident_name']) && isset($_POST['details'])) {
        $resident_name = $conn->real_escape_string($_POST['resident_name']);
        $details = $conn->real_escape_string($_POST['details']);
        $insert_sql = "INSERT INTO complaints (resident_name, details, status) VALUES ('$resident_name', '$details', 'Pending')";
        if ($conn->query($insert_sql) === TRUE) {
            $popup_message = 'Complaint submitted successfully.';
        } else {
            $popup_message = 'Error submitting complaint: ' . $conn->error;
        }
    }
}

// Fetch complaints with error handling
$sql = "SELECT * FROM complaints";
$result = $conn->query($sql);

if (!$result) {
    die('<div class="alert alert-danger popup-notification">Error fetching complaints: ' . $conn->error . '</div>');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Management</title>
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
        .btn-primary {
            background-color: #007BFF;
            border-color: #007BFF;
        }
        .btn-primary:hover {
            background-color: #0066FF;
        }
        .table th {
            background-color: #007BFF;
            color: white;
        }
        .modal-content {
            border-radius: 12px;
        }
        .login-message {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 14px;
            border: 1px solid #bee5eb;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .login-message i {
            margin-right: 8px;
            color: #0c5460;
        }
        .popup-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            display: none;
        }
        .card-table {
            overflow-x: auto;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">Complaints Management</a>
            <div class="text-white">
                <i class="fas fa-user-check"></i> Welcome, <strong><?php echo $_SESSION['username'] ?? 'Guest'; ?></strong>! 
                You are logged in as an <strong><?php echo $_SESSION['role'] ?? 'No Role'; ?></strong>.
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card p-4">
            <h2 class="text-center">Submit a Complaint</h2>
            <form method="POST" class="mt-3">
                <input type="hidden" name="submit_complaint" value="1">
                <div class="mb-3">
                    <label for="resident_name" class="form-label">Resident Name</label>
                    <input type="text" class="form-control" name="resident_name" required>
                </div>
                <div class="mb-3">
                    <label for="details" class="form-label">Complaint Details</label>
                    <textarea class="form-control" name="details" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit Complaint</button>
            </form>
        </div>

        <div class="card p-4 mt-4 card-table">
            <h2 class="text-center">Complaints</h2>
            <?php if ($is_official) { ?>
            <form method="POST" class="mb-3 text-center">
                <input type="hidden" name="delete_all_complaints" value="1">
                <button type="submit" class="btn btn-danger">Delete All Complaints</button>
            </form>
            <?php } ?>
            <table class="table table-bordered text-center">
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
                            <td>
                                <span class="badge 
                                    <?php echo ($row['status'] == 'Pending') ? 'bg-warning' : 'bg-success'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
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
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="popup-notification alert alert-success" id="action-success-popup"><?php echo $popup_message ?? ''; ?></div>

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

        // Show popup notification
        var actionSuccessPopup = document.getElementById('action-success-popup');
        if (actionSuccessPopup && actionSuccessPopup.innerHTML.trim() !== '') {
            actionSuccessPopup.style.display = 'block';
            setTimeout(function() {
                actionSuccessPopup.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>