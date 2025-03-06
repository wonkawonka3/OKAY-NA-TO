<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #121c84, #8278da);
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
        }

        .success-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 50%;
            margin: auto;
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            color: white;
            margin-bottom: 20px;
        }

        h2 {
            color: black;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .btn-container {
            margin-top: 20px;
        }

        .btn {
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Hotel Management System</h1>
</header>

<div class="success-container">
    <h2> Room Added Successfully!</h2>
    
    <div class="btn-container">
        <a href="index.php" class="btn btn-primary">Go to Homepage</a>
    </div>
</div>

</body>
</html>
