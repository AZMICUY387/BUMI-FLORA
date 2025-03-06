<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gudang Belakang - Bumi Flora</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css">
  <style>
    
  </style>
</head>
<body>
  <div id="sidebar">
    <div class="label">
      <img src="img/Logo.png" alt="logo" style="width: 50px;">
      <label><a href="index.php">Bumi Flora</a></label>
    </div>
    <ul>
      <li><a href="#inputbarang">Input Barang</a></li>
      <li><a href="#daftargudang">Daftar Gudang</a></li>
      <li><a href="#tambahkendaraan">Tambah Kendaraan</a></li>
    </ul>
  </div>
  <div id="content" class="container-fluid">
    <div class="row">
      <div class="container hero">
        <h1>Welcome to <span class="typewriter">Bumi Flora</span></h1>
        <p>Di mana inovasi dan keindahan bersatu, menciptakan masa depan yang lebih cerah.</p>
      </div>
    </div>
    <button class="toggle-btn" id="toggle-btn">Menu</button>
    
    
    
    <!-- Footer -->
    <footer>
      <div class="container">
        <p>&copy; 2025 <strong style="color: #32CD32;">Bumi Flora</storng>. All rights reserved.</p>
      </div>
    </footer>
  </div>
  
  <!-- JS Bootstrap, jQuery, dan Select2 JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // Inisialisasi select2 untuk search nama barang
      $('#barang44').select2({
        placeholder: "Search Nama Barang",
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true
      });
    });

    $(document).ready(function() {
        $('#unit').select2({
          placeholder: "Pilih Opsi",
          allowClear: true,
          width: '100%',
          dropdownAutoWidth: true
        });
    });

    $(document).ready(function() {
      $('#kategori').select2({
          placeholder: "Pilih Kategori",
          allowClear: true,
          width: '100%',
          dropdownAutoWidth: true
        });
    });

    $(document).ready(function() {
      $('#NamaBarang').select2({
          placeholder: "Pilih Barang",
          allowClear: true,
          width: '100%',
          dropdownAutoWidth: true
        });
    });
    
    document.addEventListener("DOMContentLoaded", function() {
      function toggleFilterInputs() {
        var periodeRadio = document.getElementById("periode-select");
        var yearRadio = document.getElementById("year-input");
        var yearMonthRadio = document.getElementById("year-month-input");

        var periodeRow = document.querySelector(".filter-periode");
        var yearRow = document.querySelector(".filter-year");
        var yearMonthRow = document.querySelector(".filter-year-month");

        // Hanya tampilkan input sel pada baris yang radio-nya terpilih, sisanya disembunyikan.
        periodeRow.cells[1].style.display = periodeRadio.checked ? "table-cell" : "none";
        yearRow.cells[1].style.display = yearRadio.checked ? "table-cell" : "none";
        yearMonthRow.cells[1].style.display = yearMonthRadio.checked ? "table-cell" : "none";
      }
      var radios = document.querySelectorAll('input[name="filter-type"]');
      radios.forEach(function(radio) {
        radio.addEventListener("change", toggleFilterInputs);
      });
      // Inisialisasi tampilan input
      toggleFilterInputs();
    });
    
    // Toggle sidebar
    document.getElementById('toggle-btn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });

  // Pastikan script ini dijalankan setelah DOM termuat
  document.addEventListener('DOMContentLoaded', () => {
  const slider = document.querySelector('.table-container');
  if (!slider) return;

  let isDown = false;
  let startX = 0;
  let scrollLeft = 0;

  // Mulai drag saat pointer ditekan (klik kiri)
  slider.addEventListener('pointerdown', e => {
    if (e.button !== 0) return; // pastikan hanya tombol kiri
    isDown = true;
    slider.classList.add('active');
    startX = e.clientX;
    scrollLeft = slider.scrollLeft;
    // Tangkap pointer agar kita terus menerima event meskipun pointer keluar dari elemen
    slider.setPointerCapture(e.pointerId);
  });

  // Geser scroll horizontal saat pointer bergerak
  slider.addEventListener('pointermove', e => {
    if (!isDown) return;
    const dx = e.clientX - startX;
    slider.scrollLeft = scrollLeft - dx;
  });

  // Lepaskan drag saat pointer diangkat
  slider.addEventListener('pointerup', e => {
    isDown = false;
    slider.classList.remove('active');
    slider.releasePointerCapture(e.pointerId);
  });

  // Pastikan jika pointer dibatalkan (misal keluar dari area) kita berhenti drag
  slider.addEventListener('pointercancel', e => {
    isDown = false;
    slider.classList.remove('active');
    slider.releasePointerCapture(e.pointerId);
  });
});
  </script>
</body>
</html>
