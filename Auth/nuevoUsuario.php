<?php 
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../');
$dotenv->load();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = $_POST["email"];

    $db = new mysqli(
        getenv('DB_HOST'),
        getenv('DB_USER'),
        getenv('DB_PASS'),
        getenv('DB_NAME')
    );

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    if (empty($username) || empty($password) || empty($email)) {
        $message = "All fields are required.";
        header("Location: ../Views/registro.php?message=$message");
        exit();
    }

    $stmt = $db->prepare("INSERT INTO user (username, password, email, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        $message = "The user has been successfully registered.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $db->close();

    header("Location: ../Views/registro.php?message=$message");
    exit();

} else {
    header("Location: ../Views/registro.php?message=$message");
    exit();
}