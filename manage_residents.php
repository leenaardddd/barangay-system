<?php
session_start();
include 'db_connect.php';

// CREATE a new resident
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_resident'])) {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO residents (full_name, birthdate, address, contact_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $birthdate, $address, $contact);
    if ($stmt->execute()) {
        echo "New resident added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// UPDATE resident details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_resident'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("UPDATE residents SET full_name=?, birthdate=?, address=?, contact_number=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $birthdate, $address, $contact, $id);
    if ($stmt->execute()) {
        echo "Resident details updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// DELETE a resident
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_resident'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM residents WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Resident removed!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// READ all residents
$sql = "SELECT * FROM residents";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Residents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Resident Records</h2>
        <form method="POST" class="mb-4">
            <input type="hidden" name="add_resident" value="1">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Birthdate</label>
                <input type="date" class="form-control" name="birthdate" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" class="form-control" name="contact" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Resident</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Birthdate</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['birthdate']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="delete_resident" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="update_resident" value="1">
                                <button type="submit" class="btn btn-warning btn-sm">Update</button>
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
