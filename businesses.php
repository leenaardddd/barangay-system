<?php
session_start();
include 'db_connect.php';

// CREATE a business registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_business'])) {
    $business_name = $_POST['business_name'];
    $owner_name = $_POST['owner_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO businesses (business_name, owner_name, address, contact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $business_name, $owner_name, $address, $contact);
    if ($stmt->execute()) {
        echo "Business registered!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// UPDATE a business registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_business'])) {
    $id = $_POST['id'];
    $business_name = $_POST['business_name'];
    $owner_name = $_POST['owner_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("UPDATE businesses SET business_name=?, owner_name=?, address=?, contact=? WHERE id=?");
    $stmt->bind_param("ssssi", $business_name, $owner_name, $address, $contact, $id);
    if ($stmt->execute()) {
        echo "Business updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// DELETE a business registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_business'])) {
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
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="delete_business" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="update_business" value="1">
                                <input type="text" name="business_name" value="<?php echo $row['business_name']; ?>" required>
                                <input type="text" name="owner_name" value="<?php echo $row['owner_name']; ?>" required>
                                <input type="text" name="address" value="<?php echo $row['address']; ?>" required>
                                <input type="text" name="contact" value="<?php echo $row['contact']; ?>" required>
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