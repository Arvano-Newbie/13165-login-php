<?php

ob_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = htmlspecialchars(trim($_POST['username']));
    $password_plaintext = $_POST['password'];
    $nama_lengkap = htmlspecialchars(trim($_POST['nama_lengkap']));

    $password_hashed = password_hash($password_plaintext, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, nama_lengkap) VALUES (?, ?, ?)";

    if (isset($koneksi) && $stmt = $koneksi->prepare($sql)) {
        
        $stmt->bind_param("sss", $username, $password_hashed, $nama_lengkap);

        if ($stmt->execute()) {
            
            ob_clean(); 
            header("Location: index.php");
            exit(); 
            
        } else {
            echo "Registrasi gagal. Coba username lain.";
        }

        $stmt->close();
    } else {
        echo "Error sistem: Koneksi database atau persiapan statement gagal.";
    }

} else {
    echo "Akses ditolak.";
}

if (isset($koneksi)) {
    $koneksi->close();
}

ob_end_flush();

?>