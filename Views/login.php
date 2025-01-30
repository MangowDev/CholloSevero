<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../Css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="../Assets/Images/Logo_Images/LogoNoBg.png" alt="logo">
            <h1 class="form-title">Chollosevero</h1>
        </div>
        <form action="../Auth/loginUsuario.php" method="POST">
            <h4 class="login-title">Log in</h4>
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

            <button type="submit">Log in</button>
            <span>Don't have an account? <a href="./registro.php">Sign up here</a></span>
        </form>
    </div>

    <?php
    if (isset($_GET['mensaje'])) {
        $mensaje = $_GET["mensaje"];
        echo "<p style='font-weight:bold;'>" . $mensaje . "</p>";
    }
    ?>
    <script src="https://kit.fontawesome.com/8b39d50696.js" crossorigin="anonymous"></script>
</body>

</html>