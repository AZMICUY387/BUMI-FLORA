<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cp = $_POST['cp'];
    $objek = $_POST['objek'];
    $cob = $_POST['cob'];
    $penempatan = $_POST['penempatan'];
    $cpb = $_POST['cpb'];
    $cb = $_POST['cb'];
    $satuan = $_POST['satuan'];
    $qty = $_POST['qty'];
    $cqty = $_POST['cqty'];
    $kondisi = $_POST['kondisi'];
    $kode_klasifikasi = $_POST['kode_klasifikasi'];
    $tp = $_POST['tp'];

    $sql_insert = "INSERT INTO Inventaris (CP, Objek, COB, Penempatan, CPB, CB, Satuan, QTY, CQTY, Kondisi, Kode_Klasifikasi, TP) VALUES ('$cp', '$objek', '$cob', '$penempatan', '$cpb', '$kategori', '$cb', '$satuan', '$qty', '$cqty', '$kondisi', '$kode_klasifikasi', '$tp')";
    $conn->query($sql_insert);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil daftar unik kendaraan dari tabel inventaris
$inventarisList = [];
$sql = "SELECT DISTINCT Objek FROM inventaris ORDER BY Objek ASC";
$resultinventaris = $conn->query($sql);

if ($resultinventaris) {
    while ($row = $resultinventaris->fetch_assoc()) {
        $inventarisList[] = $row;
    }
} else {
    echo "Error: " . $conn->error; // Untuk debugging jika terjadi kesalahan
}

$sql = "SELECT * FROM Inventaris";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventaris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="..//css/style.css">
    
</head>
<body>
    <div id="sidebar">
    <div class="label">
      <img src="../img/Logo.png" alt="logo" style="width: 50px;">
      <label><a href="index.php">Bumi Flora</a></label>
    </div>
    <ul>
    <li><a href="#">Dashboard</a></li>
            <li><a href="#">Inventaris</a></li>
            <li><a href="#">Laporan</a></li>
            <li><a href="#">Pengaturan</a></li>
    </ul>
  </div>
  <button class="toggle-btn" id="toggle-btn">Menu</button>
  <div id="content" class="container-fluid">
    <div class="row">
      
    </div>
    
    <section id="daftargudang">
    <div id="content" class="container-fluid">
        <div class="container mt-5">
            <h2>Daftar Inventaris</h2>
            <form method="POST" class="mb-4">
                <table class="table table-bordered">
                    <tr>
                        <td><input type="text" name="cp" class="form-control" placeholder="CP" required></td>
                        <td><input type="text" name="objek" class="form-control" placeholder="Objek" required></td>
                        <td><input type="text" name="cob" class="form-control" placeholder="COB" required></td>
                        <td><input type="text" name="penempatan" class="form-control" placeholder="Penempatan" required></td>
                        <td><input type="text" name="cpb" class="form-control" placeholder="CPB" required></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="kategori" class="form-control" placeholder="Nama kategori" required></td>
                        <td><input type="text" name="cb" class="form-control" placeholder="CB" required></td>
                        <td><input type="text" name="satuan" class="form-control" placeholder="Satuan" required></td>
                        <td><input type="number" name="qty" class="form-control" placeholder="QTY" required></td>
                        <td><input type="text" name="cqty" class="form-control" placeholder="CQTY" required></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="kondisi" class="form-control" placeholder="Kondisi" required></td>
                        <td><input type="text" name="kode_klasifikasi" class="form-control" placeholder="Kode Klasifikasi" required></td>
                        <td><input type="text" name="tp" class="form-control" placeholder="TP" required></td>
                        
                    </tr>
                </table>
                <button type="submit" class="btn btn-success">Tambah</button>
            </form>

            <form method="GET" action="">
            <div class="filter-container">
            <table class="filter-table">
    <table class="filter-table">
        <tr>
            <td>
                <label for="Objek" class="Objek-label">Objek Barang:</label>
            </td>
            <td>
            <select class="form-control" id="Objek" name="Objek" required>
                <option value="">Pilih Objek</option>
                <?php foreach ($inventarisList as $inventaris): ?>
                    <option value="<?= htmlspecialchars($inventaris['Objek']) ?>">
                        <?= htmlspecialchars($inventaris['Objek']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            </td>
        </tr>
    </table>
                </div>
    <div class="filter-actions">
        <button type="submit" class="btn btn-success">Filter</button>
    </div>
</form>

            <h2>Data Stock Inventaris</h2>
            <div class="table-container">
                <table id="stockTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>CP</th>
                            <th>Objek</th>
                            <th>COB</th>
                            <th>Nm brg</th>
                            <th>Penempatan</th>
                            <th>CPB</th>
                            <th>CB</th>
                            <th>Satuan</th>
                            <th>QTY</th>
                            <th>CQTY</th>
                            <th>Kondisi</th>
                            <th>Kode Klasifikasi</th>
                            <th>TP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $id = 1;
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$id}</td>
                                <td>{$row['CP']}</td>
                                <td>{$row['Objek']}</td>
                                <td>{$row['COB']}</td>
                                <td>{$row['Nama_Barang']}</td>
                                <td>{$row['Penempatan']}</td>
                                <td>{$row['CPB']}</td>
                                <td>{$row['CB']}</td>
                                <td>{$row['Satuan']}</td>
                                <td>{$row['QTY']}</td>
                                <td>{$row['CQTY']}</td>
                                <td>{$row['Kondisi']}</td>
                                <td>{$row['Kode_Klasifikasi']}</td>
                                <td>{$row['TP']}</td>
                            </tr>";
                            $id++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('toggle-btn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });

    $('#Objek').select2({
        placeholder: "Pilih Objek",
        allowClear: true,
        width: '100%'
      });
    </script>
</body>
</html>