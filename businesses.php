<?php
session_start();
include 'db_connect.php';

// Check if the user is an official
$is_official = isset($_SESSION['role']) && $_SESSION['role'] === 'official';

// CREATE a business registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_business'])) {
    $business_name = $_POST['business_name'];
    $owner_name = $_POST['owner_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO businesses (business_name, owner_name, address, contact, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("ssss", $business_name, $owner_name, $address, $contact);
    if ($stmt->execute()) {
        echo "Business registered and pending approval!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// UPDATE a business registration (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_business'])) {
    $id = $_POST['id'];
    $business_name = $_POST['business_name'];
    $owner_name = $_POST['owner_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE businesses SET business_name=?, owner_name=?, address=?, contact=?, status=? WHERE id=?");
    $stmt->bind_param("sssssi", $business_name, $owner_name, $address, $contact, $status, $id);
    if ($stmt->execute()) {
        echo "Business updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// DELETE a business registration (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_business'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM businesses WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Business deleted!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// READ all businesses
$sql = "SELECT * FROM businesses ORDER BY business_name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Business Management</a>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Register a New Business</h2>
        <form method="POST" class="mb-4">
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

        <h2>Registered Businesses</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Business Name</th>
                    <th>Owner Name</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['business_name']; ?></td>
                        <td><?php echo $row['owner_name']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['contact']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if ($is_official) { ?>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="<?php echo $row['id']; ?>" data-business_name="<?php echo $row['business_name']; ?>" data-owner_name="<?php echo $row['owner_name']; ?>" data-address="<?php echo $row['address']; ?>" data-contact="<?php echo $row['contact']; ?>" data-status="<?php echo $row['status']; ?>">Update</button>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="delete_business" value="1">
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
                    <h5 class="modal-title" id="updateModalLabel">Update Business</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="update_business" value="1">
                        <input type="hidden" name="id" id="update-id">
                        <div class="mb-3">
                            <label for="update-business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" name="business_name" id="update-business_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-owner_name" class="form-label">Owner Name</label>
                            <input type="text" class="form-control" name="owner_name" id="update-owner_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="update-address" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-contact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contact" id="update-contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="update-status" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Business</button>
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
            var business_name = button.getAttribute('data-business_name');
            var owner_name = button.getAttribute('data-owner_name');
            var address = button.getAttribute('data-address');
            var contact = button.getAttribute('data-contact');
            var status = button.getAttribute('data-status');

            var modalIdInput = updateModal.querySelector('#update-id');
            var modalBusinessNameInput = updateModal.querySelector('#update-business_name');
            var modalOwnerNameInput = updateModal.querySelector('#update-owner_name');
            var modalAddressInput = updateModal.querySelector('#update-address');
            var modalContactInput = updateModal.querySelector('#update-contact');
            var modalStatusInput = updateModal.querySelector('#update-status');

            modalIdInput.value = id;
            modalBusinessNameInput.value = business_name;
            modalOwnerNameInput.value = owner_name;
            modalAddressInput.value = address;
            modalContactInput.value = contact;
            modalStatusInput.value = status;
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>