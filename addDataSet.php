<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();

    $title = $_POST['title'];
    $items = (int) $_POST['items'];
    $players = $_POST['players'];
    $language = $_POST['language'];
    $uid = $_POST['uid'];

    date_default_timezone_set('Asia/Bangkok');
    $time = date('Y-m-d H:i:s');

    $dataRows = json_decode($_POST['dataRows']);
    $numberOfSet = count($dataRows) / $items;

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    for($i = 0; $i < $numberOfSet; $i++) {
        if($i > 0) {
            $titleTmp = $title . " " . ($i + 1);
        }
        else {
            $titleTmp = $title;
        }

        $result = mysqli_query($db, "
            INSERT INTO set_data (
                judul,
                jumlah_item,
                kuota,
                bahasa,
                pengguna,
                waktu
            )
            VALUES (
                '$titleTmp',
                '$items',
                '$players',
                '$language',
                '$uid',
                '$time'
            )
        ");

        $set_data = mysqli_insert_id($db);
        for($j = 0; $j < $items; $j++) {
            $x = ($i * $items) + $j;
            $result = mysqli_query($db, "
                INSERT INTO data (
                    teks,
                    set_data
                )
                VALUES (
                    '$dataRows[$x]',
                    '$set_data'
                )
            ");
        }
    }

    if($result) {
        $response['success'] = 1;
        $response['message'] = "Unggah Set berhasil.";
    }
    else {
        $response['success'] = 0;
        $response['message'] = "Unggah Set gagal.";
    }

    echo json_encode($response);
?>