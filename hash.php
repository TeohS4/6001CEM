<?php
include 'connect.php';

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Hash the password
$username = "admin"; 
$password = "Admin123@";

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$query = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";

if (mysqli_query($db, $query)) {
    echo "Record inserted successfully";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($db);
}

mysqli_close($db);
?>
