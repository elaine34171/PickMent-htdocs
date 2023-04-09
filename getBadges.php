<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();
  
    $uid = $_POST['uid'];

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $result = mysqli_query($db, "SELECT * FROM medali") or die(mysqli_connect_error());

    $response['badges'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $badge = array();

        $badge['id'] = $row['id'];
        $badge['title'] = $row['nama'];
        $badge['image'] = $row['gambar'];

        $tmp = $row['id'];
        $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_medali WHERE pengguna = '$uid' AND medali = '$tmp'") or die(mysqli_connect_error());

        if(mysqli_num_rows($checkStatus) > 0) {
            $badge['status'] = "1";
        }
        else {
            $badge['status'] = "0";
        }

        array_push($response['badges'], $badge);
    }

    echo json_encode($response);
?>