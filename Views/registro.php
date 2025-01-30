<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../Css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="../Assets/Images/Logo_Images/LogoNoBg.png" alt="logo">
            <h1 class="form-title">Chollosevero</h1>
        </div>
        <form action="../Auth/nuevoUsuario.php" method="POST">
            <h4 class="login-title">Register</h4>
            <div class="label-div">
                <i class="fa-solid fa-user"></i>
                <label for="username">Username:</label>
            </div>
            <input type="text" id="username" name="username" required>
            <div class="label-div">
                <i class="fa-solid fa-lock"></i>
                <label for="password">Password:</label>
            </div>
            <input type="password" id="password" name="password" required>
            <div class="label-div">
                <i class="fa-solid fa-envelope"></i>
                <label for="email">Email:</label>
            </div>
            <input type="email" id="email" name="email" required>

            <button type="submit">Register</button>
            <span>Already have an account? <a href="./login.php">Log in here</a></span>
            <?php
            if (isset($_GET['message'])) {
                $message = $_GET["message"];
                echo "<p class='notify-message'>" . $message . "</p>";
            }
            ?>
        </form>

    </div>

    <script src="https://kit.fontawesome.com/8b39d50696.js" crossorigin="anonymous"></script>
</body>

</html>