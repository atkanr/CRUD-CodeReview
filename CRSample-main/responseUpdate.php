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
            // Insert new record into the contacts table
            $stmt = $pdo->prepare('UPDATE contacts SET name = ?, email = ?, phone = ?, title = ? WHERE id = ?');
            $stmt->execute([$name, $email, $phone, $title, $_GET['id']]);
            header("location:index.php");
        } else {
            die ('Update Tidak Berhasil dilakukan');
        }
    } else {
        echo "Token tidak valid";
    }
?>
