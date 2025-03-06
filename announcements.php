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

// CREATE an announcement (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_announcement'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO announcements (title, content, date_posted) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $title, $content);
    if ($stmt->execute()) {
        $notification = '<div class="alert alert-success">Announcement posted!</div>';
        $popup_message = 'Announcement created!';
    } else {
        $notification = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// UPDATE an announcement (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_announcement'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE announcements SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    if ($stmt->execute()) {
        $notification = '<div class="alert alert-success">Announcement updated!</div>';
        $popup_message = 'Announcement updated!';
    } else {
        $notification = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// DELETE an announcement (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_announcement'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM announcements WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $notification = '<div class="alert alert-success">Announcement deleted!</div>';
        $popup_message = 'Announcement deleted!';
    } else {
        $notification = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// READ all announcements
$sql = "SELECT * FROM announcements ORDER BY date_posted DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements Management</title>
    <link rel="icon" type="image/png" href="assets/brgy_logo.png">
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
            <a class="navbar-brand" href="#">Announcements Management</a>
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
            <h2 class="text-center">Post a New Announcement</h2>
            <?php if ($is_official) { ?>
            <form method="POST" class="mt-3">
                <input type="hidden" name="post_announcement" value="1">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" name="content" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Post Announcement</button>
            </form>
            <?php } else { ?>
            <p>You do not have permission to post announcements.</p>
            <?php } ?>
        </div>

        <div class="card p-4 mt-4 card-table">
            <h2 class="text-center">Announcements</h2>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Date Posted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['content']; ?></td>
                            <td><?php echo $row['date_posted']; ?></td>
                            <td>
                                <?php if ($is_official) { ?>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="delete_announcement" value="1">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="<?php echo $row['id']; ?>" data-title="<?php echo $row['title']; ?>" data-content="<?php echo $row['content']; ?>">Update</button>
                                <?php } else { ?>
                                <p>No actions available.</p>
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
                    <h5 class="modal-title" id="updateModalLabel">Update Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="update_announcement" value="1">
                        <input type="hidden" name="id" id="update-id">
                        <div class="mb-3">
                            <label for="update-title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="update-title" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-content" class="form-label">Content</label>
                            <textarea class="form-control" name="content" id="update-content" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Announcement</button>
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
            var title = button.getAttribute('data-title');
            var content = button.getAttribute('data-content');

            var modalIdInput = updateModal.querySelector('#update-id');
            var modalTitleInput = updateModal.querySelector('#update-title');
            var modalContentInput = updateModal.querySelector('#update-content');

            modalIdInput.value = id;
            modalTitleInput.value = title;
            modalContentInput.value = content;
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
