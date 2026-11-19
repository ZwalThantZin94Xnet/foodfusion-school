<?php
session_start();
include "db.php";

/* Only logged-in users can view, like, comment */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Like/Comment လုပ်တဲ့အခါ ဒီ User ID ကို သုံး
/* LIKE POST */
if (isset($_GET['like'])) {

    $post_id = $_GET['like'];

    $sql = "INSERT IGNORE INTO community_likes 
            (post_id, user_id)
            VALUES (?, ?)";
    //INSERT IGNORE သုံးထားတာက User တစ်ယောက်က Post တစ်ခုကို Like ၂ ခါ မလုပ်နိုင်အောင်။
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();

    header("Location: community_cookbook.php");
    exit();
}

/* ADD COMMENT */
if (isset($_POST['add_comment'])) {

    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO community_comments 
            (post_id, user_id, comment)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    $stmt->execute();

    header("Location: community_cookbook.php");
    exit();
}

/* GET ALL POSTS */
$posts = $conn->query("
    SELECT community_posts.*, users.first_name
    FROM community_posts
    JOIN users ON community_posts.user_id = users.id
    ORDER BY community_posts.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Community Cookbook - Community</title>
</head>
<body>

<h1>Community Cookbook</h1>

<a href="community_cookbook.php">Community</a> |
<a href="my_wall.php">My Wall</a>

<p>
    View recipes, cooking tips and culinary experiences shared by FoodFusion members.
    Members can like and comment on community posts.
</p>

<hr>

<h2>Community Posts</h2>

<?php while ($row = $posts->fetch_assoc()): ?>

    <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">

        <h3><?php echo htmlspecialchars($row['title']); ?></h3>

        <p>
            <strong>Posted by:</strong>
            <?php echo htmlspecialchars($row['first_name']); ?>
            <!-- Database ထဲ ထည့်ထား htmlspecialchars() Security အတွက် သုံးတာပါ။ 
            e.g, <script>alert('Hack')</script>
            JavaScript အဖြစ် Run

            &lt;script&gt;alert('Hack')&lt;/script&gt;
            စာသားအနေနဲ့ပဲ ပြ -->
            
        </p>
        
        <?php if (!empty($row['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="180">
            <br><br>
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <!-- nl2br(), New Line (\n) ကို<br>ပြောင်းပေး 
        e.g, Database
                Line 1
                Line 2
                Line 3
            Output (nl2br() မသုံးရင်)
                Line 1 Line 2 Line 3
            Database ထဲရှိ Line Break များကို Browserတွင် မူလအတိုင်း Line ခွဲပြသနိုင်သည်။
        -->

        <?php
        $post_id = $row['id'];

        $likeStmt = $conn->prepare("
            SELECT COUNT(*) AS total_likes
            FROM community_likes
            WHERE post_id = ?
        ");
        $likeStmt->bind_param("i", $post_id);
        $likeStmt->execute();
        $likeData = $likeStmt->get_result()->fetch_assoc();

        $commentStmt = $conn->prepare("
            SELECT community_comments.*, users.first_name
            FROM community_comments
            JOIN users ON community_comments.user_id = users.id
            WHERE community_comments.post_id = ?
            ORDER BY community_comments.id DESC
        ");
        $commentStmt->bind_param("i", $post_id);
        $commentStmt->execute();
        $commentResult = $commentStmt->get_result();
        ?>

        <a href="community_cookbook.php?like=<?php echo $post_id; ?>">Like</a>
        <span><?php echo $likeData['total_likes']; ?> likes</span>

        <h4>Comments</h4>

        <?php while ($comment = $commentResult->fetch_assoc()): ?>
            <p>
                <strong><?php echo htmlspecialchars($comment['first_name']); ?>:</strong>
                <?php echo htmlspecialchars($comment['comment']); ?>
            </p>
        <?php endwhile; ?>

        <form method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <input type="text" name="comment" placeholder="Write a comment..." required>
            <button type="submit" name="add_comment">Comment</button>
        </form>

    </div>

<?php endwhile; ?>

</body>
</html>