<?php
include 'dbconnection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Management System</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="script.js" defer></script>
    <style>
    body {
    background-image: url("img/bg.png");
    background-size: 100% 100%; 
    background-repeat: no-repeat; 
    background-attachment: fixed;
    }
</style>
</head>
<body>
<header>
<a href="index.php">
            <img src="img/logo.png" alt="Logo" class="logo">
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Parking Slot</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="payment.php">Payment</a></li>
                <li><a href="about.php">About Us</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <label for="phone">Enter Phone Number:</label>
        <input type="text" id="phone" placeholder="Enter your Phone Number">
        <button onclick="searchBookings()">Search</button>

        <table border="1">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User ID</th>
                    <th>Vehicle ID</th>
                    <th>Slot ID</th>
                    <th>Booking Date</th>
                    <th>Duration (hours)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="bookingResults"></tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Parking Management System</p>
    </footer>
</body>
</html>
