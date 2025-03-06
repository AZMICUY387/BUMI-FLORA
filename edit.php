    <?php
    include 'koneksi.php';

    // Proses form submission untuk update data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari form
        $id   = $_POST['id'];
        $old_periode   = $_POST['old_periode'];
        $new_periode   = $_POST['new_periode']; // format YYYY-MM-DD
        $new_banyak  = $_POST['new_banyak'];
        
        // Ambil Nama_Barang dari tabel barang berdasarkan id
        $sqlBarang = "SELECT nama_barang FROM barang WHERE id = '$id'";
        $resultBarang = $conn->query($sqlBarang);
        if ($resultBarang && $resultBarang->num_rows > 0) {
            $rowBarang = $resultBarang->fetch_assoc();
            $nama_barang = $rowBarang['nama_barang'];
            
            // Update data pada tabel barang_history (log barang) berdasarkan nama barang dan periode
            $sqlUpdate = "UPDATE barang
                          SET periode = '$new_periode', banyak = '$new_banyak'
                          WHERE id = '$id'";
                           
            if ($conn->query($sqlUpdate) === TRUE) {
                // Redirect kembali ke daftargudang.php dengan pesan sukses
                header("Location: daftargudang.php?message=Update+successful");
                exit();
            } else {
                $error = "Error updating record: " . $conn->error;
            }
        } else {
            $error = "Data barang tidak ditemukan.";
        }
    }

    // Jika request GET, ambil data dari URL untuk menampilkan form
    // Cek apakah semua parameter yang diperlukan ada
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'], $_GET['periode'], $_GET['banyak'])) {
        $id = $_GET['id'];
        $old_periode = $_GET['periode'];
        $banyak = $_GET['banyak'];
    } else {
        // Redirect ke halaman lain jika parameter tidak ada
        echo "Parameter tidak lengkap. Harap coba lagi.";
        exit();
    }
}
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Tgl / Qty</title>
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
        <ul>
          <li><a href="daftargudang.php">Daftar Gudang</a></li>
          <li><a href="inputbarang.php">Input Barang</a></li>
          <li><a href="tambah-kendaraan1.php">Tambah Kendaraan</a></li>
        </ul>
      </div>
      <button class="toggle-btn" id="toggle-btn">Menu</button>
      <div id="content" class="container-fluid">
        <div class="row">
          
        </div>
            <div class="container mt-5" style="padding: 100px;">
                <h2>Edit Tgl / Qty</h2>
                <?php if(isset($error)) { echo '<p class="error">'. $error .'</p>'; } ?>
                <form action="edit.php" method="POST">
                    <div class="form-group">
                    <!-- Data kode barang dan periode lama disimpan sebagai hidden -->
                    <input type="text" class="form-control" hidden name="id" value="<?= htmlspecialchars($id) ?>">
                    <input type="text" class="form-control" hidden name="old_periode" >
                    </div>
                    <div class="form-group">
                    <label for="new_periode">Tanggal</label>
                    <input type="text" class="form-control" id="new_periode" name="new_periode" value="<?= htmlspecialchars($old_periode) ?>" required>
                    </div>
                    <div class="form-group">
                    <label for="new_banyak">Banyak</label>
                    <input type="number" class="form-control" id="new_banyak" name="new_banyak" value="<?= htmlspecialchars($banyak) ?>" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
        <script>
            // Toggle sidebar
    document.getElementById('toggle-btn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });
        </script>
    </body>
    </html>