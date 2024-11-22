<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sistem_pakar_penyakit";

$conn = mysqli_connect(hostname : $host, username : $user, password : $password);

if (!$conn) {
    die("koneksi gagal: " . mysqli_connect_error());
}

?>