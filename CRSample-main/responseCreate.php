<?php 

    include 'functions.php';
    $pdo = pdo_connect();

    session_start();

    function cek_token() {
        if (!isset($_POST['csrf_token'])) {
            return false;
        }
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return ($_POST['csrf_token'] === $_SESSION['csrf_token']);
    }

    
    if (cek_token()) {
        if (!empty($_POST)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $title = $_POST['title'];
            $created = date('Y-m-d H:i:s');
            // Insert new record into the contacts table
            $stmt = $pdo->prepare('INSERT INTO contacts VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$id, $name, $email, $phone, $title, $created]);
            header("location:index.php");
        } else {
            die ('Data Tidak Berhasil Ditambahkan');
        }
    } else {
        echo "Token Tidak Valid";
    }
?>