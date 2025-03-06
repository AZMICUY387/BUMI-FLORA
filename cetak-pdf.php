<?php
// cetak_pdf.php

require('fpdf.php'); // Pastikan FPDF sudah di-include
include 'koneksi.php';

// Ambil parameter filter dari GET (jika ada)
$filterType      = isset($_GET['filter-type']) ? $_GET['filter-type'] : '';
$periodeFilter   = isset($_GET['periode']) ? $_GET['periode'] : '';
$yearFilter      = isset($_GET['year']) ? $_GET['year'] : '';
$yearMonthFilter = isset($_GET['year_month']) ? $_GET['year_month'] : '';
$kategoriFilter  = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$barangSearch    = isset($_GET['barang44']) ? $_GET['barang44'] : '';

// Bangun kondisi WHERE berdasarkan filter yang dipilih
$whereClauses = [];
if ($filterType == 'periode' && $periodeFilter != '') {
    $whereClauses[] = "b.periode = '" . $conn->real_escape_string($periodeFilter) . "'";
} elseif ($filterType == 'year' && $yearFilter != '') {
    $whereClauses[] = "YEAR(b.periode) = '" . $conn->real_escape_string($yearFilter) . "'";
} elseif ($filterType == 'year_month' && $yearMonthFilter != '') {
    $whereClauses[] = "DATE_FORMAT(b.periode, '%Y-%m') = '" . $conn->real_escape_string($yearMonthFilter) . "'";
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

// Query utama untuk mengambil data barang
$sql = "SELECT b.*, 
    IFNULL(bh.stock, b.stock_bulan_lalu) as stock_history,
    (SELECT IFNULL(SUM(lv.quantity), 0) FROM log_vehicle lv 
       JOIN vehicle v ON lv.vehicle_id = v.id 
       WHERE lv.barang_id = b.id AND v.nama_kendaraan = 'AFD1') AS afd1,
    (SELECT IFNULL(SUM(lv.quantity), 0) FROM log_vehicle lv 
       JOIN vehicle v ON lv.vehicle_id = v.id 
       WHERE lv.barang_id = b.id AND v.nama_kendaraan = 'AFD2') AS afd2,
    (SELECT IFNULL(SUM(lv.quantity), 0) FROM log_vehicle lv 
       JOIN vehicle v ON lv.vehicle_id = v.id 
       WHERE lv.barang_id = b.id AND v.nama_kendaraan = 'AFD3') AS afd3,
    (SELECT IFNULL(SUM(lv.quantity), 0) FROM log_vehicle lv 
       JOIN vehicle v ON lv.vehicle_id = v.id 
       WHERE lv.barang_id = b.id AND v.nama_kendaraan = 'AFD4') AS afd4,
    (SELECT IFNULL(SUM(lv.quantity), 0) FROM log_vehicle lv 
       JOIN vehicle v ON lv.vehicle_id = v.id 
       WHERE lv.barang_id = b.id AND v.nama_kendaraan = 'AFD5') AS afd5
FROM barang b
LEFT JOIN barang_history bh ON bh.barang_id = b.id AND bh.periode = 'Januari' "
. $whereSQL . " ORDER BY b.id ASC";

$result = $conn->query($sql);

// Inisialisasi PDF dengan orientasi Landscape (untuk ruang yang lebih lebar)
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// Header PDF
// Logo (pastikan path logo sesuai, misalnya di folder img/)
$logoPath = 'img/Logo.png';
if(file_exists($logoPath)) {
    $pdf->Image($logoPath, 10, 8, 20); // (x, y, width)
}
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Bumi Flora', 0, 1, 'C');

// Tanggal Cetak
$pdf->SetFont('Arial', '', 10);
date_default_timezone_set('Asia/Jakarta');
$pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d-m-Y H:i:s'), 0, 1, 'C');
$pdf->Ln(8);

// Judul Laporan di tengah
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Daftar Barang Gudang Belakang', 0, 1, 'C');
$pdf->Ln(2);

// Set header tabel
$pdf->SetFont('Arial', 'B', 8);
$header = [
    'Kategori', 'Nama Barang', 'Satuan', 'Stock Bulan Lalu', 'Tgl', 'Qty',
    'AFD1', 'AFD2', 'AFD3', 'AFD4', 'AFD5', 'Traksi', 'Bibitan', 'Lainnya',
    'Keterangan', 'Total Keluar', 'Stock Akhir'
];
// Lebar kolom dalam mm (harus sesuai dengan jumlah header)
$w = [24, 40, 12, 24, 14, 10, 10, 10, 10, 10, 10, 12, 12, 12, 30, 18, 18];

// Cetak header
for($i = 0; $i < count($header); $i++){
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
}
$pdf->Ln();

function NbLines($pdf, $w, $txt) {
    $lines = explode("\n", $txt);
    $total = 0;
    foreach ($lines as $line) {
        if(trim($line) == '') {
            $total++;
        } else {
            $lineWidth = $pdf->GetStringWidth($line);
            $lineCount = ceil($lineWidth / $w);
            $total += $lineCount;
        }
    }
    return $total;
}

// Set font untuk data tabel
$pdf->SetFont('Arial', '', 7);
$lineHeight = 6; // tinggi baris dasar

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $kategori       = $row['kategori'];
        $namaBarang     = $row['nama_barang'];
        $satuan         = $row['unit'];
        $stockBulanLalu = $row['stock_history'];
        $periode        = $row['periode'];
        $barangMasukQty = $row['banyak'];
        
        $afd1 = $row['afd1'];
        $afd2 = $row['afd2'];
        $afd3 = $row['afd3'];
        $afd4 = $row['afd4'];
        $afd5 = $row['afd5'];
        
        // Default kolom tambahan
        $traksi  = 0;
        $bibitan = 0;
        $lainnya = 0;
        
        $totalKeluar = $afd1 + $afd2 + $afd3 + $afd4 + $afd5 + $traksi + $bibitan + $lainnya;
        $stockAkhir = $stockBulanLalu + $barangMasukQty - $totalKeluar;
        
        // Keterangan (jika ada)
        $keterangan = isset($row['keterangan']) ? $row['keterangan'] : '';
        
        // Data baris, urut sesuai header
        $data = [
            $kategori, $namaBarang, $satuan, $stockBulanLalu, $periode, $barangMasukQty,
            $afd1, $afd2, $afd3, $afd4, $afd5, $traksi, $bibitan, $lainnya,
            $keterangan, $totalKeluar, $stockAkhir
        ];
        
        // Hitung jumlah baris untuk kolom "Keterangan" (index ke-14)
        $nbLines = NbLines($pdf, $w[14], $data[14]);
        // Tentukan tinggi baris untuk baris ini
        $rowHeight = $lineHeight * $nbLines;
        
        // Simpan posisi awal
        $xStart = $pdf->GetX();
        $yStart = $pdf->GetY();
        
        // Cetak tiap sel
        for ($i = 0; $i < count($data); $i++) {
            $pdf->SetXY($xStart, $yStart);
            if ($i == 14) {
                // Gunakan MultiCell untuk kolom Keterangan agar teks dibungkus
                $pdf->MultiCell($w[$i], $lineHeight, $data[$i], 1, 'C');
                // Setelah MultiCell, posisi X berpindah ke kanan sebanyak lebar kolom tersebut
                $xStart += $w[$i];
            } else {
                $pdf->Cell($w[$i], $rowHeight, $data[$i], 1, 0, 'C');
                $xStart += $w[$i];
            }
        }
        $pdf->Ln($rowHeight);
    }
} else {
    $pdf->Cell(array_sum($w), 6, 'Tidak ada data.', 1, 0, 'C');
}

$pdf->Output('I', 'Laporan Gudang Bumi Flora.pdf');
$conn->close();
?>
