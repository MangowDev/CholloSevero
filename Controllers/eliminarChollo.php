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

    if (!empty($_POST['id'])) {

        $id = $_POST['id'];

        $stmt = $db->prepare("DELETE FROM deals WHERE id = ?");

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Deal deleted succesfully.";
        } else {
            $message = "Error deleting the deal.";
        }

        $stmt->close();
        $db->close();

        header("Location: ../Views/chollos.php?message=$message");
        exit();
    } else {
        $message = "ID not found.";
        header("Location: ../Views/chollos.php?message=$message");
        exit();
    }
} else {
    header("Location: ../Views/crear.php?message=$message");
    exit();
}
