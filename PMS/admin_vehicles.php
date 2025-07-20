<?php
include 'dbconnection.php';

// Add a new vehicle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_vehicle'])) {
    $license_plate = $_POST['license_plate'];
    $vehicle_type = $_POST['vehicle_type'];
    $user_id = $_POST['user_id'];

    // Generate Vehicle ID
    $vehicle_id = 'V' . substr(md5($license_plate), 0, 6);

    $sql = "INSERT INTO Vehicles (Vehicle_ID, License_Plate, Vehicle_Type, User_ID) 
            VALUES (:vehicle_id, :license_plate, :vehicle_type, :user_id)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':vehicle_id', $vehicle_id);
    oci_bind_by_name($stmt, ':license_plate', $license_plate);
    oci_bind_by_name($stmt, ':vehicle_type', $vehicle_type);
    oci_bind_by_name($stmt, ':user_id', $user_id);

    if (oci_execute($stmt)) {
        echo "Vehicle added successfully!";
    } else {
        echo "Error adding vehicle!";
    }
}

// Delete a vehicle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];

    $delete_sql = "DELETE FROM Vehicles WHERE Vehicle_ID = :vehicle_id";
    $delete_stmt = oci_parse($conn, $delete_sql);
    oci_bind_by_name($delete_stmt, ':vehicle_id', $vehicle_id);

    if (oci_execute($delete_stmt)) {
        echo "Vehicle deleted successfully!";
    } else {
        echo "Error deleting vehicle!";
    }
}

// Fetch all vehicles
$sql = "SELECT Vehicles.*, Users.Name FROM Vehicles JOIN Users ON Vehicles.User_ID = Users.User_ID ORDER BY Vehicle_ID ASC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Manage Vehicles</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Manage Vehicles</h1>

    <form method="post">
        <input type="text" name="license_plate" placeholder="License Plate" required>
        <select name="vehicle_type">
            <option value="Bike">Bike</option>
            <option value="Car">Car</option>
            <option value="Microbus">Microbus</option>
        </select>
        <input type="text" name="user_id" placeholder="User ID (Assigned by Admin)" required>
        <button type="submit" name="add_vehicle">Add Vehicle</button>
    </form>

    <h2>Vehicle List</h2>
    <table>
        <tr>
            <th>Vehicle ID</th>
            <th>License Plate</th>
            <th>Type</th>
            <th>Owner (User)</th>
            <th>Action</th>
        </tr>
        <?php while ($row = oci_fetch_assoc($stmt)): ?>
            <tr>
                <td><?php echo $row['VEHICLE_ID']; ?></td>
                <td><?php echo $row['LICENSE_PLATE']; ?></td>
                <td><?php echo $row['VEHICLE_TYPE']; ?></td>
                <td><?php echo $row['NAME']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="vehicle_id" value="<?php echo $row['VEHICLE_ID']; ?>">
                        <button type="submit" name="delete_vehicle">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
