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

    $checkSet = mysqli_query($db, "SELECT * FROM set_data WHERE id = '$id'") or die(mysqli_connect_error());

    if(mysqli_num_rows($checkSet) > 0) {
        $response['found'] = 1;

        $checkHistory = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$id' AND pengguna = '$uid'") or die(mysqli_connect_error());

        if(mysqli_num_rows($checkHistory) > 0) {
            $response['done'] = 1;
        }
        else {
            $response['done'] = 0;

            $checkCompletion = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$id' AND status_selesai = 1") or die(mysqli_connect_error());
 
            if(mysqli_num_rows($checkCompletion) > 0) {
                $response['completed'] = 1;
            }
            else {
                $response['completed'] = 0;

                $result = mysqli_query($db, "SELECT * FROM `data` WHERE set_data ='$id'") or die(mysqli_connect_error());

                $response['gameData'] = array();
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $gameData = array();

                    $gameData['id'] = $row['id'];
                    $gameData['data'] = $row['teks'];

                    $tmp = $row['id'];
                    $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$tmp' AND sentimen = 1") or die(mysqli_connect_error());
                    $gameData['positive'] = mysqli_num_rows($resultTmp);

                    $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$tmp' AND sentimen = 0") or die(mysqli_connect_error());
                    $gameData['neutral'] = mysqli_num_rows($resultTmp);

                    $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$tmp' AND sentimen = -1") or die(mysqli_connect_error());
                    $gameData['negative'] = mysqli_num_rows($resultTmp);

                    array_push($response['gameData'], $gameData);
                }
            }
        }
    }
    else {
        $response['found'] = 0;
    }

    echo json_encode($response);
?>