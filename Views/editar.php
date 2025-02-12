<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require_once __DIR__ . '/../vendor/autoload.php';

$dotenvPath = '../';
$dotenv = Dotenv\Dotenv::createImmutable($dotenvPath, '.env.local');
$dotenv->load();

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: chollos.php");
    exit;
}
$username = $_SESSION["username"];

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id"])) {
        $db = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME']
        );

        $id = $_POST["id"];
        $title = "";
        $price = "";
        $previous_price = "";
        $rating = "";
        $description = "";
        $shop = "";
        $image = "";
        $useId = "";

        $stmt = $db->prepare("SELECT * FROM deals WHERE id = $id");

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $title, $price, $previous_price, $rating, $description, $shop, $image, $userId);
            $stmt->fetch();
        } else {
            $message = "Deal not found with the requested ID.";
        }

        $stmt->close();
        $db->close();
    }
} else {
    header("Location: ./chollos.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar un chollo</title>
    <link rel="stylesheet" href="../Css/crear.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header class="container-fluid d-flex flex-row align-items-center">
        <div class="col-lg-2 col-6 d-flex flex-row align-items-center justify-content-left text-left logo-div">
            <a href="chollos.php">
                <img src="../Assets/Images/Logo_Images/LogoNoBg.png" alt="logo">
            </a>
            <a href="chollos.php">
                <h1 class="logo-h1">Chollosevero</h1>
            </a>
        </div>

        <div class="col-lg-2 col-6 d-flex flex-row align-items-center justify-content-left text-left filters-div">
            <i class="fa-solid fa-bars"></i>
            <select name="filters" id="lang">
                <option value="default-option">Select filters here:</option>
                <option value="javascript">JavaScript</option>
                <option value="php">PHP</option>
                <option value="java">Java</option>
                <option value="python">Python</option>
                <option value="C#">C#</option>
                <option value="C++">C++</option>
            </select>
        </div>
        <div class="col-lg-4 col-6 d-flex flex-row align-items-center justify-content-left text-left search-bar-div">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchbar" name="searchbar" placeholder="Search deals here...">
        </div>
        <!--         <div class="col-lg-3 col-6 d-flex flex-row align-items-center justify-content-end text-end user-div">
            <i class="fa-regular fa-user"></i>
            <h4>Username</h4>
        </div> -->
        <div class="col-lg-3 col-6 d-flex flex-row align-items-center justify-content-end text-end user-div">
            <i class="fa-regular fa-user"></i>
            <h4>Login/Register</h4>
        </div>
    </header>
    <nav>
        <div>
            <a href="crear.php">
                <i class="fa-solid fa-pencil"></i>
                <span>
                    Create
                </span>
            </a>
        </div>
        <div>
            <a href="#">
                <i class="fa-solid fa-sack-dollar"></i>
                <span>
                    My Deals
                </span>
            </a>
        </div>
        <div>
            <a href="../Controllers/disconnect.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>
                    Logout
                </span>
            </a>
        </div>
    </nav>
    <section class="container-fluid p-4">
        <form class="container-fluid px-4" action="../Controllers/editarChollo.php" method="POST">
            <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id) ?>" required>

            <div class="row form-row">
                <h4>Edit Deal</h4>
            </div>
            <div class="row form-row">
                <div class="col-lg-4 col-6 d-flex flex-column align-items-start justify-content-start text-left form-col">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" maxlength="30" required>
                </div>
                <div class="col-lg-4 col-6 d-flex flex-column align-items-start justify-content-start text-left form-col">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>" required>
                </div>
                <div class="col-lg-4 col-6 d-flex flex-column align-items-start justify-content-start text-left form-col">
                    <label for="previous_price">Previous price:</label>
                    <input type="number" id="previous_price" name="previous_price" step="0.01" value="<?= htmlspecialchars($previous_price) ?>" required>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-lg-4 col-6 d-flex flex-column align-items-start justify-content-start text-left form-col">
                    <label for="rating">Rating:</label>
                    <input type="number" id="rating" name="rating" max="5" min="0.5" step="0.01" value="<?= htmlspecialchars($rating) ?>" required>
                </div>
                <div class="col-lg-4 col-6 d-flex flex-column align-items-start justify-content-start text-left form-col">
                    <label for="shop">Shop:</label>
                    <input type="text" id="shop" name="shop" maxlength="50" value="<?= htmlspecialchars($shop) ?>" required>
                </div>
                <div class="col-lg-4 col-6 d-flex flex-column align-items-start justify-content-start text-left form-col">
                    <label for="image">Image:</label>
                    <input type="text" id="image" name="image" maxlength="300" value="<?= htmlspecialchars($image) ?>">
                </div>
            </div>
            <div class="row form-row">
                <div class="col-12 d-flex flex-column align-items-start justify-content-start text-left form-col">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" maxlength="200"><?= htmlspecialchars($description) ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex flex-row align-items-end justify-content-end text-left">
                    <button type="submit">Update</button>
                </div>
            </div>
        </form>
    </section>

    <script src="https://kit.fontawesome.com/8b39d50696.js" crossorigin="anonymous"></script>
</body>

</html>