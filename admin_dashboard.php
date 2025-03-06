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


$totalBookingsQuery = "SELECT COUNT(*) AS total FROM bookings"; 
$totalRevenueQuery = "SELECT COALESCE(SUM(price), 0) AS revenue FROM rooms WHERE status = 'Booked'"; 
$occupiedRoomsQuery = "SELECT COUNT(*) AS occupied FROM rooms WHERE status = 'Booked'"; 
$totalRoomsQuery = "SELECT COUNT(*) AS total FROM rooms";


$result = $conn->query($totalBookingsQuery);
if (!$result) die("Bookings query error: " . $conn->error);
$totalBookings = $result->fetch_assoc()['total'];

$result = $conn->query($totalRevenueQuery);
if (!$result) die("Revenue query error: " . $conn->error);
$totalRevenue = $result->fetch_assoc()['revenue'] ?? 0; 

$result = $conn->query($occupiedRoomsQuery);
if (!$result) die("Occupied rooms query error: " . $conn->error);
$occupiedRooms = $result->fetch_assoc()['occupied'];

$result = $conn->query($totalRoomsQuery);
if (!$result) die("Total rooms query error: " . $conn->error);
$totalRooms = $result->fetch_assoc()['total'];

$occupancyRate = ($totalRooms > 0) ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;


$monthlyRevenueQuery = "SELECT MONTH(booking_date) AS month, SUM(price) AS revenue FROM bookings GROUP BY MONTH(booking_date)";
$monthlyRevenueResult = $conn->query($monthlyRevenueQuery);

$monthlyRevenueData = [];
while ($row = $monthlyRevenueResult->fetch_assoc()) {
    $monthlyRevenueData[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Hotel Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card { margin-bottom: 20px; }
        .chart-container { width: 100%; height: 400px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Dashboard</span>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center p-3">
                    <h4>Total Bookings</h4>
                    <h2><?= $totalBookings ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center p-3">
                    <h4>Total Revenue</h4>
                    <h2>$<?= number_format($totalRevenue, 2) ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark text-center p-3">
                    <h4>Occupancy Rate</h4>
                    <h2><?= $occupancyRate ?>%</h2>
                </div>
            </div>
        </div>

      
        <div class="row">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="text-center">Room Availability</h5>
                    <canvas id="roomChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="text-center">Monthly Revenue</h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

    
        <div class="row mt-4">
            <div class="col-md-4">
                <a href="add_room.php" class="btn btn-primary w-100">Manage Rooms</a>
            </div>
            <div class="col-md-4">
                <a href="manage_bookings.php" class="btn btn-success w-100">Manage Bookings</a>
            </div>
            <div class="col-md-4">
                <a href="manage_users.php" class="btn btn-warning w-100">Manage Users</a>
            </div>
        </div>
    </div>

    <script>
        
        const roomChart = new Chart(document.getElementById('roomChart'), {
            type: 'pie',
            data: {
                labels: ['Booked Rooms', 'Available Rooms'],
                datasets: [{
                    data: [<?= $occupiedRooms ?>, <?= $totalRooms - $occupiedRooms ?>],
                    backgroundColor: ['#ff6384', '#36a2eb']
                }]
            }
        });

  
        const revenueData = <?= json_encode(array_column($monthlyRevenueData, 'revenue')) ?>;
        const revenueLabels = <?= json_encode(array_map(fn($m) => date("F", mktime(0, 0, 0, $m['month'], 1)), $monthlyRevenueData)) ?>;

        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: revenueData,
                    borderColor: '#4bc0c0',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }]
            }
        });
    </script>
</body>
</html>