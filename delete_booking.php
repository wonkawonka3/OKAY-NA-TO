<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");

$stmt->bind_param("i", $id);

if($stmt->execute()) {
    echo"<script>alert('Booking deleted successfully!'); window.location.href='manage_bookings.php';</script>";
}
}else {
    echo"<script>alert('Error deleting booking!'); window.location.href='manage_bookings.php';</script>";
}

$stmt->close();

$conn->close();
?>