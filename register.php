<?php
include "db.php";

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (first_name, last_name, email, password)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $first_name, $last_name, $email, $password);

try {
    $stmt->execute();
    echo "Registration successful. <a href= 'index.php'>go back</a>";
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        echo "Email already exists.<a href= 'index.php'>try again</a>";
    } else {
        throw $e;
    }
};
?>

<!-- password_hash(...), PHP built-in function
password ကို hash (encrypted string) ပြောင်း-->