<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

// Email နဲ့ user ရှာမယ်
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
//$result က database result object ဖြစ်, အဲဒီထဲက row ကို $user = mysqli_fetch_assoc($result)ယူ

if (mysqli_num_rows($result) == 1) {
    //mysqli_fetch_assoc($result) ကို မခေါ်ခင် row ရှိ/မရှိ စစ်သင့်

    $user = mysqli_fetch_assoc($result);
    /*
    $user = [
    "id" => 1,
    "first_name" => "Thandar",
    "email" => "thandar@gmail.com",
    "role" => "admin"];
    */

    // Account lock ဖြစ်/မဖြစ် စစ်မယ်
    if ($user['failed_attempts'] >= 3) {

        $last_failed = strtotime($user['last_failed_login']);
        //login မမှားသေးတဲ့ user အတွက် value က NULL ဖြစ်နိုင်
        //NULL ကို strtotime()
        $current_time = time();

        $seconds_passed = $current_time - $last_failed;

        if ($seconds_passed < 180) {

            $remaining = 180 - $seconds_passed;

            echo "Your account is locked. Try again after "
                 . $remaining . " seconds.<br>";

            echo "<a href='index.php'>Back to Login</a>";
            exit();

        } else {

            // 3 minutes ကျော်ရင် attempts reset
            $reset_sql = "
                UPDATE users
                SET failed_attempts = 0,
                    last_failed_login = NULL
                WHERE email = '$email'
            ";

            mysqli_query($conn, $reset_sql);

            $user['failed_attempts'] = 0;
        }
    }

    // Password စစ်မယ်
    if (password_verify($password, $user['password'])) {

        // Login အောင်မြင်ရင် failed attempts reset
        $reset_sql = "
            UPDATE users
            SET failed_attempts = 0,
                last_failed_login = NULL
            WHERE email = '$email'
        ";

        mysqli_query($conn, $reset_sql);

        // Session data သိမ်းမယ်
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['first_name'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit();

    } else {

        // Password မှားရင် failed attempts +1
        $new_attempts = $user['failed_attempts'] + 1;

        $update_sql = "
            UPDATE users
            SET failed_attempts = $new_attempts,
                last_failed_login = NOW()
            WHERE email = '$email'
        ";

        mysqli_query($conn, $update_sql);

        if ($new_attempts >= 3) {

            echo "Wrong password.<br>";
            echo "Your account is locked for 3 minutes.<br>";
            echo "<a href='index.php'>Back to Login</a>";

        } else {

            echo "Wrong password.<br>";
            echo "Attempt $new_attempts of 3.<br>";
            echo "<a href='index.php'>Try again</a>";
        }
    }

} else {

    echo "Email not found.<br>";
    echo "<a href='index.php'>Try again</a>";
}
?>

======================================================

<?php
session_start();
//Session က server-side memory, user data ကို page တစ်ခုကနေ တစ်ခုကို ဆက်လက်သိမ်းထား
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];


$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    // 3 times failed ဖြစ်ထားရင် 3 minutes lock စစ်မယ်
    if ($user['failed_attempts'] >= 3) {

        $lock_sql = "SELECT TIMESTAMPDIFF(SECOND, last_failed_login, NOW()) AS seconds_passed
                     FROM users
                     WHERE email = ?";
        //TIMESTAMPDIFF(unit, start, end)
        //seconds_passed=NOW()−last_failed_login
        $lock_stmt = $conn->prepare($lock_sql);
        $lock_stmt->bind_param("s", $email);
        $lock_stmt->execute();

        $lock_result = $lock_stmt->get_result();
        $lock_data = $lock_result->fetch_assoc();

        if ($lock_data['seconds_passed'] < 180) {
            echo "Your account is locked. Please try again after 3 minutes. 
            <a href='index.php'>Back to Login</a>";
            exit();
        } else {
            // 3 minutes ကျော်ရင် reset
            $reset_sql = "UPDATE users 
                          SET failed_attempts = 0, last_failed_login = NULL 
                          WHERE email = ?";
            $reset_stmt = $conn->prepare($reset_sql);
            $reset_stmt->bind_param("s", $email);
            $reset_stmt->execute();

            $user['failed_attempts'] = 0;
        }
    }

    // Password မှန်/မမှန် စစ်မယ်
    if (password_verify($password, $user['password'])) {

        // Login success ဖြစ်ရင် reset
        $reset_sql = "UPDATE users 
                      SET failed_attempts = 0, last_failed_login = NULL 
                      WHERE email = ?";
        $reset_stmt = $conn->prepare($reset_sql);
        $reset_stmt->bind_param("s", $email);
        $reset_stmt->execute();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['first_name'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();

    } else {

        // Password မှားရင် +1
        $new_attempts = $user['failed_attempts'] + 1;

        $update_sql = "UPDATE users 
                       SET failed_attempts = ?, last_failed_login = NOW() 
                       WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $new_attempts, $email);
        $update_stmt->execute();

        if ($new_attempts >= 3) {
            echo "Wrong password. Your account is locked for 3 minutes. 
            <a href='index.php'>Back to Login</a>";
        } else {
            echo "Wrong password. Attempt $new_attempts of 3. 
            <a href='index.php'>Try again</a>";
        }
    }

} else {
    echo "Email not found. <a href='index.php'>Try again</a>";
}
?>
