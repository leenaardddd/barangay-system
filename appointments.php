<?php
session_start();
include 'db_connect.php';

// Check if the user is an official
$is_official = isset($_SESSION['role']) && $_SESSION['role'] === 'official';

// Prevent unauthorized access
if (!$is_official) {
    $notification = '<div class="alert alert-danger">You are not authorized to perform this action.</div>';
}

if ($conn->connect_error) {
    die('<div class="alert alert-danger">Database Connection Failed: ' . $conn->connect_error . '</div>');
}

// CREATE an appointment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['schedule_appointment'])) {
    $resident_id = $_POST['resident_id'];
    $appointment_date = $_POST['appointment_date'];
    $purpose = $_POST['purpose'];

    // Check if resident_id exists
    $stmt = $conn->prepare("SELECT id FROM residents WHERE id = ?");
    $stmt->bind_param("i", $resident_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO appointments (resident_id, appointment_date, purpose, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("iss", $resident_id, $appointment_date, $purpose);
        if ($stmt->execute()) {
            $notification = '<div class="alert alert-success">Appointment scheduled and pending approval!</div>';
            $popup_message = 'Appointment scheduled!';
        } else {
            $notification = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    } else {
        $notification = '<div class="alert alert-danger">Error: Resident ID does not exist.</div>';
        $stmt->close();
    }
}

// UPDATE an appointment (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_appointment'])) {
    $id = $_POST['id'];
    $appointment_date = $_POST['appointment_date'];
    $purpose = $_POST['purpose'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE appointments SET appointment_date=?, purpose=?, status=? WHERE id=?");
    $stmt->bind_param("sssi", $appointment_date, $purpose, $status, $id);
    if ($stmt->execute()) {
        $notification = '<div class="alert alert-success">Appointment updated!</div>';
        $popup_message = 'Appointment updated!';
    } else {
        $notification = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// DELETE an appointment (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_appointment'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $notification = '<div class="alert alert-success">Appointment deleted!</div>';
        $popup_message = 'Appointment deleted!';
    } else {
        $notification = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// READ all appointments
$sql = "SELECT * FROM appointments ORDER BY appointment_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Management</title>
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
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">Appointments Management</a>
            <div class="text-white">
                <i class="fas fa-user-check"></i> Welcome, <strong><?php echo $_SESSION['username'] ?? 'Guest'; ?></strong>! 
                You are logged in as an <strong><?php echo $_SESSION['role'] ?? 'No Role'; ?></strong>.
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card p-4">
            <h2 class="text-center">Schedule a New Appointment</h2>
            <form method="POST" class="mt-3">
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
                <button type="submit" class="btn btn-primary w-100">Schedule Appointment</button>
            </form>
        </div>

        <div class="card p-4 mt-4">
            <h2 class="text-center">Appointments</h2>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Resident ID</th>
                        <th>Appointment Date</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['resident_id']; ?></td>
                            <td><?php echo $row['appointment_date']; ?></td>
                            <td><?php echo $row['purpose']; ?></td>
                            <td>
                                <span class="badge 
                                    <?php echo ($row['status'] == 'Pending') ? 'bg-warning' : 'bg-success'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($is_official) { ?>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="<?php echo $row['id']; ?>" data-appointment_date="<?php echo $row['appointment_date']; ?>" data-purpose="<?php echo $row['purpose']; ?>" data-status="<?php echo $row['status']; ?>">Update</button>
                                    <form method="POST" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="delete_appointment" value="1">
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
                    <h5 class="modal-title" id="updateModalLabel">Update Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="update_appointment" value="1">
                        <input type="hidden" name="id" id="update-id">
                        <div class="mb-3">
                            <label for="update-appointment_date" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" name="appointment_date" id="update-appointment_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-purpose" class="form-label">Purpose</label>
                            <textarea class="form-control" name="purpose" id="update-purpose" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="update-status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="update-status" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Appointment</button>
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
            var appointment_date = button.getAttribute('data-appointment_date');
            var purpose = button.getAttribute('data-purpose');
            var status = button.getAttribute('data-status');

            var modalIdInput = updateModal.querySelector('#update-id');
            var modalAppointmentDateInput = updateModal.querySelector('#update-appointment_date');
            var modalPurposeInput = updateModal.querySelector('#update-purpose');
            var modalStatusInput = updateModal.querySelector('#update-status');

            modalIdInput.value = id;
            modalAppointmentDateInput.value = appointment_date;
            modalPurposeInput.value = purpose;
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