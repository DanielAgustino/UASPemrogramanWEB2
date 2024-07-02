<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE nim='$nim' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['nim'] = $nim;
        header("Location: index.php");
    } else {
        echo "Login failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="post" action="">
        NIM: <input type="text" name="nim" required>
        Password: <input type="password" name="password" required>
        <input type="submit" value="Login">
    </form>
</body>
</html>
