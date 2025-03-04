<?php
session_start();
include 'db_connect.php';

// Check if the user is an official
$is_official = isset($_SESSION['role']) && $_SESSION['role'] === 'official';

// CREATE an announcement (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_announcement'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO announcements (title, content, date_posted) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $title, $content);
    if ($stmt->execute()) {
        echo "Announcement posted!";
    } else {
        echo "Error: " . $stmt->error;
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
        echo "Announcement updated!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// DELETE an announcement (only for officials)
if ($is_official && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_announcement'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM announcements WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Announcement deleted!";
    } else {
        echo "Error: " . $stmt->error;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Post a New Announcement</h2>
        <?php if ($is_official) { ?>
        <form method="POST" class="mb-4">
            <input type="hidden" name="post_announcement" value="1">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" name="content" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post Announcement</button>
        </form>
        <?php } else { ?>
        <p>You do not have permission to post announcements.</p>
        <?php } ?>

        <h2>Announcements</h2>
        <table class="table table-bordered">
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
                        <button type="submit" class="btn btn-primary">Update Announcement</button>
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
            var title = button.getAttribute('data-title');
            var content = button.getAttribute('data-content');

            var modalIdInput = updateModal.querySelector('#update-id');
            var modalTitleInput = updateModal.querySelector('#update-title');
            var modalContentInput = updateModal.querySelector('#update-content');

            modalIdInput.value = id;
            modalTitleInput.value = title;
            modalContentInput.value = content;
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
