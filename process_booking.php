<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $roomId = intval($_POST['room_id']);

    $conn->begin_transaction();
    try {
       
        $sql = "INSERT INTO bookings (name, email, phone, room_id, booking_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $phone, $roomId);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting booking: " . $stmt->error);
        }

       
        $checkUser = "SELECT id FROM users WHERE email = ?";
        $stmtCheck = $conn->prepare($checkUser);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows == 0) {
            $username = explode('@', $email)[0]; 
            $checkUsername = "SELECT id FROM users WHERE username = ?";
            $stmtUserCheck = $conn->prepare($checkUsername);
            $stmtUserCheck->bind_param("s", $username);
            $stmtUserCheck->execute();
            $stmtUserCheck->store_result();

            if ($stmtUserCheck->num_rows > 0) {
                $username .= rand(1000, 9999);
            }

  
            $insertUser = "INSERT INTO users (username, name, email, phone, role, status) VALUES (?, ?, ?, ?, 'Customer', 'Active')";
            $stmtUser = $conn->prepare($insertUser);
            $stmtUser->bind_param("ssss", $username, $name, $email, $phone);
            if (!$stmtUser->execute()) {
                throw new Exception("Error inserting user: " . $stmtUser->error);
            }
        }

        $updateRoomStatus = "UPDATE rooms SET status = 'Booked' WHERE id = ?";
        $stmtUpdate = $conn->prepare($updateRoomStatus);
        $stmtUpdate->bind_param("i", $roomId);
        if (!$stmtUpdate->execute()) {
            throw new Exception("Error updating room status: " . $stmtUpdate->error);
        }

        $conn->commit();

       
        $subject = "Booking Confirmation - Hotel Maya";
        $message = "
            <h1>Booking Confirmation</h1>
            <p>Dear $name,</p>
            <p>Thank you for booking with us. Here are your details:</p>
            <ul>
                <li><strong>Room ID:</strong> $roomId</li>
                <li><strong>Name:</strong> $name</li>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Phone:</strong> $phone</li>
                <li><strong>Booking Date:</strong> " . date('Y-m-d H:i:s') . "</li>
            </ul>
            <p>We look forward to hosting you!</p>
        ";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'carx41519@gmail.com';
            $mail->Password = 'yveq vnph tcgc hqpi';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('no-reply@hotelmaya.com', 'Hotel Maya');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            echo "<p>Email could not be sent. Error: {$mail->ErrorInfo}</p>";
        }

        header("Location: successfull.html?success=1");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}

$conn->close();
?>
