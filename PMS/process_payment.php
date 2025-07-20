<?php
include 'dbconnection.php';

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $update_sql = "UPDATE Payments SET Status = 'Completed' WHERE Booking_ID = :booking_id";
    $update_stmt = oci_parse($conn, $update_sql);
    oci_bind_by_name($update_stmt, ':booking_id', $booking_id);
    oci_execute($update_stmt);

    $update_slot = "UPDATE ParkingSlots SET Status = 'Confirmed' 
                    WHERE Slot_ID = (SELECT Slot_ID FROM Bookings WHERE Booking_ID = :booking_id)";
    $slot_stmt = oci_parse($conn, $update_slot);
    oci_bind_by_name($slot_stmt, ':booking_id', $booking_id);
    oci_execute($slot_stmt);

    echo "<script>alert('Payment successful! Slot confirmed.'); window.location.href='payment.php';</script>";
}
?>
