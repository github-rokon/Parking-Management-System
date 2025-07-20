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
        <input type="text" id="user_id" placeholder="Enter your User ID">
        <button onclick="searchPayments()">Search</button>

        <table border="1">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="paymentResults"></tbody>
        </table>
    </main>

    <script>
        function searchPayments() {
            let userId = document.getElementById("user_id").value;
            if (userId.trim() === "") {
                alert("Please enter your User ID.");
                return;
            }

            fetch(`search_payments.php?user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    let tbody = document.getElementById("paymentResults");
                    tbody.innerHTML = "";
                    data.forEach(row => {
                        let tr = document.createElement("tr");
                        let payButton = row.STATUS === "Pending" 
                            ? `<button onclick="payNow('${row.BOOKING_ID}')">Pay Now</button>` 
                            : "Completed";
                        tr.innerHTML = `<td>${row.BOOKING_ID}</td>
                                        <td>${row.AMOUNT}</td>
                                        <td>${row.PAYMENT_METHOD}</td>
                                        <td>${row.STATUS}</td>
                                        <td>${payButton}</td>`;
                        tbody.appendChild(tr);
                    });
                });
        }

        function payNow(bookingId) {
            if (confirm("Proceed with payment?")) {
                window.location.href = `process_payment.php?booking_id=${bookingId}`;
            }
        }
    </script>
</body>
<footer>
        <p>&copy; 2025 Parking Management System</p>
    </footer>
</html>
