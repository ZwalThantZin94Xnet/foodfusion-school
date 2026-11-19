<?php
session_start();
include "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admin only.");
}

/* ADD RESOURCE */
if (isset($_POST['add_resource'])) {

    $resource_category = $_POST['resource_category'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $fileName = time() . "_" . basename($_FILES['resource_file']['name']);
    $tmpName = $_FILES['resource_file']['tmp_name'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    move_uploaded_file($tmpName, "uploads/resources/" . $fileName);

    $sql = "INSERT INTO resources 
            (resource_category, title, description, file_name, file_type)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $resource_category, $title, $description, $fileName, $fileType);
    $stmt->execute();

    header("Location: admin_resources.php");
    exit();
}

$resources = $conn->query("SELECT * FROM resources ORDER BY id DESC");
?>

<h1>Admin - Manage Resources</h1>

<form method="POST" enctype="multipart/form-data">

    <select name="resource_category" required>
        <option value="">Select Resource Category</option>
        <option value="Culinary">Culinary Resources</option>
        <option value="Educational">Educational Resources</option>
    </select>
    <br><br>

    <input type="text" name="title" placeholder="Resource Title" required>
    <br><br>

    <textarea name="description" placeholder="Description" required></textarea>
    <br><br>

    <input type="file" name="resource_file" required>
    <br><br>

    <button type="submit" name="add_resource">Upload Resource</button>

</form>

<hr>

<h2>Uploaded Resources</h2>

<?php while ($row = $resources->fetch_assoc()): ?>

    <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">

        <h3><?php echo htmlspecialchars($row['title']); ?></h3>

        <p>
            <strong>Category:</strong>
            <?php echo htmlspecialchars($row['resource_category']); ?>
        </p>

        <p><?php echo htmlspecialchars($row['description']); ?></p>

        <p>
            <strong>File:</strong>
            <?php echo htmlspecialchars($row['file_name']); ?>
        </p>

        <a href="uploads/resources/<?php echo htmlspecialchars($row['file_name']); ?>" download>
            Download
        </a>

        |
        <a href="edit_resource.php?id=<?php echo $row['id']; ?>">
            Edit
        </a>

        |

        <a href="delete_resource.php?id=<?php echo $row['id']; ?>"
        onclick="return confirm('Delete this resource?')">
            Delete
        </a>

    </div>

<?php endwhile; ?>