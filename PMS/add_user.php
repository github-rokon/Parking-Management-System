<?php
include 'dbConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Generate USER_ID (Based on Phone Number)
    $user_id = "USR_" . preg_replace('/\D/', '', $phone);

    // Check if user already exists
    $checkQuery = "SELECT USER_ID FROM Users WHERE PHONE = :phone OR EMAIL = :email";
    $stid = oci_parse($conn, $checkQuery);
    oci_bind_by_name($stid, ':phone', $phone);
    oci_bind_by_name($stid, ':email', $email);
    oci_execute($stid);

    if ($row = oci_fetch_assoc($stid)) {
        echo "<script>alert('User already exists with ID: {$row['USER_ID']}');</script>";
    } else {
        // Insert new user
        $insertQuery = "INSERT INTO Users (USER_ID, NAME, PHONE, EMAIL) 
                        VALUES (:user_id, :name, :phone, :email)";
        $stid = oci_parse($conn, $insertQuery);
        oci_bind_by_name($stid, ':user_id', $user_id);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':phone', $phone);
        oci_bind_by_name($stid, ':email', $email);

        if (oci_execute($stid)) {
            echo "<script>alert('User added successfully! USER_ID: $user_id');</script>";
        } else {
            echo "<script>alert('Error adding user');</script>";
        }
    }

    oci_free_statement($stid);
    oci_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Add New User</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required><br>

        <label>Phone:</label>
        <input type="text" name="phone" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <button type="submit">Add User</button>
    </form>
</body>
</html>
