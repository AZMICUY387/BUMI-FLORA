<?php
// inventaris.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventaris - Bumi Flora</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      overflow-x: hidden;
      background: url('https://via.placeholder.com/1920x1080') no-repeat center center fixed;
      background-size: cover;
      filter: grayscale(50%);
      font-family: Arial, sans-serif;
    }

    .label {
      padding: 15px;
      border-bottom: 1px solid rgba(17, 8, 8, 0.2);
      margin-top: 15%;
      margin-bottom: 5%;
    }
    .label a {
      color: #fff;
      text-decoration: none;
    }
    .label a:hover{
      color:rgb(5, 10, 5);
      transition: color 0.3s ease;
    }
    #sidebar {
      position: fixed;
      top: 0;
      left: -250px;
      width: 250px;
      height: 100%;
      background-color: #32CD32;
      transition: left 0.3s ease;
      padding-top: 60px;
      box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    #sidebar.active {
      left: 0;
    }
    #sidebar ul {
      list-style: none;
      padding: 0;
    }
    #sidebar ul li {
      padding: 15px;
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    #sidebar ul li a {
      color: #fff;
      text-decoration: none;
      display: block;
      transition: background 0.3s;
    }
    #sidebar ul li a:hover {
      background-color: #2eb82e;
    }
    #content {
      transition: margin-left 0.3s ease, width 0.3s ease;
      margin-left: 0;
      width: 100%;
    }
    #content.active {
      margin-left: 250px;
      width: calc(100% - 250px);
    }
    .toggle-btn {
      position: fixed;
      left: 10px;
      top: 10px;
      background-color: #32CD32;
      border: none;
      color: #fff;
      padding: 10px 15px;
      cursor: pointer;
      z-index: 1000;
      border-radius: 4px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    h1, h2, h3, h4, h5, h6 {
      color: #32CD32;
    }
    p {
      color: #333;
    }
    /* Footer */
    footer {
      background-color: #32CD32;
      color: white;
      padding: 10px 0;
      text-align: center;
      width: 100%;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <button class="toggle-btn" id="toggle-btn">Menu</button>
  <div id="sidebar">
  <label class="label"><a href="index.php">Bumi Flora</a></label>
    <ul>
      <li><a href="inventaris.php">Home</a></li>
      <li><a href="#keuangan">Inventaris Keuangan</a></li>
    </ul>
  </div>
  <div id="content" class="container-fluid">
    <div class="row">
      <div class="col-12 mt-5">
        <h1 class="text-center">Inventaris</h1>
        <p class="text-center">Selamat datang di halaman Inventaris Bumi Flora.</p>
      </div>
    </div>
    
    <!-- Contoh gambar dengan efek grayscale -->
    <div class="text-center my-4">
      <img src="https://via.placeholder.com/800x400" class="img-fluid grayscale" alt="Contoh Gambar Inventaris">
    </div>
    
    <!-- Section: Inventaris Keuangan -->
    <section id="keuangan" class="mt-5">
      <h2>Inventaris Keuangan Kantor</h2>
      <p>Berikut adalah data keuangan kantor yang terintegrasi dengan sistem inventaris:</p>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Kode Akun</th>
            <th>Deskripsi</th>
            <th>Saldo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>101</td>
            <td>Kas</td>
            <td>Rp 50.000.000</td>
          </tr>
          <tr>
            <td>102</td>
            <td>Bank</td>
            <td>Rp 100.000.000</td>
          </tr>
          <!-- Baris data lain dapat ditambahkan -->
        </tbody>
      </table>
    </section>
  </div>
  
  <!-- Footer -->
  <footer>
    <div class="container">
      <p>&copy; 2025 Bumi Flora. All rights reserved.</p>
    </div>
  </footer>
  
  <!-- JS Bootstrap dan jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // Toggle sidebar
    document.getElementById('toggle-btn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });
  </script>
</body>
</html>
