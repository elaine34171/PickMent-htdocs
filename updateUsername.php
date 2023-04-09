<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();

    $uid = $_POST['uid'];
    $username = $_POST['username'];

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $checkUsername = mysqli_query($db, "SELECT * FROM pengguna WHERE nama_pengguna = '$username'") or die(mysqli_connect_error());

    if(mysqli_num_rows($checkUsername) === 0) {
        $result = mysqli_query($db, "UPDATE pengguna SET nama_pengguna = '$username' WHERE id = '$uid'");

        if($result) {
            $response['success'] = 1;
            $response['message'] = "Perubahan Nama Pengguna berhasil.";
        }
        else {
            $response['success'] = 0;
            $response['message'] = "Perubahan Nama Pengguna gagal.";
        }
    }
    else {
        $response['success'] = 0;
        $response['message'] = "Nama Pengguna telah digunakan oleh pengguna lain.";
    }

    echo json_encode($response);
?>