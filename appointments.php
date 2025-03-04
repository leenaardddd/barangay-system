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
            echo '<div class="alert alert-success">Appointment scheduled and pending approval!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    } else {
        echo '<div class="alert alert-danger">Error: Resident ID does not exist.</div>';
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
        echo "Appointment updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// DELETE an appointment (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_appointment'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Appointment deleted!";
    } else {
        echo "Error: " . $stmt->error;
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
</head>
<body>
    <div class="container mt-4">
        <h2>Schedule a New Appointment</h2>
        <form method="POST" class="mb-4">
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

        <h2>Appointments</h2>
        <table class="table table-bordered">
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
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if ($is_official) { ?>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="delete_appointment" value="1">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="update_appointment" value="1">
                                    <input type="date" name="appointment_date" value="<?php echo $row['appointment_date']; ?>" required>
                                    <input type="text" name="purpose" value="<?php echo $row['purpose']; ?>" required>
                                    <select name="status" class="form-select form-select-sm" required>
                                        <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Approved" <?php if ($row['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                                    </select>
                                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>