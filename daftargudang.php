<?php
include 'koneksi.php';

// Ambil parameter filter dari GET (jika ada)
$filterType      = isset($_GET['filter-type']) ? $_GET['filter-type'] : '';
$periodeFilter   = isset($_GET['periode']) ? $_GET['periode'] : '';
$yearFilter      = isset($_GET['year']) ? $_GET['year'] : '';
$yearMonthFilter = isset($_GET['year_month']) ? $_GET['year_month'] : '';
$kategoriFilter  = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$barangSearch    = isset($_GET['barang44']) ? $_GET['barang44'] : '';

// Jika tidak ada filter yang dipilih, default ke filter year_month dengan bulan sekarang
if(empty($filterType)) {
    $filterType = "year_month";
    $yearMonthFilter = date("Y-m");
}

// Ambil daftar periode untuk dropdown filter
$resultPeriode = $conn->query("SELECT DISTINCT periode FROM barang ORDER BY periode ASC");
if (!$resultPeriode) {
    $resultPeriode = $conn->query("SELECT DISTINCT periode FROM log_barang ORDER BY periode ASC");
}

// Ambil daftar nama barang untuk search
$namaBarangList44 = [];
$resultNama = $conn->query("SELECT DISTINCT nama_barang FROM barang ORDER BY nama_barang ASC");
if ($resultNama) {
    while ($row = $resultNama->fetch_assoc()) {
        $namaBarangList44[] = $row['nama_barang'];
    }
}

if ($filterType == 'year_month' && $yearMonthFilter != '') {
    // Bulan yang dipilih, misalnya "2023-03"
    $selectedMonth = $conn->real_escape_string($yearMonthFilter);
    // Bulan sebelumnya, misalnya "2023-02"
    $previousMonth = date("Y-m", strtotime("-1 month", strtotime($yearMonthFilter . "-01")));
    
    // Kondisi tambahan untuk data current (bulan yang dipilih)
    $extraWhere = " AND DATE_FORMAT(lb.periode, '%Y-%m') = '$selectedMonth' ";
    if ($kategoriFilter != '') {
        $extraWhere .= " AND b.kategori = '" . $conn->real_escape_string($kategoriFilter) . "' ";
    }
    if ($barangSearch != '') {
        $extraWhere .= " AND b.nama_barang = '" . $conn->real_escape_string($barangSearch) . "' ";
    }
    
    // Query untuk data current bulan yang dipilih
    $sqlCurrent = "SELECT 
            b.id, 
            b.kategori, 
            b.nama_barang, 
            b.unit, 
            lb.banyak, 
            lb.periode, 
            lv.afd1, lv.afd2, lv.afd3, lv.afd4, lv.afd5, 
            lv.traksi, lv.bibitan, lv.lainnya,
            b.keterangan 
        FROM barang b
        LEFT JOIN log_barang lb 
            ON lb.barang_id = b.id AND DATE_FORMAT(lb.periode, '%Y-%m') = '$selectedMonth'
        LEFT JOIN (
            SELECT 
                lv.barang_id,
                SUM(CASE WHEN v.nama_kendaraan = 'AFD I' THEN lv.quantity ELSE 0 END) AS afd1,
                SUM(CASE WHEN v.nama_kendaraan = 'AFD II' THEN lv.quantity ELSE 0 END) AS afd2,
                SUM(CASE WHEN v.nama_kendaraan = 'AFD III' THEN lv.quantity ELSE 0 END) AS afd3,
                SUM(CASE WHEN v.nama_kendaraan = 'AFD IV' THEN lv.quantity ELSE 0 END) AS afd4,
                SUM(CASE WHEN v.nama_kendaraan = 'AFD V' THEN lv.quantity ELSE 0 END) AS afd5,
                SUM(CASE WHEN v.nama_kendaraan = 'traksi' THEN lv.quantity ELSE 0 END) AS traksi,
                SUM(CASE WHEN v.nama_kendaraan = 'bibitan' THEN lv.quantity ELSE 0 END) AS bibitan,
                SUM(CASE WHEN v.nama_kendaraan = 'lainnya' THEN lv.quantity ELSE 0 END) AS lainnya
            FROM log_vehicle lv
            JOIN vehicle v ON lv.vehicle_id = v.id
            WHERE DATE_FORMAT(lv.periode, '%Y-%m') = '$selectedMonth'
            GROUP BY lv.barang_id
        ) lv ON lv.barang_id = b.id
        WHERE 1=1 $extraWhere
        ORDER BY b.id ASC";
    
    // Jalankan query current
    $resultCurrent = $conn->query($sqlCurrent);
    
    // Query untuk total masuk dari log_barang di bulan sebelumnya
    $sqlPrevMasuk = "SELECT barang_id, SUM(banyak) AS totalMasukPrev 
                     FROM log_barang 
                     WHERE DATE_FORMAT(periode, '%Y-%m') = '$previousMonth'
                     GROUP BY barang_id";
    $resultPrevMasuk = $conn->query($sqlPrevMasuk);
    $prevMasuk = [];
    if ($resultPrevMasuk) {
        while ($row = $resultPrevMasuk->fetch_assoc()) {
            $prevMasuk[$row['barang_id']] = $row['totalMasukPrev'];
        }
    }
    
    // Query untuk total keluar dari log_vehicle di bulan sebelumnya
    $sqlPrevKeluar = "SELECT barang_id, SUM(quantity) AS totalKeluarPrev 
                      FROM log_vehicle 
                      WHERE DATE_FORMAT(periode, '%Y-%m') = '$previousMonth'
                      GROUP BY barang_id";
    $resultPrevKeluar = $conn->query($sqlPrevKeluar);
    $prevKeluar = [];
    if ($resultPrevKeluar) {
        while ($row = $resultPrevKeluar->fetch_assoc()) {
            $prevKeluar[$row['barang_id']] = $row['totalKeluarPrev'];
        }
    }
    
} else {
    // Untuk filter selain year_month (periode, year, atau tanpa filter)
    $whereClauses = [];
    if ($filterType == 'periode' && $periodeFilter != '') {
        $whereClauses[] = "lb.periode = '" . $conn->real_escape_string($periodeFilter) . "'";
    } elseif ($filterType == 'year' && $yearFilter != '') {
        $whereClauses[] = "YEAR(lb.periode) = '" . $conn->real_escape_string($yearFilter) . "'";
    }
    if ($kategoriFilter != '') {
        $whereClauses[] = "b.kategori = '" . $conn->real_escape_string($kategoriFilter) . "'";
    }
    if ($barangSearch != '') {
        $whereClauses[] = "b.nama_barang = '" . $conn->real_escape_string($barangSearch) . "'";
    }
    $whereSQL = "";
    if (count($whereClauses) > 0) {
        $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
    }
    
    $sqlCurrent = "SELECT 
            b.id, 
            b.kategori, 
            b.nama_barang, 
            b.unit, 
            lb.banyak, 
            lb.periode, 
            lv.afd1, lv.afd2, lv.afd3, lv.afd4, lv.afd5, 
            lv.traksi, lv.bibitan, lv.lainnya,
            b.keterangan 
        FROM barang b
        LEFT JOIN log_barang lb ON lb.barang_id = b.id"
        . $whereSQL . " ORDER BY b.id ASC";
    
    $resultCurrent = $conn->query($sqlCurrent);
    // Tidak ada perhitungan stock akhir bulan lalu khusus
    $prevMasuk = $prevKeluar = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Gudang - Bumi Flora</title>
  <!-- Bootstrap & Select2 CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- Sidebar & Header tetap sama -->
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
    <!-- Form Filter -->
    <div class="container mt-5">
      <section id="daftargudang">
        <h2>Daftar Barang</h2>
        <form method="get" action="daftargudang.php">
          <div class="filter-container">
            <table class="filter-table">
              <tr class="filter-periode">
                <td>
                  <input type="radio" id="periode-select" name="filter-type" value="periode" <?= ($filterType=='periode') ? 'checked' : '' ?>>
                  <label for="periode-select">Harian:</label>
                </td>
                <td class="input-cell">
                  <select name="periode" id="periode">
                    <option value="">Pilih Harian</option>
                    <?php
                      if ($resultPeriode) {
                        while ($row = $resultPeriode->fetch_assoc()) {
                          $selectedOpt = ($filterType=='periode' && $periodeFilter == $row['periode']) ? 'selected' : '';
                          echo "<option value='" . $row['periode'] . "' $selectedOpt>" . $row['periode'] . "</option>";
                        }
                      }
                    ?>
                  </select>
                </td>
              </tr>
              <tr class="filter-year">
                <td>
                  <input type="radio" id="year-input" name="filter-type" value="year" <?= ($filterType=='year') ? 'checked' : '' ?>>
                  <label for="year-input">Tahun:</label>
                </td>
                <td class="input-cell">
                  <input type="number" id="year" name="year" min="2000" max="2100" value="<?= ($filterType=='year') ? $yearFilter : '' ?>">
                </td>
              </tr>
              <tr class="filter-year-month">
                <td>
                  <input type="radio" id="year-month-input" name="filter-type" value="year_month" <?= ($filterType=='year_month') ? 'checked' : '' ?>>
                  <label for="year-month-input">Bulan:</label>
                </td>
                <td class="input-cell">
                  <input type="month" id="year_month" name="year_month" value="<?= ($filterType=='year_month') ? $yearMonthFilter : date('Y-m') ?>">
                </td>
              </tr>
              <tr>
                <td>
                  <label for="kategori" class="kategori-label">Kategori Barang:</label>
                </td>
                <td>
                  <select name="kategori" id="kategori">
                    <option value="">Pilih Kategori Barang</option>
                    <?php
                      $resultKategori2 = $conn->query("SELECT DISTINCT kategori FROM barang");
                      while ($row = $resultKategori2->fetch_assoc()) {
                        $selectedCat = ($kategoriFilter == $row['kategori']) ? 'selected' : '';
                        echo "<option value='" . $row['kategori'] . "' $selectedCat>" . $row['kategori'] . "</option>";
                      }
                    ?>
                  </select>
                </td>
              </tr>
            </table>
            <div class="filter-actions">
              <button type="submit" class="btn btn-success">Filter</button>
              <div class="search-container">
                <select id="barang44" name="barang44">
                  <option value="" disabled selected>Search Nama Barang</option>
                  <?php foreach ($namaBarangList44 as $barang4) { ?>
                    <option value="<?= htmlspecialchars($barang4) ?>"><?= htmlspecialchars($barang4) ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
        </form>
        
        <!-- Tabel Data -->
        <h2>Data Stock Barang</h2>
        <div class="table-container">
          <table id="stockTable" class="table table-bordered">
            <thead>
              <tr>
                <th rowspan="2">Kategori</th>
                <th rowspan="2" style="min-width: 240px;">Nama Barang</th>
                <th rowspan="2">Satuan</th>
                <?php if ($filterType=='year_month' && $yearMonthFilter != ''): ?>
                  <th rowspan="2">Stock Bulan Lalu</th>
                <?php endif; ?>
                <th colspan="2">Barang Masuk</th>
                <th colspan="8">Barang Keluar</th>
                <th rowspan="2" style="min-width: 200px;">Keterangan</th>
                <th rowspan="2">Total Keluar</th>
                <th rowspan="2">Stock Akhir</th>
                <th rowspan="2" style="min-width: 70px;">Aksi</th>
              </tr>
              <tr>
                <th>QTY</th>
                <th>Tgl</th>
                <th>AFD 1</th>
                <th>AFD 2</th>
                <th>AFD 3</th>
                <th>AFD 4</th>
                <th>AFD 5</th>
                <th>Traksi</th>
                <th>Bibitan</th>
                <th>Lainnya</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($resultCurrent && $resultCurrent->num_rows > 0) {
                  while ($row = $resultCurrent->fetch_assoc()) {
                      $kategori   = $row['kategori'];
                      $namaBarang = $row['nama_barang'];
                      $satuan     = $row['unit'];
                      $barangMasukQty = isset($row['banyak']) ? $row['banyak'] : 0;
                      $periode    = isset($row['periode']) ? $row['periode'] : '';
      
                      $afd1    = isset($row['afd1']) ? $row['afd1'] : 0;
                      $afd2    = isset($row['afd2']) ? $row['afd2'] : 0;
                      $afd3    = isset($row['afd3']) ? $row['afd3'] : 0;
                      $afd4    = isset($row['afd4']) ? $row['afd4'] : 0;
                      $afd5    = isset($row['afd5']) ? $row['afd5'] : 0;
                      $traksi  = isset($row['traksi']) ? $row['traksi'] : 0;
                      $bibitan = isset($row['bibitan']) ? $row['bibitan'] : 0;
                      $lainnya = isset($row['lainnya']) ? $row['lainnya'] : 0;
                      $keterangan = isset($row['keterangan']) ? $row['keterangan'] : '';
      
                      $totalKeluar = $afd1 + $afd2 + $afd3 + $afd4 + $afd5 + $traksi + $bibitan + $lainnya;
      
                      if ($filterType=='year_month' && $yearMonthFilter != '') {
                          // Ambil total masuk dan keluar dari bulan sebelumnya berdasarkan id barang
                          $idBarang = $row['id'];
                          $totalMasukPrev = isset($prevMasuk[$idBarang]) ? $prevMasuk[$idBarang] : 0;
                          $totalKeluarPrev = isset($prevKeluar[$idBarang]) ? $prevKeluar[$idBarang] : 0;
                          // Hitung stock akhir bulan lalu: (total masuk prev) - (total keluar prev)
                          $stockAkhirBulanLalu = $totalMasukPrev - $totalKeluarPrev;
                      }
      
                      // Total stock = stock bulan lalu + barang masuk bulan ini - total digunakan (keluar bulan ini)
                      $stockAkhir = ($filterType=='year_month' && $yearMonthFilter != '') 
                                    ? $stockAkhirBulanLalu + $barangMasukQty - $totalKeluar
                                    : $barangMasukQty - $totalKeluar;
      
                      echo "<tr>";
                      echo "<td>$kategori</td>";
                      echo "<td>$namaBarang</td>";
                      echo "<td>$satuan</td>";
                      if ($filterType=='year_month' && $yearMonthFilter != '') {
                        echo "<td>$stockAkhirBulanLalu</td>";
                      }
                      echo "<td>$barangMasukQty</td>";
                      echo "<td>$periode</td>";
                      echo "<td>$afd1</td>";
                      echo "<td>$afd2</td>";
                      echo "<td>$afd3</td>";
                      echo "<td>$afd4</td>";
                      echo "<td>$afd5</td>";
                      echo "<td>$traksi</td>";
                      echo "<td>$bibitan</td>";
                      echo "<td>$lainnya</td>";
                      echo "<td>$keterangan</td>";
                      echo "<td>$totalKeluar</td>";
                      echo "<td>$stockAkhir</td>";
                      echo "<td>
                              <a href='edit.php?id=" . $row['id'] . "&periode=" . $periode . "&banyak=" . $barangMasukQty . "'>
                                  <img src='img/pencil-fill.svg' alt='edit' style='width:20px; height:20px;'>
                              </a>
                              <a href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus barang ini?')\">
                                  <img src='img/trash-fill.svg' alt='Delete' style='width:20px; height:20px;'>
                              </a>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='17'>Tidak ada data</td></tr>";
              }
              $conn->close();
              ?>
            </tbody>
          </table>
        </div>
      </section>
      
      <!-- Tombol Cetak PDF -->
      <div id="cetak-pdf-btn" style="margin: 20px 0; text-align: center;">
        <form action="cetak-pdf.php" method="GET">
          <input type="hidden" name="filter-type" value="<?= htmlspecialchars($filterType) ?>">
          <input type="hidden" name="periode" value="<?= htmlspecialchars($periodeFilter) ?>">
          <input type="hidden" name="year" value="<?= htmlspecialchars($yearFilter) ?>">
          <input type="hidden" name="year_month" value="<?= htmlspecialchars($yearMonthFilter) ?>">
          <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategoriFilter) ?>">
          <input type="hidden" name="barang44" value="<?= htmlspecialchars($barangSearch) ?>">
          <button type="submit" class="btn btn-success">Cetak PDF</button>
        </form>
      </div>
    </div>
    
    <!-- Footer -->
    <footer>
      <div class="container">
        <p>&copy; 2025 Bumi Flora. All rights reserved.</p>
      </div>
    </footer>
  </div>
  
  <!-- JS Bootstrap & Select2 serta script tambahan -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#barang44').select2({ placeholder: "Search Nama Barang", allowClear: true, width: '100%' });
      $('#periode').select2({ placeholder: "Pilih Harian", allowClear: true, width: '100%' });
      $('#kategori').select2({ placeholder: "Pilih Kategori Barang", allowClear: true, width: '100%' });
      function toggleFilterInputs() {
        var periodeRadio = document.getElementById("periode-select");
        var yearRadio = document.getElementById("year-input");
        var yearMonthRadio = document.getElementById("year-month-input");
  
        var periodeRow = document.querySelector(".filter-periode");
        var yearRow = document.querySelector(".filter-year");
        var yearMonthRow = document.querySelector(".filter-year-month");
  
        periodeRow.cells[1].style.display = periodeRadio.checked ? "table-cell" : "none";
        yearRow.cells[1].style.display = yearRadio.checked ? "table-cell" : "none";
        yearMonthRow.cells[1].style.display = yearMonthRadio.checked ? "table-cell" : "none";
      }
      var radios = document.querySelectorAll('input[name="filter-type"]');
      radios.forEach(function(radio) {
        radio.addEventListener("change", toggleFilterInputs);
      });
      toggleFilterInputs();
    });
  
    document.getElementById('toggle-btn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });
  </script>
</body>
</html>
