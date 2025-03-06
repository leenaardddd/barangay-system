<?php
session_start();
include 'db_connect.php';

// Check if the user is an official
$is_official = isset($_SESSION['role']) && $_SESSION['role'] === 'official';

// Prevent unauthorized access
if (!$is_official) {
    // echo '<div class="alert alert-danger">You are not authorized to perform this action.</div>';
}

if ($conn->connect_error) {
    die('<div class="alert alert-danger">Database Connection Failed: ' . $conn->connect_error . '</div>');
}

// CREATE a business registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_business'])) {
    $business_name = $_POST['business_name'];
    $owner_name = $_POST['owner_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    // Validate contact number
    if (!preg_match('/^\d{11}$/', $contact)) {
        $notification = '<div class="text-white">Error: Contact number must be 11 digits.</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO businesses (business_name, owner_name, address, contact, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssss", $business_name, $owner_name, $address, $contact);
        if ($stmt->execute()) {
            $notification = '<div class="alert alert-success">Business registered and pending approval!</div>';
        } else {
            $notification = '<div class="text-white">Error: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// UPDATE a business registration (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_business'])) {
    $id = $_POST['id'];
    $business_name = $_POST['business_name'];
    $owner_name = $_POST['owner_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $status = $_POST['status'];

    // Validate contact number
    if (!preg_match('/^\d{11}$/', $contact)) {
        echo '<div class="alert alert-danger">Error: Contact number must be 11 digits.</div>';
    } else {
        $stmt = $conn->prepare("UPDATE businesses SET business_name=?, owner_name=?, address=?, contact=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $business_name, $owner_name, $address, $contact, $status, $id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success" id="update-success-popup">Business updated!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// DELETE a business registration (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_business'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM businesses WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Business deleted!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// READ all businesses
$sql = "SELECT * FROM businesses ORDER BY business_name ASC";
$result = $conn->query($sql);
if (!$result) {
    die('<div class="alert alert-danger">Error fetching businesses: ' . $conn->error . '</div>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Management</title>
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
            <a class="navbar-brand" href="#">Business Management</a>
            <div class="text-white">
                <i class="fas fa-user-check"></i> Welcome, <strong><?php echo $_SESSION['username'] ?? 'Guest'; ?></strong>! 
                You are logged in as an <strong><?php echo $_SESSION['role'] ?? 'No Role'; ?></strong>.
            </div>
            <a class="btn btn-outline-light" href="index.php">Logout</a>
            <?php if (isset($notification)) echo $notification; ?>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card p-4">
            <div>
                <button class="btn btn-link" onclick="window.history.back()"><i class="bi bi-arrow-left" style="color: #6c757d;"></i></button>
            </div>
            <h2 class="text-center">Register a New Business</h2>
            <form method="POST" class="mt-3">
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
                    <input type="text" class="form-control" name="contact" required maxlength="11" pattern="\d{11}">
                </div>
                <button type="submit" class="btn btn-primary w-100">Register Business</button>
            </form>
        </div>

        <div class="card p-4 mt-4 card-table">
            <h2 class="text-center">Registered Businesses</h2>
            <table class="table table-bordered text-center">
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
                            <td>
                                <span class="badge 
                                    <?php echo ($row['status'] == 'Pending') ? 'bg-warning' : 'bg-success'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
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
                            <input type="text" class="form-control" name="contact" id="update-contact" required maxlength="11" pattern="\d{11}">
                        </div>
                        <div class="mb-3">
                            <label for="update-status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="update-status" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Business</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="popup-notification alert alert-success" id="update-success-popup">Business updated!</div> -->

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

        // Show popup notification
        var updateSuccessPopup = document.getElementById('update-success-popup');
        if (updateSuccessPopup) {
            updateSuccessPopup.style.display = 'block';
            setTimeout(function() {
                updateSuccessPopup.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>