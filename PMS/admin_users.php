<?php
include 'dbconnection.php';

// Add a new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Generate User ID (based on phone & email)
    $user_id = 'U' . substr(md5($phone . $email), 0, 6);

    // Insert user into database
    $sql = "INSERT INTO Users (User_ID, Name, Phone, Email) VALUES (:user_id, :name, :phone, :email)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':user_id', $user_id);
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':phone', $phone);
    oci_bind_by_name($stmt, ':email', $email);

    if (oci_execute($stmt)) {
        echo "User added successfully!";
    } else {
        echo "Error adding user!";
    }
}

// Delete a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $delete_sql = "DELETE FROM Users WHERE User_ID = :user_id";
    $delete_stmt = oci_parse($conn, $delete_sql);
    oci_bind_by_name($delete_stmt, ':user_id', $user_id);

    if (oci_execute($delete_stmt)) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user!";
    }
}

// Fetch all users
$sql = "SELECT * FROM Users ORDER BY User_ID ASC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Manage Users</h1>

    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit" name="add_user">Add User</button>
    </form>

    <h2>Users List</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while ($row = oci_fetch_assoc($stmt)): ?>
            <tr>
                <td><?php echo $row['USER_ID']; ?></td>
                <td><?php echo $row['NAME']; ?></td>
                <td><?php echo $row['PHONE']; ?></td>
                <td><?php echo $row['EMAIL']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?php echo $row['USER_ID']; ?>">
                        <button type="submit" name="delete_user">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
