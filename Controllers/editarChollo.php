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
        !empty($_POST["id"]) &&
        !empty($_POST['title']) &&
        !empty($_POST['price']) &&
        !empty($_POST['rating']) &&
        !empty($_POST['shop']) &&
        isset($_POST['previous_price']) &&
        isset($_POST['description'])
    ) {

        $id = $_POST["id"];
        $title = $_POST['title'];
        $price = $_POST['price'];
        $previous_price = $_POST['previous_price'];
        $rating = $_POST['rating'];
        $shop = $_POST['shop'];
        $image = $_POST['image'];
        $description = $_POST['description'];

        $stmt = $db->prepare("UPDATE deals SET title = ?, price = ?, previous_price = ?, rating = ?, description = ?, shop = ?, image = ? WHERE id = ?");

        $stmt->bind_param("sdddsssi", $title, $price, $previous_price, $rating, $description, $shop, $image, $id);

        if ($stmt->execute()) {
            $message = "Deal updated succesfully.";
        } else {
            $message = "Error updating the deal.";
        }

        $stmt->close();
        $db->close();

        header("Location: ../Views/chollos.php?message=$message");
        exit();
    } else {
        $message = "All fields are required.";
        header("Location: ../Views/chollos.php?message=$message");
        exit();
    }
} else {
    header("Location: ../Views/chollos.php?message=$message");
    exit();
}
