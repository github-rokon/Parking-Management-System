<?php
include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $slot_id = $_POST['slot_id'];
    $payment_method = $_POST['payment_method'];
    $booking_id = uniqid('B');
    $payment_id = uniqid('P');
    $booking_date = date('Y-m-d H:i:s');
    $amount = 50.00;

    // Insert booking record
    $sql = "INSERT INTO Bookings (Booking_ID, Slot_ID, User_ID, Vehicle_ID, Booking_Date, Status) 
            VALUES (:booking_id, :slot_id, :user_id, :vehicle_id, TO_DATE(:booking_date, 'YYYY-MM-DD HH24:MI:SS'), 'Booked')";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':booking_id', $booking_id);
    oci_bind_by_name($stmt, ':slot_id', $slot_id);
    oci_bind_by_name($stmt, ':user_id', $user_id);
    oci_bind_by_name($stmt, ':vehicle_id', $vehicle_id);
    oci_bind_by_name($stmt, ':booking_date', $booking_date);
    oci_execute($stmt);

    // Update slot status to "Booked"
    $update_sql = "UPDATE ParkingSlots SET Status = 'Booked' WHERE Slot_ID = :slot_id";
    $update_stmt = oci_parse($conn, $update_sql);
    oci_bind_by_name($update_stmt, ':slot_id', $slot_id);
    oci_execute($update_stmt);

    echo "<script>alert('Booking successful! Please proceed with payment.'); window.location.href='payment.php?booking_id=$booking_id';</script>";
    exit();
}
?>
