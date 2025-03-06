<?php
// input_barang.php
include 'koneksi.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nama_barang"]) && isset($_POST["unit"])) {
    // Proses penyimpanan data barang
    $nama_barang = $_POST["nama_barang"];
    $unit        = $_POST["unit"];
    $kategori    = $_POST["kategori"];
    $quantity    = $_POST["banyak"];
    $tanggal     = $_POST["periode"];

    $stmt = $conn->prepare("INSERT INTO barang (kategori, nama_barang, banyak, unit, periode) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $kategori, $nama_barang, $quantity, $unit, $tanggal);

    if ($stmt->execute()) {
        $msg = "Data barang berhasil dimasukkan!";
    } else {
        $msg = "Terjadi kesalahan: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Barang - Bumi Flora</title>
  <!-- Bootstrap & Select2 CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="sidebar">
    <div class="label">
      <img src="img/Logo.png" alt="logo" style="width: 50px;">
      <label><a href="index.php">Bumi Flora</a></label>
    </div>
    <ul><li><a href="daftargudang.php">Daftar Gudang</a></li>
      <li><a href="inputbarang.php">Input Barang</a></li>
      
      <li><a href="tambah-kendaraan1.php">Tambah Kendaraan</a></li>
    </ul>
  </div>
  <button class="toggle-btn" id="toggle-btn">Menu</button>
  <div id="content" class="container-fluid">
    <div class="row">
      
    </div>
    
    

  <!-- (Opsional) Sertakan sidebar atau header jika diperlukan -->
  <?php // include 'sidebar.php'; ?>
  <div class="container mt-5" style="padding: 60px;">
    <h2>Input Barang</h2>
    <?php if ($msg != "") echo "<div class='alert alert-info'>$msg</div>"; ?>
    <form method="post" action="inputbarang.php">
      <div class="form-group">
          <label for="namaBarang">Nama Barang</label>
          <input type="text" class="form-control" id="namaBarang" name="nama_barang" placeholder="Masukkan nama barang">
      </div>
      <div class="form-group">
          <label for="unit">Satuan Unit:</label>
          <select id="unit" name="unit" required class="form-control">
              <option value="" disabled selected>Pilih Opsi</option>
              <option value="buah">Bh</option>
                <option value="pcs">Pcs</option>
                <option value="unit">Unit</option>
                <option value="psg">Psg</option>
                <option value="btg">Btg</option>
                <option value="kg">Kg</option>
                <option value="mtr">Mtr</option>
                <option value="bks">Bks</option>
                <option value="roll">Roll</option>
                <option value="bal">Bal</option>
                <option value="ktk">Ktk</option>
                <option value="pak">Pak</option>
                <option value="kpg">Kpg</option>
                <option value="set">Set</option>
                <option value="lbr">Lbr</option>
                <option value="glg">Glg</option>
                <option value="sak">Sak</option>
                <option value="ikat">Ikat</option>
                <option value="btl">Btl</option>
                <option value="jrg">Jrg</option>
                <option value="liter">Liter</option>
                <option value="gram">Gram</option>
              <!-- Tambahkan opsi satuan lainnya -->
          </select>
      </div>
      <div class="form-group">
          <label for="kategori">Kategori:</label>
          <select id="kategori" name="kategori" required class="form-control">
              <option value="" disabled selected>Pilih Kategori</option>
              <option value="Bengkel/Kendaraan">Traksi</option>
                <option value="BBM">BBM</option>
                <option value="Racun">Racun</option>
                <option value="Pupuk">Pupuk</option>
                <option value="Rambung Muda">Rambung Muda</option>
                <option value="Listrik">Listrik</option>
                <option value="Air">Air</option>
                <option value="Peralatan Bangunan">Peralatan Bangunan</option>
                <option value="Peralatan Pertanian">Peralatan Pertanian</option>
                <option value="Alat Lainnya">Alat Lainnya</option>
          </select>
      </div>
      <div class="form-group">
          <label for="quantity">Quantity</label>
          <input type="number" class="form-control" id="quantity" name="banyak" placeholder="Masukkan jumlah">
      </div>
      <div class="form-group">
          <label for="tanggal">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="periode">
      </div>
      <button type="submit" class="btn btn-success">Submit</button>
    </form>
  </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Bumi Flora. All rights reserved.</p>
        </div>
    </footer>
  
  <!-- JS Bootstrap & Select2 -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#unit').select2({
        placeholder: "Pilih Opsi",
        allowClear: true,
        width: '100%'
      });
      $('#kategori').select2({
        placeholder: "Pilih Kategori",
        allowClear: true,
        width: '100%'
      });
    });

    // Toggle sidebar
    document.getElementById('toggle-btn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });
  </script>
</body>
</html>
