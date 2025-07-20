<?php
include 'dbConnection.php';

// Fetch parking slots
$query = "SELECT Slot_ID, Status FROM ParkingSlots";
$stid = oci_parse($conn, $query);
oci_execute($stid);

$slots = [];
while ($row = oci_fetch_assoc($stid)) {
    $slots[] = $row;
}

oci_free_statement($stid);
oci_close($conn);
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
        <div class="parking-lot">
            <?php foreach ($slots as $slot): ?>
                <div class="slot <?= strtolower($slot['STATUS']) ?>" 
                     data-slot="<?= $slot['SLOT_ID'] ?>" 
                     onclick="handleSlotClick('<?= $slot['SLOT_ID'] ?>', '<?= $slot['STATUS'] ?>')">
                    <?= $slot['SLOT_ID'] ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="legend">
            <p><span class="available"></span> Available</p>
            <p><span class="booked"></span> Booked</p>
            <p><span class="confirmed"></span> Confirmed</p>
        </div>
    </main>

    <!-- Booking & Payment Side Panel -->
    <div id="sidePanel" class="side-panel hidden">
        <button class="close-btn" onclick="closePanel()">×</button>
        <h2>Book</h2>
        <form id="bookingForm" target="_self">
        <input type="hidden" id="slotID" name="slotID">

        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="vehicleType">Vehicle Type:</label>
        <select name="vehicleType" required>
            <option value="Car">Car</option>
            <option value="Bike">Bike</option>
            <option value="Van">Van</option>
        </select>

        <label for="license">License Number:</label>
        <input type="text" name="license" required>

        <label for="duration">Duration (hours):</label>
        <input type="number" name="duration" required>

        <button type="submit">Confirm Booking</button>
    </form>



        

    </div>

    <footer>
        <p>&copy; 2025 Parking Management System</p>
    </footer>

    <script>
        function handleSlotClick(slotID, status) {
            if (status === 'Booked' || status === 'Confirmed') {
                alert(`Slot ${slotID} is ${status}.`);
            } else {
                document.getElementById('slotID').value = slotID;
                document.getElementById('sidePanel').classList.remove('hidden');
            }
        }

        function closePanel() {
            document.getElementById('sidePanel').classList.add('hidden');
        }
    </script>
</body>
</html>
