<?php
session_start();
include 'db.php';

if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_id = $_POST['group'];
    $country_id = $_POST['country'];
    $wins = $_POST['wins'];
    $draws = $_POST['draws'];
    $losses = $_POST['losses'];
    $points = ($wins * 3) + ($draws);

    
    $check_sql = "SELECT * FROM standings WHERE group_id='$group_id' AND country_id='$country_id'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        
        $sql = "UPDATE standings SET wins='$wins', draws='$draws', losses='$losses', points='$points' WHERE group_id='$group_id' AND country_id='$country_id'";
    } else {
        
        $sql = "INSERT INTO standings (group_id, country_id, wins, draws, losses, points) VALUES ('$group_id', '$country_id', '$wins', '$draws', '$losses', '$points')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$groups = $conn->query("SELECT * FROM groups");
$countries = $conn->query("SELECT * FROM countries");
$standings = $conn->query("SELECT g.name as group_name, c.name as country_name, s.wins, s.draws, s.losses, s.points 
                           FROM standings s
                           JOIN groups g ON s.group_id = g.id
                           JOIN countries c ON s.country_id = c.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Data</title>
</head>
<body>
<h1>Data Group B</h1>
<p><?php echo date('l, d F Y H:i:s'); ?></p> 
<p><b>201011401941</b></p>
    <form method="post" action="">
        <label>Group:</label>
        <select name="group">
            <?php while ($group = $groups->fetch_assoc()) { ?>
                <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
            <?php } ?>
        </select>
        <label>Negara:</label>
        <select name="country">
            <?php while ($country = $countries->fetch_assoc()) { ?>
                <option value="<?php echo $country['id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?>
        </select>
        <label>Menang:</label>
        <input type="number" name="wins" required>
        <label>Seri:</label>
        <input type="number" name="draws" required>
        <label>Kalah:</label>
        <input type="number" name="losses" required>
        <input type="submit" value="Simpan">

        <a href="index.php">HOME</a>
    </form>

    <h2>Hasil Standings</h2>
    <table border="1">
        <tr>
            <th>Group</th>
            <th>Negara</th>
            <th>Menang</th>
            <th>Seri</th>
            <th>Kalah</th>
            <th>Poin</th>
        </tr>
        <?php while ($row = $standings->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['group_name']; ?></td>
                <td><?php echo $row['country_name']; ?></td>
                <td><?php echo $row['wins']; ?></td>
                <td><?php echo $row['draws']; ?></td>
                <td><?php echo $row['losses']; ?></td>
                <td><?php echo $row['points']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
