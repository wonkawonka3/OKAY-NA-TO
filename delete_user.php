<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    
    $deleteSql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        header("Location: manage_users.php?success=1");
        exit();
    } else {
        echo "<p>Error deleting user: " . $stmt->error . "</p>";
    }
}

$conn->close();
?>
