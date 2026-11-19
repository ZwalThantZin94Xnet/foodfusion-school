<?php
session_start();
include "db.php";

/* Only logged-in users can access My Wall */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ADD POST */
if (isset($_POST['submit_post'])) {

    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageName = "";

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $tmpName = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmpName, "uploads/" . $imageName);
    }

    $sql = "INSERT INTO community_posts 
            (user_id, title, content, image)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $title, $content, $imageName);
    $stmt->execute();

    header("Location: my_wall.php");
    exit();
}

/* GET ONLY MY POSTS */
$stmt = $conn->prepare("
    SELECT *
    FROM community_posts
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Community Cookbook - My Wall</title>
</head>
<body>

<h1>Community Cookbook - My Wall</h1>

<a href="community_cookbook.php">Community</a> |
<a href="my_wall.php">My Wall</a>

<p>
    Create, update, edit and delete your own recipes, cooking tips and culinary experiences.
</p>

<h2>Create New Post</h2>

<form method="POST" enctype="multipart/form-data">
    <!-- Encoding Type -->
    <input type="text" name="title" placeholder="Post Title" required>
    <br><br>

    <textarea name="content" placeholder="Share your recipe, cooking tip or culinary experience..." required></textarea>
    <br><br>

    <input type="file" name="image">
    <br><br>

    <button type="submit" name="submit_post">Submit Post</button>

</form>

<hr>

<h2>My Posts</h2>

<?php while ($row = $posts->fetch_assoc()): ?>

    <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">

        <h3><?php echo htmlspecialchars($row['title']); ?></h3>

        <?php if (!empty($row['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="180">
            <br><br>
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>

        <a href="edit_post.php?id=<?php echo $row['id']; ?>">Edit</a> |
        <a href="delete_post.php?id=<?php echo $row['id']; ?>"
           onclick="return confirm('Delete this post?')">Delete</a>

    </div>

<?php endwhile; ?>

</body>
</html>