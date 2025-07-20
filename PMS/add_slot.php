<?php
include 'dbConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $slotID = $_POST['slotID'];

    // Check if the slot already exists
    $checkQuery = "SELECT * FROM ParkingSlots WHERE Slot_ID = :slotID";
    $stid = oci_parse($conn, $checkQuery);
    oci_bind_by_name($stid, ':slotID', $slotID);
    oci_execute($stid);

    if (oci_fetch_assoc($stid)) {
        echo json_encode(["error" => "Slot ID already exists!"]);
    } else {
        // Insert new slot
        $insertQuery = "INSERT INTO ParkingSlots (Slot_ID, Status) VALUES (:slotID, 'Available')";
        $stidInsert = oci_parse($conn, $insertQuery);
        oci_bind_by_name($stidInsert, ':slotID', $slotID);
        oci_execute($stidInsert);
        
        echo json_encode(["message" => "Parking slot added successfully!"]);
    }

    oci_free_statement($stid);
    oci_free_statement($stidInsert);
    oci_close($conn);
}
?>
