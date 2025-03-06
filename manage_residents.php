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

// CREATE a new resident
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_resident'])) {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    // Validate birthdate
    if (strtotime($birthdate) > time()) {
        $notification = '<div class="alert alert-danger">Birthdate cannot be in the future.</div>';
    } elseif (!preg_match('/^\d{10}$/', $contact)) {
        $notification = '<div class="alert alert-danger">Invalid contact number format. Use 11 digits.</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO residents (full_name, birthdate, address, contact_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $birthdate, $address, $contact);
        if ($stmt->execute()) {
            $notification = '<div class="alert alert-success">New resident added successfully!</div>';
            $popup_message = 'Resident created!';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
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
        $notification = '<div class="alert alert-success">Resident details updated!</div>';
        $popup_message = 'Resident updated!';
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
        $notification = '<div class="alert alert-success">Resident removed!</div>';
        $popup_message = 'Resident deleted!';
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
    <title>Residents Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
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
            <a class="navbar-brand" href="#">Residents Management</a>
            <div class="text-white">
                <i class="fas fa-user-check"></i> Welcome, <strong><?php echo $_SESSION['username'] ?? 'Guest'; ?></strong>! 
                You are logged in as an <strong><?php echo $_SESSION['role'] ?? 'No Role'; ?></strong>.
            </div>
            <a class="btn btn-outline-light" href="index.php">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card p-4">
            <div>
                <button class="btn btn-link" onclick="window.history.back()"><i class="bi bi-arrow-left" style="color: #6c757d;"></i></button>
            </div>
            <h2 class="text-center">Manage Residents</h2>
            <?php if (isset($notification)) echo $notification; ?>
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
                    <input type="text" class="form-control" name="contact" pattern="\d{1,11}" maxlength="11" required>
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
    </div>

    <div class="popup-notification alert alert-success" id="action-success-popup"><?php echo $popup_message ?? ''; ?></div>

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
                            <input type="text" class="form-control" name="contact" id="update-contact" pattern="\d{1,11}" maxlength="11" required>
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
