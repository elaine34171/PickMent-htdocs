<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();
  
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $result = mysqli_query($db, "SELECT * FROM pengguna WHERE nama_pengguna = '$username'") or die(mysqli_connect_error());

    if(mysqli_num_rows($result) > 0) {
        $response['user'] = array();

        $passwordCheck = 0;
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            if(password_verify($password, $row['kata_sandi'])) {
                $user = array();

                $user['uid'] = $row['id'];
                
                array_push($response['user'], $user);

                $passwordCheck = 1;

                break;
            }
        }

        if($passwordCheck == 1) {
            $response['success'] = 1;
            $response['message'] = "Berhasil masuk ke akun.";
        }
        else {
            $response['success'] = 0;
            $response['message'] = "Kata Sandi tidak sesuai.";
        }
    }
    else {
        $response['success'] = 0;
        $response['message'] = "Nama Pengguna belum terdaftar";
    }

    echo json_encode($response);
?>