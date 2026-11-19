<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT * FROM resources
    WHERE resource_category = 'Culinary'
    ORDER BY id DESC
");
$stmt->execute();
$resources = $stmt->get_result();
?>

<h1>Culinary Resources</h1>

<p>
    Download recipe cards, watch cooking tutorials, and learn cooking techniques and kitchen hacks.
</p>

<?php while ($row = $resources->fetch_assoc()): ?>

    <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">

        <h3><?php echo htmlspecialchars($row['title']); ?></h3>

        <p><?php echo htmlspecialchars($row['description']); ?></p>

        <?php if ($row['file_type'] == 'mp4'): ?>
            <video width="400" controls>
                <source src="uploads/resources/<?php echo htmlspecialchars($row['file_name']); ?>" type="video/mp4">
            </video>
            <br><br>
        <?php endif; ?>

        <a href="uploads/resources/<?php echo htmlspecialchars($row['file_name']); ?>" download>
            Download Resource
        </a>

    </div>

<?php endwhile; ?>