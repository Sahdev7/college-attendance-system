<?php
$password = 'Admin1';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hash;
?>