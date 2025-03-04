<?php
session_start();
include 'db_connect.php';

// Debugging: Print session variables
echo '<div class="alert alert-info">You are logged in as ' . $_SESSION['username'] . ' (' . $_SESSION['role'] . ')</div>';

// CREATE a new resident
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_resident'])) {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO residents (full_name, birthdate, address, contact_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $birthdate, $address, $contact);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">New resident added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
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
        echo '<div class="alert alert-success">Resident details updated!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// DELETE a resident
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_resident'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM residents WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Resident removed!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
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
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['full_name']; ?>" data-birthdate="<?php echo $row['birthdate']; ?>" data-address="<?php echo $row['address']; ?>" data-contact="<?php echo $row['contact_number']; ?>">Update</button>
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
                    <h5 class="modal-title" id="updateModalLabel">Update Resident</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="update_resident" value="1">
                        <input type="hidden" name="id" id="update-id">
                        <div class="mb-3">
                            <label for="update-name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" id="update-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate" id="update-birthdate" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="update-address" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-contact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contact" id="update-contact" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Resident</button>
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
            var name = button.getAttribute('data-name');
            var birthdate = button.getAttribute('data-birthdate');
            var address = button.getAttribute('data-address');
            var contact = button.getAttribute('data-contact');

            var modalIdInput = updateModal.querySelector('#update-id');
            var modalNameInput = updateModal.querySelector('#update-name');
            var modalBirthdateInput = updateModal.querySelector('#update-birthdate');
            var modalAddressInput = updateModal.querySelector('#update-address');
            var modalContactInput = updateModal.querySelector('#update-contact');

            modalIdInput.value = id;
            modalNameInput.value = name;
            modalBirthdateInput.value = birthdate;
            modalAddressInput.value = address;
            modalContactInput.value = contact;
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
