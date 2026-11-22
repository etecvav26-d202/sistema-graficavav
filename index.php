<?php
session_start();

if (isset($_SESSION['idfuncionario'])) {
    header("Location: admin/dashboard.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>
