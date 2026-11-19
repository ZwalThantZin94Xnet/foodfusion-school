<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>FoodFusion</title>
    <style>
        nav {
            background: #eee;
            padding: 10px;
        }
        nav a {
            margin: 10px;
            text-decoration: none;
        }
        .popup {
            display: none;
            border: 1px solid #ccc;
            padding: 20px;
            width: 300px;
            background: #f9f9f9;
            position: absolute;
            top: 80px;
            left: 50%; /*box ကို center သို့ရွှေ့ */
            transform: translateX(-50%); /*150px (half) ဘယ်ဘက်ပြန်ရွှေ့*/
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="#">Home</a>
    <a href="#">About Us</a>
    <a href="#">Recipe Collection</a>
    <a href="#">Community Cookbook</a>
    <a href="#">Contact Us</a>
    <a href="#">Culinary Resources</a>
    <a href="#">Educational Resources</a>

    <?php if(isset($_SESSION['user'])): ?>
        <span style="float:right;">Welcome, <?php echo $_SESSION['user']; ?></span>
        <a href="logout.php">Logout</a>
    <?php else: ?> 
        <button onclick="showRegister()">Join Us</button>
        <button onclick="showLogin()">Login</button>
    <?php endif; ?>
</nav>

<!-- $_SESSION, built-in superglobal array in PHP
e.g, 
$_SESSION['name']
$_SESSION['email']

$_SESSION['user'] - login အောင်မြင်တဲ့ user ကို သိမ်းထားတဲ့ variable ပါ
isset() က ဒီ variable ရှိလား?  
else:  user login မဝင်ထားရင် ဒီအပိုင်းကို run မယ်
-->


<h1>Welcome to FoodFusion</h1>

<!-- REGISTER POPUP -->
<div id="registerForm" class="popup">
    <h2>Join Us (Register)</h2>

    <form action="register.php" method="POST">
        <input type="text" name="first_name" placeholder="First Name" required><br><br>
        <input type="text" name="last_name" placeholder="Last Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Register</button>
    </form>

    <br>
    <button onclick="closeAll()">Close</button>
</div>

<!-- LOGIN POPUP -->
<div id="loginForm" class="popup">
    <h2>Login</h2>

    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <br>
    <button onclick="closeAll()">Close</button>
</div>


<!-- JAVASCRIPT -->
<script>
function showRegister() {
    document.getElementById("registerForm").style.display = "block";
    document.getElementById("loginForm").style.display = "none";
}

function showLogin() {
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("registerForm").style.display = "none";
}

function closeAll() {
    document.getElementById("registerForm").style.display = "none";
    document.getElementById("loginForm").style.display = "none";
}
</script>

</body>
</html>