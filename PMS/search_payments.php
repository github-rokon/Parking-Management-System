<?php
include 'dbconnection.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT P.Booking_ID, P.Amount, P.Payment_Method, P.Status 
            FROM Payments P
            JOIN Bookings B ON P.Booking_ID = B.Booking_ID
            WHERE B.User_ID = :user_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':user_id', $user_id);
    oci_execute($stmt);

    $payments = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $payments[] = $row;
    }
    echo json_encode($payments);
}
?>
