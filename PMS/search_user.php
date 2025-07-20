<?php
include 'dbConnection.php';

if (isset($_GET['phone'])) {
    $phone = $_GET['phone'];

    $query = "SELECT * FROM Users WHERE PHONE = :phone";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':phone', $phone);
    oci_execute($stid);

    $result = oci_fetch_assoc($stid);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(["error" => "User not found"]);
    }

    oci_free_statement($stid);
    oci_close($conn);
}
?>
