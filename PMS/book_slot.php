<?php
include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $vehicleType = $_POST['vehicleType']; // Car, Bike, Van
    $license = $_POST['license'];
    $slotID = $_POST['slotID'];
    $duration = $_POST['duration'];

    // Check if user exists
    $query = "SELECT User_ID FROM Users WHERE Phone = :phone";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':phone', $phone);
    oci_execute($stid);

    $row = oci_fetch_assoc($stid);
    if ($row) {
        $userID = $row['USER_ID']; // Existing user
    } else {
        // Generate new User_ID (e.g., U1001, U1002...)
        $userID = "U" . rand(1000, 9999);

        // Insert new user
        $query = "INSERT INTO Users (User_ID, Name, Phone, Email, Vehicle_Type, License_Number) 
                  VALUES (:userID, :name, :phone, :email, :vehicleType, :license)";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':userID', $userID);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':phone', $phone);
        oci_bind_by_name($stid, ':email', $email);
        oci_bind_by_name($stid, ':vehicleType', $vehicleType);
        oci_bind_by_name($stid, ':license', $license);
        oci_execute($stid);
    }

    // Generate Booking_ID (e.g., B20240201-1234)
    $bookingID = "B" . date("Ymd") . "-" . rand(1000, 9999);

    // Insert booking data
    $query = "INSERT INTO BOOKINGS (BOOKING_ID, USER_ID, SLOT_ID, VEHICLE_ID, BOOKING_DATE, DURATION, STATUS) 
              VALUES (:bookingID, :userID, :slotID, :vehicleID, CURRENT_TIMESTAMP, :duration, 'Booked')";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':bookingID', $bookingID);
    oci_bind_by_name($stid, ':userID', $userID);
    oci_bind_by_name($stid, ':slotID', $slotID);
    oci_bind_by_name($stid, ':vehicleID', $license);
    oci_bind_by_name($stid, ':duration', $duration);
    oci_execute($stid);

    // Update parking slot status
    $query = "UPDATE ParkingSlots SET Status = 'Booked' WHERE Slot_ID = :slotID";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':slotID', $slotID);
    oci_execute($stid);

    oci_close($conn);

    // Return success message with User_ID and Booking Details
    echo json_encode([
        "success" => true,
        "message" => "Booking confirmed!",
        "userID" => $userID,
        "bookingID" => $bookingID,
        "slotID" => $slotID
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
