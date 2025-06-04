<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['role_id'])) {
    $_SESSION['role_id'] = intval($_POST['role_id']);
}
?>
