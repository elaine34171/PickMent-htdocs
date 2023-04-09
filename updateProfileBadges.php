<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();

    $uid = $_POST['uid'];
    $badge1 = $_POST['badge1'];
    $badge2 = $_POST['badge2'];

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $result = mysqli_query($db, "UPDATE pengguna SET medali_1 = '$badge1', medali_2 = '$badge2' WHERE id = '$uid'");

    if($result) {
        $response['success'] = 1;
        $response['message'] = "Perubahan Mendali Profil berhasil.";
    }
    else {
        $response['success'] = 0;
        $response['message'] = "Perubahan Mendali Profil gagal.";
    }

    echo json_encode($response);
?>