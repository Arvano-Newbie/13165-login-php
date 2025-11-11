<?php

session_start();
ob_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username_input = htmlspecialchars(trim($_POST['username']));
    $password_input = $_POST['password'];

    $sql = "SELECT username, password, nama_lengkap FROM users WHERE username = ?";

    if (isset($koneksi) && $stmt = $koneksi->prepare($sql)) {
        
        $stmt->bind_param("s", $username_input);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            
            $user_data = $result->fetch_assoc();
            $password_hashed = $user_data['password'];

            if (password_verify($password_input, $password_hashed)) {
                
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['nama_lengkap'] = $user_data['nama_lengkap'];

                ob_clean(); 
                header("Location: dashboard.php");
                exit();
                
            } else {
                $error_message = "Username atau password salah.";
            }
            
        } else {
            $error_message = "Username atau password salah.";
        }

        $stmt->close();
        
    } else {
        $error_message = "Terjadi masalah sistem.";
    }

} else {
    $error_message = "Metode permintaan tidak valid.";
}

if (isset($error_message)) {
    $_SESSION['error_login'] = $error_message;
}

ob_clean(); 
header("Location: index.php");
exit();

if (isset($koneksi)) {
    $koneksi->close();
}

ob_end_flush();

?>