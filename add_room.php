<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $beds = $_POST['beds'];
    $size = $_POST['size'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];

    $check_room = "SELECT * FROM rooms WHERE room_number = ?";
    $stmt = $conn->prepare($check_room);
    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Room number already exists. Please use a different room number.');</script>";
    } else {
        $insert_room = "INSERT INTO rooms (room_number, room_type, status, beds, size, capacity, price) 
                        VALUES (?, ?, 'Available', ?, ?, ?, ?)";

        $stmt = $conn->prepare($insert_room);
        $stmt->bind_param("sssiii", $room_number, $room_type, $beds, $size, $capacity, $price);

        if ($stmt->execute()) {
            header("Location: success_roomAdd.php");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room | Hotel Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            transition: 0.3s ease-in-out;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<header class="bg-primary text-white text-center py-4">
    <h1>Hotel Management System</h1>
    <p>Admin Panel - Add New Room</p>
</header>

<div class="container">
    <div class="form-container">
        <h2 class="text-center">Add New Room</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="room_number" class="form-label">Room Number:</label>
                <input type="text" name="room_number" class="form-control" required>
            </div>

            <div class="mb-3">
            <form action="add_room.php" method="POST">
    <label for="room_type" class="form-label">Room Type:</label>
    <select name="room_type" id="room_type" class="form-control" required>
        <option value="">-- Select Room Type --</option>
        <option value="single">Single</option>
        <option value="double">Double</option>
        <option value="suite">Suite</option>
    </select>
            </div>

            <div class="mb-3">
                <label for="beds" class="form-label">Number of Beds:</label>
                <input type="text" name="beds" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="size" class="form-label">Room Size:</label>
                <input type="text" name="size" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity (people):</label>
                <input type="number" name="capacity" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price ($):</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-custom">Add Room</button>
            <button type="button" class="btn btn-outline-secondary w-100 mt-2 btn-custom" onclick="window.location.href='upload_images.php'">Add image to Room</button>
            <button type="button" class="btn btn-outline-secondary w-100 mt-2 btn-custom" onclick="window.location.href='admin_dashboard.php'">Back to Dashboard</button>
            <button type="button" class="btn btn-danger w-100 mt-2 btn-custom" onclick="window.location.href='logout.php'">Logout</button>
        </form>
    </div>
</div>

</body>
</html>
