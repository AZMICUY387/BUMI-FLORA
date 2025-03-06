<?php
// proses_kendaraan.php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nama_kendaraan"]) && isset($_POST["nama_barang"])) {
    $nama_kendaraan = trim($_POST["nama_kendaraan"]);
    $nama_barang    = trim($_POST["nama_barang"]);
    $banyak         = intval($_POST["banyak"]);
    $tanggal        = date("Y-m-d");

    // Cek apakah barang tersedia
    $stmt = $conn->prepare("SELECT id, banyak FROM barang WHERE nama_barang = ?");
    $stmt->bind_param("s", $nama_barang);
    $stmt->execute();
    $result = $stmt->get_result();
    $barang = $result->fetch_assoc();

    if (!$barang) {
        die("Error: Barang tidak ditemukan.");
    }

    $barang_id  = $barang['id'];
    $stok_barang = $barang['banyak'];

    if ($stok_barang < $banyak) {
        die("Error: Stok barang tidak cukup.");
    }

    // Cek apakah kendaraan sudah ada di database
    $stmt = $conn->prepare("SELECT id FROM vehicle WHERE nama_kendaraan = ?");
    $stmt->bind_param("s", $nama_kendaraan);
    $stmt->execute();
    $result = $stmt->get_result();
    $kendaraan = $result->fetch_assoc();

    if (!$kendaraan) {
        // Jika kendaraan belum ada, tambahkan ke tabel vehicle
        $stmt = $conn->prepare("INSERT INTO vehicle (nama_kendaraan) VALUES (?)");
        $stmt->bind_param("s", $nama_kendaraan);
        $stmt->execute();
        $vehicle_id = $stmt->insert_id;
    } else {
        $vehicle_id = $kendaraan['id'];
    }

    // Catat ke log_vehicle
    $stmt = $conn->prepare("INSERT INTO log_vehicle (vehicle_id, barang_id, quantity, periode) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $vehicle_id, $barang_id, $banyak, $tanggal);
    $stmt->execute();

    // Update stok barang
    $new_stok = $stok_barang - $banyak;
    $stmt = $conn->prepare("UPDATE barang SET banyak = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_stok, $barang_id);
    $stmt->execute();

    echo "Data kendaraan berhasil ditambahkan!";
    $stmt->close();
} else {
    echo "Invalid request.";
}
$conn->close();
?>
