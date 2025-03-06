<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $requestId = intval($_GET['id']);

    $sql = "UPDATE checkout_requests SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $requestId);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?message=Checkout approved");
        exit();
    } else {
        echo "❌ Error updating status.";
    }
}

$conn->close();
?>
