<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT 
            b.id, 
            b.name, 
            b.email, 
            b.phone, 
            r.room_number, 
            r.room_type, 
            b.booking_date, 
            b.price 
        FROM bookings b 
        INNER JOIN rooms r ON b.room_id = r.id";

$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <a href="admin_dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>

    
 
    <h2 class="text-center">Manage Bookings</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Room Number</th>
                <th>Room Type</th>
                <th>Booking Date</th>
                <th>Price ($)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['room_number'] ?></td>
                    <td><?= $row['room_type'] ?></td>
                    <td><?= $row['booking_date'] ?></td>
                    <td><?= $row['price'] ?></td>
                    
                    <td> 
                        <a href="delete_booking.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm "onclick=" return  confirm('Are you sure to delete this booking?');)">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
