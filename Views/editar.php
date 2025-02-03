<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenvPath = '../';
$dotenv = Dotenv\Dotenv::createImmutable($dotenvPath, '.env.local');
$dotenv->load();

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

        $stmt = $db->prepare("SELECT * FROM deals WHERE id = $id");

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $title, $price, $previous_price, $rating, $description, $shop, $image);
            $stmt->fetch();
        } else {
            $message = "Deal not found with the requested ID.";
        }

        $stmt->close();
        $db->close();
    }
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
            <input type="text" id="searchbar" name="searchbar" placeholder="Search chollos here...">
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
            <i class="fa-solid fa-pencil"></i>
            <a href="crear.php">Create</a>
        </div>
        <div>
            <i class="fa-solid fa-sack-dollar"></i>
            <a href="#">My deals</a>
        </div>
    </nav>
    <section>
        <form action="../Controllers/editarChollo.php" method="POST">
            <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id) ?>" required>

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>" required>

            <label for="previous_price">Previous price:</label>
            <input type="number" id="previous_price" name="previous_price" step="0.01" value="<?= htmlspecialchars($previous_price) ?>" required>

            <label for="rating">Rating:</label>
            <input type="number" id="rating" name="rating" max="5" step="0.01" value="<?= htmlspecialchars($rating) ?>" required>

            <label for="shop">Shop:</label>
            <input type="text" id="shop" name="shop" maxlength="50" value="<?= htmlspecialchars($shop) ?>" required>

            <label for="image">Image:</label>
            <input type="text" id="image" name="image" value="<?= htmlspecialchars($image) ?>">

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" maxlength="200"><?= htmlspecialchars($description) ?></textarea>

            <button type="submit">Update</button>
        </form>
    </section>
    <script src="https://kit.fontawesome.com/8b39d50696.js" crossorigin="anonymous"></script>
</body>

</html>