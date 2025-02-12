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

$username = $_SESSION["username"];
$role = $_SESSION["role"];
$userDeal = $_SESSION["id"];

$message = "";

$db = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}



$stmt = $db->prepare("SELECT * FROM deals where user_id = ?");
if ($stmt === false) {
    die("Prepare failed: " . $db->error);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis chollos</title>
    <link rel="stylesheet" href="../Css/misChollos.css">
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
        <?php
        if (!empty($username)) {

        ?>
            <div class="col-lg-3 col-6 d-flex flex-row align-items-center justify-content-end text-end user-div">
                <i class="fa-regular fa-user"></i>
                <h4><?php echo $username ?></h4>
            </div>
        <?php
        } else { ?>
            <div class="col-lg-3 col-6 d-flex flex-row align-items-center justify-content-end text-end user-div">
                <a class="login-register-a" href="./login.php">
                    <h4>Login/Register</h4>
                </a>
            </div>
        <?php
        }

        ?>
    </header>

    <?php
    if (!empty($username)) {

    ?>
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
    <?php
    }

    ?>


    <section class="container-fluid px-5 mt-5 main-deals-section">
        <h1>My deals</h1>
        <div class="row deals-row justify-content-between">
            <?php
            $stmt->bind_param("i", $userDeal);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $title, $price, $previous_price, $rating, $description, $shop, $image, $userId);

                while ($stmt->fetch()) {

            ?>
                    <div class="row deals-row justify-content-between">
                        <div class="d-flex flex-column align-items-start justify-content-start text-left deal-col">
                            <div class="d-flex flex-row align-items-center justify-content-start text-left deal-first-row">
                                <div class="col-xl-2 col-lg-3 col-12 d-flex flex-column align-items-start justify-content-start text-left deal-img-col">
                                    <div class="deal-img" style="background: url('<?php echo $image; ?>'); background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
                                </div>
                                <div class="col-xl-9 col-lg-6 col-12 d-flex flex-column align-items-start justify-content-start text-left deal-info-col">
                                    <div class="col-12 d-flex flex-row align-items-center justify-content-start text-left deal-title">
                                        <h4><?php echo $title ?></h4>
                                        <span><span><?php echo $previous_price ?>€</span><?php echo $price ?>€</span>
                                    </div>
                                    <div class="col-12 d-flex flex-row align-items-center justify-content-start text-left deal-rating">
                                        <?php
                                        $rating = (float) $rating;
                                        $fullStars = (int) floor($rating);

                                        for ($i = 0; $i < $fullStars; $i++) {
                                            echo "<i class='fa-solid fa-star'></i>";
                                        }

                                        if ($rating - $fullStars > 0) {
                                            echo "<i class='fa-solid fa-star-half'></i>";
                                        }
                                        ?>


                                    </div>
                                    <span class="shop-col">Shop: <span><?php echo $shop ?></span></span>
                                    <span><?php echo $description ?></span>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-12 d-flex flex-column align-items-start justify-content-start text-left deal-edit-col">
                                    <form action="./editar.php" method="POST">
                                        <input type="hidden" name="id" value=<?php echo $id ?>>
                                        <button type="submit" class="edit-vertical-button">Edit</button>
                                    </form>
                                    <form action="../Controllers/eliminarChollo.php" method="POST">
                                        <input type="hidden" name="id" value=<?php echo $id ?>>
                                        <button type="submit" class="delete-vertical-button">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "No results found.";
            }
            ?>
        </div>

    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchBar = document.getElementById("searchbar");
            const deals = document.querySelectorAll(".deal-col");

            function searchDeals() {
                let query = searchBar.value.toLowerCase().trim();

                deals.forEach(deal => {
                    let title = deal.querySelector(".deal-title h4").textContent.toLowerCase();

                    if (!title.includes(query)) {
                        deal.classList.remove("deal-col");
                        deal.classList.add("hidden-col");
                    } else {
                        deal.classList.remove("hidden-col");
                        deal.classList.add("deal-col");
                    }
                });
            }

            searchBar.addEventListener("keydown", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    searchDeals();
                }
            });
        });
    </script>

    <script src="https://kit.fontawesome.com/8b39d50696.js" crossorigin="anonymous"></script>

    <?php
    $stmt->close();
    $db->close();
    ?>
</body>

</html>