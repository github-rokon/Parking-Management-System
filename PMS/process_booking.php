<?php
include 'dbConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $slot_id = $_POST['slot_id'];
    $user_id = $_POST['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $datetime = $_POST['datetime'];

    // Insert booking record
    $query = "INSERT INTO Bookings (Booking_ID, Slot_ID, User_ID, Vehicle_ID, Date_Time, Status)
              VALUES (SEQ_BOOKING_ID.NEXTVAL, :slot_id, :user_id, :vehicle_id, TO_DATE(:datetime, 'YYYY-MM-DD HH24:MI:SS'), 'Booked')";

    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':slot_id', $slot_id);
    oci_bind_by_name($stid, ':user_id', $user_id);
    oci_bind_by_name($stid, ':vehicle_id', $vehicle_id);
    oci_bind_by_name($stid, ':datetime', $datetime);

    if (oci_execute($stid)) {
        // Update slot status
        $updateQuery = "UPDATE ParkingSlots SET Status = 'Booked' WHERE Slot_ID = :slot_id";
        $updateStid = oci_parse($conn, $updateQuery);
        oci_bind_by_name($updateStid, ':slot_id', $slot_id);
        oci_execute($updateStid);
        
        echo "<script>alert('Booking successful!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Booking failed!');</script>";
    }
}
?>
