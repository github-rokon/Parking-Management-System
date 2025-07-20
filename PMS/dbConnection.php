<?php
$conn = oci_connect('system', 'rokon', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    echo "Connection failed: " . $e['message'];
} else {
     
}
?>