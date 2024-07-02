<?php
session_start();
include 'db.php';

if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}

require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Laporan Klasemen UEFA 2024', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function ReportTable($header, $data) {
        $this->SetFont('Arial', 'B', 12);
        foreach ($header as $col) {
            $this->Cell(40, 7, $col, 1);
        }
        $this->Ln();
        $this->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(40, 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdf = new PDF();
    $pdf->AddPage();
    $header = array('Tim', 'Menang', 'Seri', 'Kalah', 'Poin');
    $data = [];

    $sql = "SELECT c.name as country, s.wins, s.draws, s.losses, s.points FROM standings s JOIN countries c ON s.country_id = c.id";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $data[] = array($row['country'], $row['wins'], $row['draws'], $row['losses'], $row['points']);
    }

    $pdf->ReportTable($header, $data);
    $pdf->Output();
    exit();
}

$sql = "SELECT c.name as country, s.wins, s.draws, s.losses, s.points FROM standings s JOIN countries c ON s.country_id = c.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan</title>
</head>
<body>
    <table border="1">
        <tr>
            <th>Tim</th>
            <th>Menang</th>
            <th>Seri</th>
            <th>Kalah</th>
            <th>Poin</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['country']; ?></td>
                <td><?php echo $row['wins']; ?></td>
                <td><?php echo $row['draws']; ?></td>
                <td><?php echo $row['losses']; ?></td>
                <td><?php echo $row['points']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <form method="post" action="">
        <input type="submit" value="Cetak PDF">
    </form>
</body>
</html>
