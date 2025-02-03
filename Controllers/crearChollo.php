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
    $db = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    if (
        !empty($_POST['title']) &&
        !empty($_POST['price']) &&
        !empty($_POST['rating']) &&
        !empty($_POST['shop']) &&
        isset($_POST['previous_price']) &&
        isset($_POST['description'])
    ) {

        $title = $_POST['title'];
        $price = $_POST['price'];
        $previous_price = $_POST['previous_price'];
        $rating = $_POST['rating'];
        $shop = $_POST['shop'];
        $image = $_POST['image'];
        $description = $_POST['description'];

        $stmt = $db->prepare("INSERT INTO deals (title, price, previous_price, rating, description, shop, image) VALUES (?,?,?,?,?,?,?)");

        $stmt->bind_param("sdddsss", $title, $price, $previous_price, $rating, $description, $shop, $image);

        if ($stmt->execute()) {
            $message = "Deal created succesfully.";
        } else {
            $message = "Error creating the deal.";
        }

        $stmt->close();
        $db->close();

        header("Location: ../Views/crear.php?message=$message");
        exit();
    } else {
        $message = "All fields are required.";
        header("Location: ../Views/crear.php?message=$message");
        exit();
    }
} else {
    header("Location: ../Views/crear.php?message=$message");
    exit();
}
