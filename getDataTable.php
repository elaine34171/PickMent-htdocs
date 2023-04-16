<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();
  
    $id = (int) $_POST['id'];
    $uid = $_POST['uid'];

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $checkSet = mysqli_query($db, "SELECT * FROM set_data WHERE id = '$id' AND pengguna = '$uid'") or die(mysqli_connect_error());

    if(mysqli_num_rows($checkSet) > 0) {
        $setResult = mysqli_fetch_array($checkSet);
        $response['title'] = $setResult['judul'];
        $response['time'] = $setResult['waktu'];

        $result = mysqli_query($db, "SELECT * FROM `data` WHERE set_data = '$id'") or die(mysqli_connect_error());

        $response['dataTable'] = array();
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dataTable = array();

            $dataTable['id'] = $row['id'];
            $dataTable['data'] = $row['teks'];

            $tmp = $row['id'];
            $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$tmp' AND sentimen = 1") or die(mysqli_connect_error());
            $dataTable['positive'] = mysqli_num_rows($resultTmp);

            $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$tmp' AND sentimen = 0") or die(mysqli_connect_error());
            $dataTable['neutral'] = mysqli_num_rows($resultTmp);

            $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$tmp' AND sentimen = -1") or die(mysqli_connect_error());
            $dataTable['negative'] = mysqli_num_rows($resultTmp);

            array_push($response['dataTable'], $dataTable);
        }

        $response['found'] = 1;
    }
    else {
        $response['found'] = 0;
    }

    echo json_encode($response);
?>