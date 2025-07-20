<?php
include 'dbconnection.php';

header('Content-Type: application/json');

if (isset($_GET['phone'])) {
    $phone = $_GET['phone'];

    // Query to get User_ID from Users table using phone number
    $userQuery = "SELECT USER_ID FROM Users WHERE PHONE = :phone";
    $stid = oci_parse($conn, $userQuery);
    oci_bind_by_name($stid, ':phone', $phone);
    oci_execute($stid);

    $user = oci_fetch_assoc($stid);
    
    if (!$user) {
        echo json_encode([]); // No user found
        exit;
    }

    $user_id = $user['USER_ID'];

    // Fetch bookings related to this user
    $bookingQuery = "SELECT * FROM Bookings WHERE USER_ID = :user_id";
    $stid = oci_parse($conn, $bookingQuery);
    oci_bind_by_name($stid, ':user_id', $user_id);
    oci_execute($stid);

    $bookings = [];
    while ($row = oci_fetch_assoc($stid)) {
        $bookings[] = $row;
    }

    echo json_encode($bookings);
}

oci_close($conn);
?>
