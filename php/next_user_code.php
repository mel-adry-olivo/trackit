<?php

include "../database/database.php";
header('Content-Type: application/json');

$role = $_GET['role'] ?? 'employee';
$prefix = $role === 'manager' ? 'PKM' : ($role === 'admin' ? 'PKA' : 'PKE');

$conn = connect_to_database();
$sql = "SELECT MAX(user_code) AS maxcode
        FROM users
        WHERE user_code LIKE '{$prefix}%'";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
mysqli_close($conn);

if ($row && $row['maxcode']) {
  $num = intval(substr($row['maxcode'], 3));
  $next = $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
} else {
  $next = $prefix . '001';
}

echo json_encode(['next_code' => $next]);
