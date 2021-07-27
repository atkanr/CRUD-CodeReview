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
        $errorNot = null;
    
        $check_login_row = $query->fetch_assoc();
        $total_count = $check_login_row['total_count'];
    
        if ($total_count == 3)  {
            $errorNot = "To many failed login attempts. Try again later";
        }
            if (isset($_POST['username']) && isset($_POST['password'])) {

                session_start();
                $user = $_POST['username'];
                $pass = $_POST['password'];
                $salt = "XDrBmrW9g2fb";
                $pdo = pdo_connect();
                $stmt = $pdo->prepare('SELECT * FROM users WHERE username = "' . $user . '" AND password = "' . hash('sha256', $pass . $salt) . '" LIMIT 1');
                $stmt->execute();
                $errorNot = $stmt->rowCount();
                if ($stmt->rowCount() > 0) {
                    $_SESSION['user'] = $user;
                    header("location: index.php");
                } else {
                    $total_count++;
                    $rem_attm = 3-$total_count;
                    if($rem_attm == 0){
                        $errorNot = "To many failed login attempts. Try again Later";
                    }   else{
                    $errorNot = "Wrong usename or password";
                    }   
                }
                $timeout = 1; // setting timeout dalam menit
                $logout = "logout.php"; // redirect halaman logout
                $timeout = $timeout * 60; // menit ke detik
                if(isset($_SESSION['start_session'])){
                    $elapsed_time = time()-$_SESSION['start_session'];
                        if($elapsed_time >= $timeout){
                            session_destroy();
                        }
                    }  
                $_SESSION['start_session']=time();
            }
    } else {
        echo "Invalid Token";
    }
?>