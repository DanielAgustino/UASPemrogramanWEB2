<?php
session_start();
if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Selamat Datang, <?php echo $_SESSION['nim']; ?></h1>
    <a href="input.php">Input Data</a>
    <a href="report.php">Laporan</a>
    <a href="logout.php">Logout</a>
</body>
</html>
