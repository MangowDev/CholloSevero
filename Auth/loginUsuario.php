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
    $password = $_POST["password"];

    $db = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    if (empty($username) || empty($password)) {
        $message = "All fields are required.";
        header("Location: ../Views/registro.php?message=" . urlencode($message));
        exit();
    }

    $stmt = $db->prepare("SELECT id, username, password FROM user WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $db->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $id;
            header("Location: ../Views/chollos.php?");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User not found.";
    }

    $stmt->close();
    $db->close();

    header("Location: ../Views/login.php?message=" . urlencode($message));
    exit();
} else {
    header("Location: ../Views/login.php?message=" . urlencode($message));
    exit();
}
?>
