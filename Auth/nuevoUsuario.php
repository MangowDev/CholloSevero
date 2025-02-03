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
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = $_POST["email"];

    $db = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    if (empty($username) || empty($_POST["password"]) || empty($email)) {
        $message = "All fields are required.";
        header("Location: /Views/registro.php?message=$message");
        exit();
    }

    $stmt = $db->prepare("INSERT INTO user (username, password, email, role) VALUES (?, ?, ?, 'user')");
    if ($stmt === false) {
        die("Prepare failed: " . $db->error);
    }

    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        $message = "The user has been successfully registered.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $db->close();

    header("Location: ../Views/login.php?message=$message");
    exit();

} else {
    header("Location: ../Views/login.php");
    exit();
}
