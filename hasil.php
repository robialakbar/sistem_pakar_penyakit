<?php
require_once 'koneksi.php';

// Fungsi untuk mengambil data gejala dari database
function getGejala($conn) {
    $sql = "SELECT * FROM gejala";
    $result = mysqli_query($conn, $sql);
    $gejala = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $gejala[] = $row;
    }
    return $gejala;
}

// Fungsi untuk mengambil data penyakit dari database
function getPenyakit($conn) {
    $sql = "SELECT * FROM penyakit";
    $result = mysqli_query($conn, $sql);
    $penyakit = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $penyakit[] = $row;
    }
    return $penyakit;
}

// Fungsi untuk mengambil data pengetahuan (rule) dari database
function getPengetahuan($conn) {
    $sql = "SELECT p.kode_penyakit, p.nama_penyakit, g.id_gejala, g.nama_gejala, pg.mb, pg.md 
            FROM pengetahuan pg
            JOIN penyakit p ON pg.kode_penyakit = p.kode_penyakit
            JOIN gejala g ON pg.id_gejala = g.id_gejala";
    $result = mysqli_query($conn, $sql);
    $pengetahuan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pengetahuan[] = $row;
    }
    return $pengetahuan;
}

// Fungsi untuk menghitung CF gabungan
function hitungCFGabungan($cf1, $cf2) {
    if ($cf1 >= 0 && $cf2 >= 0) {
        return $cf1 + $cf2 * (1 - $cf1);
    } elseif ($cf1 < 0 && $cf2 < 0) {
        return $cf1 + $cf2 * (1 + $cf1);
    } else {
        return ($cf1 + $cf2) / (1 - min(abs($cf1), abs($cf2)));
    }
}

// Inisialisasi variabel
$gejalaTerpilih = isset($_POST['gejala']) ? $_POST['gejala'] : [];
$cfPenyakit = [];

// Proses diagnosa jika ada gejala yang dipilih
if (!empty($gejalaTerpilih)) {
    $gejala = getGejala($conn);
    $penyakit = getPenyakit($conn);
    $pengetahuan = getPengetahuan($conn);

    // Forward chaining
    foreach ($penyakit as $p) {
        $cfPenyakit[$p['kode_penyakit']] = 0;
        foreach ($pengetahuan as $pg) {
            if ($pg['kode_penyakit'] == $p['kode_penyakit'] && in_array($pg['id_gejala'], $gejalaTerpilih)) {
                // Hitung CF untuk setiap rule
                $cfRule = $pg['mb'] - $pg['md'];
                // Gabungkan CF dengan CF penyakit yang sudah ada
                $cfPenyakit[$p['kode_penyakit']] = hitungCFGabungan($cfPenyakit[$p['kode_penyakit']], $cfRule);
            }
        }
    }
}
// Tampilkan form gejala dan hasil diagnosa
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Diagnosa Penyakit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
  <?php if (!empty($gejalaTerpilih)): ?>
      <h3 class="mt-4">Hasil Diagnosa :</h3>
      <table class="table table-striped">
          <thead class="bg-primary">
              <tr>
                  <th>Kode Penyakit</th>
                  <th>Nama Penyakit</th>
                  <th>Solusi</th>
                  <th>Nilai CF</th>
                  <th>Persentase</th>
              </tr>
          </thead>
          <tbody>
              <?php 
              foreach ($cfPenyakit as $kodePenyakit => $cf): ?>
                  <tr>
                      <td><?php echo $kodePenyakit; ?></td>
                      <td><?php echo $penyakit[array_search($kodePenyakit, array_column($penyakit, 'kode_penyakit'))]['nama_penyakit']; ?></td>
                      <td><?php echo $penyakit[array_search($kodePenyakit, array_column($penyakit, 'kode_penyakit'))]['saran']; ?></td>
                      <td><?php echo $cf; ?></td>
                      <td><b><?php echo $cf * 100; ?>%</b></td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
      <div class="d-grid gap-2">
				<a href="index.php" class="btn btn-warning">Kembali</a>
			</div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>