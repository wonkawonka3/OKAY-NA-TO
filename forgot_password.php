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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);


    $sql = "SELECT * FROM users WHERE username = '$email' OR email = '$email'";


    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
     
        $otp = rand(100000, 999999);
      
        $expiry = time() + 300;
        $update_sql = "UPDATE users SET reset_token='$otp', reset_expiry='$expiry' WHERE username='$email' OR email='$email'";
        $conn->query($update_sql);


        require 'vendor/autoload.php'; 
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        try {
          
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';           
            $mail->SMTPAuth   = true;
            $mail->Username   = 'carx41519@gmail.com';   
            $mail->Password   = 'yveq vnph tcgc hqpi';      
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

        
            $mail->setFrom('carx41519@gmail.com', 'Hotel Maya');
            $mail->addAddress($email);

    
            $mail->isHTML(false);
            $mail->Subject = 'Your OTP for Password Reset';
            $mail->Body    = "Hello,\n\nYour OTP for password reset is: $otp\n\nThis OTP will expire in 5 minutes.\n\nIf you did not request a password reset, please ignore this email.";

            $mail->send();


            $_SESSION['reset_email'] = $email;

       
            header("Location: verify_otp.php");
            exit();
        } catch (Exception $e) {
            $message = "Error sending OTP: " . $mail->ErrorInfo;
        }
    } else {
        $message = "No account found with that email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow" style="width: 400px;">
      <div class="card-body">
        <h5 class="text-center mb-4">Forgot Password</h5>
        <?php if ($message): ?>
          <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label for="email" class="form-label">Enter your email</label>
            <input type="email" id="email" name="email" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Send OTP</button>
        </form>
        <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>
      </div>
    </div>
  </div>
</body>
</html>
