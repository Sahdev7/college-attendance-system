<?php
require 'includes/db_connect.php';
$stmt = $conn->prepare("SELECT admin_password FROM admin WHERE admin_id = ?");
$stmt->execute(['A1']);
$admin = $stmt->fetch();
$password = 'Admin1';
if ($admin && password_verify($password, $admin['admin_password'])) {
    echo "Password is correct!";
} else {
    echo "Password is incorrect!";
}
?>