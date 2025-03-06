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

$whereClauses = ["status = 'Available'"];
if (!empty($_GET['room_type'])) {
    $whereClauses[] = "room_type = '" . $conn->real_escape_string($_GET['room_type']) . "'";
}
if (!empty($_GET['min_price'])) {
    $whereClauses[] = "price >= " . (int)$_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $whereClauses[] = "price <= " . (int)$_GET['max_price'];
}

$sql = "SELECT * FROM rooms WHERE " . implode(" AND ", $whereClauses);
$result = $conn->query($sql);

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Maya| Luxury Stay</title>
    <style>
        .hero {
            background-image: url('hotel.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3); 
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        :root {
            --primary-color: #003366;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #002244;
            border-color: #002244;
        }
        body {
            font-family: 'Open Sans', sans-serif;
        }
        h1, h2 h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #ffc107 !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Hotel Maya</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#rooms">Rooms</a></li>
                <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item"><a class="btn btn-danger ms-2" href="log_out.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-light ms-2" href="login_.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light ms-2" href="register.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<section class="hero" data-aos="fade-in">
    <div class="hero-content text-center">
        <h1 class="hero-title">Experience Luxury Redefined</h1>
        <p class="hero-subtitle">Your Perfect Getaway Awaits</p>
        <a href="#rooms" class="btn btn-secondary">Explore Rooms</a>
    </div>
</section>

<section id="rooms" class="container mt-4">
    <h2 class="text-center">Our Available Rooms</h2>
    <div class="row g-4">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card">
                
                    <img src="uploads/<?= $row['image_path'] ?>" class="card-img-top" alt="<?= $row['room_type'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['room_type'] ?></h5>
                        <p>Room <?= $row['room_number'] ?></p>
                        <p><i class="fas fa-bed"></i> <?= $row['beds'] ?> Beds</p>
                        <p><i class="fas fa-expand"></i> <?= $row['size'] ?> sqft</p>
                        <p><i class="fas fa-users"></i> Guests: <?= $row['capacity'] ?></p>
                        <h6 class="text-danger">$<?= $row['price'] ?>/night</h6>
                        <a href="<?= $isLoggedIn ? 'book_room.php?id='.$row['id'] : 'login_.php' ?>" class="btn btn-dark link w-100">
                            <?= $isLoggedIn ? "Book Now" : "Login to Book" ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<footer class="footer bg-dark text-white text-center py-4 mt-4">
    <div class="container">
        <div class="row">
            <!-- Navigation Links -->
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white">Home</a></li>
                    <li><a href="#rooms" class="text-white">Rooms</a></li>
                    <li><a href="contact_us.php" class="text-white">Contact</a></li>
                    <li><a href="about_us.php" class="text-white">About Us</a></li>
                </ul>
            </div>
          <div class="col-md-4">
                <h5>Contact Us</h5>
                <p><i class="fas fa-phone"></i> +1 234 567 890</p>
                <p><i class="fas fa-envelope"></i> support@hotelmaya.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Robinson Gapan </p>
            </div>

  
            <div class="col-md-4">
                <h5>Connect With Us</h5>
                <a href="https://facebook.com" target="_blank" class="text-white me-2"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="https://twitter.com" target="_blank" class="text-white me-2"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="https://instagram.com" target="_blank" class="text-white me-2"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="https://linkedin.com" target="_blank" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                
                <div class="mt-3">
                    <a href="UsersLogin.php" class="btn btn-secondary">Admin Login</a>
                </div>
            </div>
        </div>

        <hr class="bg-light">
        <p class="mb-0">© 2025 Hotel Maya. All rights reserved.</p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>

<?php $conn->close(); ?>