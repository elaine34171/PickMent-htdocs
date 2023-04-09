<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();

    $uid = $_POST['uid'];
    $avatar = $_POST['avatar'];
    $frame = $_POST['frame'];
    $namecard = $_POST['namecard'];

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $result = mysqli_query($db, "
        UPDATE pengguna
        SET ikon = '$avatar',
            bingkai = '$frame',
            kartu_nama = '$namecard'
        WHERE id = '$uid'
    ");

    if($result) {
        $response['success'] = 1;
        $response['message'] = "Perubahan Profil berhasil.";
    }
    else {
        $response['success'] = 0;
        $response['message'] = "Perubahan Profil gagal.";
    }

    echo json_encode($response);
?>