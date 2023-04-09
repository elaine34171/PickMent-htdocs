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

    $result = mysqli_query($db, "SELECT * FROM bingkai") or die(mysqli_connect_error());

    $response['frames'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $frame = array();

        $frame['id'] = $row['id'];
        $frame['color'] = $row['warna'];
        $frame['level'] = $row['level'];

        $resultTmp = mysqli_query($db, "SELECT level FROM pengguna WHERE id = '$uid'") or die(mysqli_connect_error());
        $level = mysqli_fetch_array($resultTmp);
        $userLevel = $level['level'];

        if(intval($row['level']) > intval($userLevel)) {
            $frame['status'] = "0";
        }
        else {
            $frame['status'] = "1";
        }

        array_push($response['frames'], $frame);
    }

    echo json_encode($response);
?>