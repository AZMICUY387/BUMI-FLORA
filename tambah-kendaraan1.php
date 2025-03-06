<?php
// tambah_kendaraan.php
include 'koneksi.php';

// Ambil daftar kendaraan dari tabel vehicle
$vehicleList = [];
$resultVehicle = $conn->query("SELECT id, nama_kendaraan FROM vehicle ORDER BY nama_kendaraan ASC");
if ($resultVehicle) {
    while ($row = $resultVehicle->fetch_assoc()) {
        $vehicleList[] = $row;
    }
}

// Ambil daftar barang
$barangList = [];
$resultBarang = $conn->query("SELECT DISTINCT nama_barang FROM barang ORDER BY nama_barang ASC");
if ($resultBarang) {
    while ($row = $resultBarang->fetch_assoc()) {
        $barangList[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama_kendaraan = $_POST['nama_kendaraan'];
  $nama_barang = $_POST['nama_barang'];
  $banyak = $_POST['banyak'];
  $keterangan = $_POST['keterangan'];
  $periode = $_POST['periode'];

  // Ambil ID kendaraan berdasarkan nama
  $stmt = $conn->prepare("SELECT id FROM vehicle WHERE nama_kendaraan = ?");
  $stmt->bind_param("s", $nama_kendaraan);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $vehicle_id = $row['id'] ?? null;

  // Ambil ID barang berdasarkan nama
  $stmt = $conn->prepare("SELECT id FROM barang WHERE nama_barang = ?");
  $stmt->bind_param("s", $nama_barang);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $barang_id = $row['id'] ?? null;

  if ($vehicle_id && $barang_id) {
      // Insert ke log_vehicle
      $stmt = $conn->prepare("INSERT INTO log_vehicle (vehicle_id, barang_id, quantity, keterangan, periode) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("iiiss", $vehicle_id, $barang_id, $banyak, $keterangan, $periode);

      if ($stmt->execute()) {
          echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='tambah-kendaraan1.php';</script>";
      } else {
          echo "<script>alert('Terjadi kesalahan: ".$stmt->error."');</script>";
      }
  } else {
      echo "<script>alert('Kendaraan atau barang tidak ditemukan!');</script>";
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Kendaraan - Bumi Flora</title>
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
  <?php // include 'sidebar.php'; ?>
  <div class="container mt-5" style="padding: 60px;">
    <section id="tambahkendaraan">
      <h2>Kendaraan yang Memakai Barang</h2>
      <form method="post" action="tambah-kendaraan1.php">
          <div class="form-group">
              <label for="kendaraan">Nama Kendaraan</label>
              <select class="form-control" id="kendaraan" name="nama_kendaraan" required>
                <option value="">Pilih Kendaraan</option>
                  <?php foreach ($vehicleList as $vehicle): ?>
                      <option value="<?= htmlspecialchars($vehicle['nama_kendaraan']) ?>">
                          <?= htmlspecialchars($vehicle['nama_kendaraan']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="form-group">
              <label for="NamaBarang">Nama Barang</label>
              <select class="form-control" id="NamaBarang" name="nama_barang" required>
                  <option value="">Pilih Barang</option>
                  <?php foreach ($barangList as $barang): ?>
                      <option value="<?= htmlspecialchars($barang['nama_barang']) ?>">
                          <?= htmlspecialchars($barang['nama_barang']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="form-group">
              <label for="Jumlah">Jumlah yang Dipakai</label>
              <input type="number" class="form-control" id="Jumlah" name="banyak" placeholder="Masukkan jumlah" required>
          </div>
          <div class="form-group">
              <label for="keterangan">Keterangan Pemakai</label>
              <input type="text" class="form-control" id="keterangan" name="keterangan">
          </div>
          <div class="form-group">
              <label for="tanggal">Tanggal Dipakai</label>
              <input type="date" class="form-control" id="tanggal" name="periode">
          </div>
          <button type="submit" class="btn btn-success">Tambah Kendaraan</button>
      </form>
    </section>
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
      $('#kendaraan').select2({
        placeholder: "Pilih Kendaraan",
        allowClear: true,
        width: '100%'
      });
      $('#NamaBarang').select2({
        placeholder: "Pilih Barang",
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
