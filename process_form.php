<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===============================================
// DATABASE CONFIG
// ===============================================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "landingpage_db";

// ===============================================
// PROCESS FORM
// ===============================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
  $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
  $package = filter_input(INPUT_POST, 'package', FILTER_SANITIZE_SPECIAL_CHARS); // ✅ NEW

  // DB connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // ===============================================
  // INSERT QUERY (UPDATED)
  // ===============================================
  $sql = "INSERT INTO callbacks (name, phone, email, message, package)
          VALUES (?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $name, $phone, $email, $message, $package);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: index.html?status=success");
    exit();
  } else {
    echo "Error: " . $stmt->error;
    $stmt->close();
    $conn->close();
  }

} else {
  header("Location: index.html");
  exit();
}
?>