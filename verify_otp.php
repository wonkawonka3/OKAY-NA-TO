<?php
session_start();

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';


if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    $sql = "SELECT reset_token, reset_expiry FROM users WHERE username='$email' OR email='$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row) {
     
        if ($row['reset_token'] == $otp && time() < $row['reset_expiry']) {
      
            if ($new_password === $confirm_password) {
           
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

             
                $update_sql = "UPDATE users
                               SET password='$hashed_password',
                                   reset_token=NULL,
                                   reset_expiry=NULL
                               WHERE username='$email' OR email='$email'";
                $conn->query($update_sql);

        
                unset($_SESSION['reset_email']);

                header("Location: login_.php?message=Password changed successfully");
                exit();
            } else {
                $message = "Passwords do not match!";
            }
        } else {
            $message = "Invalid or expired OTP.";
        }
    } else {
        $message = "Something went wrong. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h5 class="text-center mb-4">Enter OTP</h5>
                <?php if ($message): ?>
                    <div class="alert alert-danger"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="otp" class="form-label">OTP</label>
                        <input type="text" id="otp" name="otp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Verify &amp; Reset</button>
                </form>
                <p class="text-center mt-3"><a href="forgot_password.php">Resend OTP</a></p>
            </div>
        </div>
    </div>
</body>
</html>
