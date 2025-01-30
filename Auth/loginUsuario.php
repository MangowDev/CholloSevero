<?php 
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
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
        header("Location: ../Views/registro.php?message=$message");
        exit();
    }

    $stmt = $db->prepare("SELECT id, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $message = "Login successful. Welcome, $username!";
            header("Location: ../Views/chollos.php?message=$message");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User not found.";
    }

    $stmt->close();
    $db->close();

    header("Location: ../Views/registro.php?message=$message");
    exit();
} else {
    header("Location: ../Views/registro.php?message=$message");
    exit();
}
?>
