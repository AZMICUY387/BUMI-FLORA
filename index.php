<?php
// index.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Bumi Flora</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Style Umum */
    body {
      overflow-x: hidden;
      background: url('img/theme.jpg') no-repeat center center fixed;
      background-size: cover;
      filter: grayscale(50%);
      font-family: Arial, sans-serif;
    }

    .label {
      padding: 15px;
      border-bottom: 1px solid rgba(17, 8, 8, 0.2);
      margin-top: 17%;
      margin-bottom: 5%;
    }
    .label {
      padding: 15px;
      border-bottom: 1px solid rgba(17, 8, 8, 0.2);
      margin-top: 17%;
      margin-bottom: 5%;
    }
    .label a {
      color: rgb(5, 10, 5);
      font-size: 150%;
      text-decoration: none;
    }
    .label a:hover{
      color: #fff;
      transition: color 0.5s ease;
    }
    p {
      color: #fff;
    }
    h1, h2, h3, h4, h5, h6 {
      color: #32CD32;
      text-align: center;
    }
    .hero {
      padding: 200px 20px;
      text-align: center;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 20px;
    }
    .hero p {
      font-size: 1.5rem;
      font-weight: 300;
    }
    .hero .cta {
      font-size: 1.2rem;
      font-weight: 800;
      background: #27ae60;
      border: none;
      border-radius: 30px;
      color: #fff;
      transition: background 0.3s;
    }
    .hero .cta:hover {
      background: #2ecc71;
    }
    /* Letakkan CSS .typewriter di sini atau di file terpisah */
    .typewriter {
      display: inline-block;
      overflow: hidden;
      white-space: nowrap;
      border-right: 2px solid #fff;
      font-size: 2.5rem;
      font-weight: 800;
      width: 10ch;
      animation: typing 4s steps(21, end) infinite, blinkCaret 0.5s step-end infinite;
    }
    @keyframes typing {
      0%   { width: 0; }
      50%  { width: 10ch; }
      100% { width: 0; }
    }
    @keyframes blinkCaret {
      50% { border-color: transparent; }
    }
    /* Sidebar dengan warna #32CD32 */
    #sidebar {
      position: fixed;
      top: 0;
      left: -250px;
      width: 250px;
      height: 100%;
      background-color: #32CD32;
      transition: left 0.3s ease;
      padding-top: 60px;
      border-bottom: 1px solid rgba(17, 8, 8, 0.2);
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
      text-align: left;
      border-bottom: 1px solid rgba(17, 8, 8, 0.2);
    }
    #sidebar ul li a {
      color: #fff;
      text-decoration: none;
      display: block;
      transition: color 0.5s;
      font-size: 110%;
    }
    #sidebar ul li a:hover {
      color: rgb(5, 10, 5);
    }
    #content {
      transition: margin-left 0.3s ease, width 0.3s ease;
      margin-left: 0;
      width: 100%;
      color: #fff;
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
    /* Footer */
    footer {
      background-color: transparent;
      color: white;
      padding: 10px 0;
      text-align: center;
      width: 100%;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <!-- Tombol Toggle -->
  <button class="toggle-btn" id="toggle-btn">Menu</button>
  
  <!-- Sidebar -->
  <div id="sidebar">
  <div class="label">
      <img src="img/Logo.png" alt="logo" style="width: 50px;">
      <label><a href="index.php">Bumi Flora</a></label>
    </div>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="daftargudang.php">Gudang Belakang</a></li>
      <li><a href="inventaris/sistem.php">Inventaris</a></li>
    </ul>
  </div>
  
  <!-- Konten Utama -->
  <div id="content" class="container-fluid">
    <div class="row">
      <div class="col-12 mt-5">
      <div class="container hero">
        <h1 style="margin-bottom: 8%;">Welcome to <span class="typewriter">Bumi Flora</span></h1>
        <p> Panduan:</p>
        <h3 style="color: white;">Gunakan menu di <strong style="color: #32CD32;">sidebar</strong> untuk mengakses halaman <strong style="color: #32CD32;">Gudang Belakang</strong> dan <strong>Inventaris</strong>.  
            Klik tombol <strong style="color: #32CD32;">Menu</strong> di pojok kiri atas untuk menampilkan atau menyembunyikan sidebar.  
            Setiap halaman menyediakan fitur dan informasi Bumi Flora.</h3>
        <p></p>
      </div>
    </div>
      </div>
    </div>
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
  
  <!-- Script Toggle Sidebar dan Typewriter Effect -->
  <script>
    // Toggle sidebar
    document.getElementById('toggle-btn').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });
    
  </script>
</body>
</html>
