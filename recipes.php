<?php
session_start();
include "db.php";

$search = $_GET['search'] ?? "";

$sql = "SELECT * FROM recipes
        WHERE title LIKE ?
        OR cuisine_type LIKE ?
        OR dietary_preference LIKE ?
        OR difficulty LIKE ?
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$keyword = "%$search%";
$stmt->bind_param("ssss", $keyword, $keyword, $keyword, $keyword);
$stmt->execute();

$result = $stmt->get_result();
?>

<h1>Recipe Collection</h1>

<form method="GET">
    <input type="text" name="search" placeholder="Search recipe, cuisine, diet, difficulty" value="<?php echo $search; ?>">
    <button type="submit">Search</button>
</form>

<hr>

<?php while($row = $result->fetch_assoc()): ?>

    <h2><?php echo $row['title']; ?></h2>

    <img src="uploads/<?php echo $row['image']; ?>" width="250"><br><br>

    <p><b>Cuisine Type:</b> <?php echo $row['cuisine_type']; ?></p>
    <p><b>Dietary Preference:</b> <?php echo $row['dietary_preference']; ?></p>
    <p><b>Difficulty:</b> <?php echo $row['difficulty']; ?></p>
    <p><?php echo $row['description']; ?></p>

    <hr>

<?php endwhile; ?>