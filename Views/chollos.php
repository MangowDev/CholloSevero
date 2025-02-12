<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require_once __DIR__ . '/../vendor/autoload.php';

$dotenvPath = '../';
$dotenv = Dotenv\Dotenv::createImmutable($dotenvPath, '.env.local');
$dotenv->load();

session_start();

$username = $_SESSION["username"];
$role = $_SESSION["role"];

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



$stmt = $db->prepare("SELECT * FROM deals");
if ($stmt === false) {
    die("Prepare failed: " . $db->error);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chollos</title>
    <link rel="stylesheet" href="../Css/chollos.css">
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
                <a href="misChollos.php">
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
        <h1>The best Deals!</h1>
        <div class="row deals-row justify-content-between">
            <?php

            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $title, $price, $previous_price, $rating, $description, $shop, $image, $userId);

                $counter = 0;
                while ($stmt->fetch()) {
                    if (isset($stmtUser)) {
                        $stmtUser->close();
                    }

                    $stmtUser = $db->prepare("SELECT username FROM user WHERE id = ?");
                    if ($stmtUser === false) {
                        die("Prepare failed: " . $db->error);
                    }

                    $stmtUser->bind_param("i", $userId);
                    $stmtUser->execute();
                    $stmtUser->store_result();

                    $userDeal = "Unknown";
                    if ($stmtUser->num_rows > 0) {
                        $stmtUser->bind_result($userDeal);
                        $stmtUser->fetch();
                    }

                    if ($counter % 2 == 0) {
                        if ($counter > 0) {
                            echo '</div>';
                        }
                        echo '<div class="row deals-row justify-content-between">';
                    }

            ?>
                    <div class="d-flex flex-column align-items-start justify-content-start text-left deal-col">
                        <div class="d-flex flex-row align-items-center justify-content-start text-left deal-first-row">
                            <div class="col-xl-3 col-lg-4 col-12 d-flex flex-column align-items-start justify-content-start text-left deal-img-col">
                            <div class="deal-img" style="background: url('<?php echo htmlspecialchars($image); ?>'); background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-12 d-flex flex-column align-items-start justify-content-start text-left deal-info-col">
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
                                <span><?php echo $description ?></span>
                            </div>
                        </div>
                        <div class="d-flex flex-row align-items-between justify-content-between text-left deal-second-row">
                            <div class="col-lg-3 d-flex flex-row align-items-start justify-content-start text-left shop-col">
                                <span>Shop: <span><?php echo $shop ?></span></span>
                            </div>

                            <?php
                            if ($username === $userDeal) {

                            ?>
                                <div class="col-lg-6 d-flex flex-row align-items-center justify-content-center text-center shop-col">
                                    <form action="./editar.php" method="POST">
                                        <input type="hidden" name="id" value=<?php echo $id ?>>
                                        <button type="submit" class="edit-button">Edit</button>
                                    </form>
                                    <form action="../Controllers/eliminarChollo.php" method="POST">
                                        <input type="hidden" name="id" value=<?php echo $id ?>>
                                        <button type="submit" class="delete-button">Delete</button>
                                    </form>
                                </div>

                            <?php
                            }
                            if ($role === "admin") {
                            ?>
                                <div class="col-lg-6 d-flex flex-row align-items-center justify-content-center text-center shop-col">
                                    <form action="./editar.php" method="POST">
                                        <input type="hidden" name="id" value=<?php echo $id ?>>
                                        <button type="submit" class="edit-button">Edit</button>
                                    </form>
                                    <form action="../Controllers/eliminarChollo.php" method="POST">
                                        <input type="hidden" name="id" value=<?php echo $id ?>>
                                        <button type="submit" class="delete-button">Delete</button>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-lg-3 d-flex flex-row align-items-end justify-content-end text-end shop-col">
                                <span>Usuario: <span><?php echo $userDeal ?></span></span>
                            </div>
                        </div>
                    </div>
            <?php
                    $counter++;
                }
                echo '</div>';
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
    $stmtUser->close();
    $stmt->close();
    $db->close();
    ?>
</body>

</html>