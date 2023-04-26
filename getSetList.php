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

    $result = mysqli_query($db, "SELECT * FROM set_data WHERE pengguna != '$uid' ORDER BY waktu ASC, id ASC") or die(mysqli_connect_error());

    $response['list'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $list = array();

        $list['id'] = $row['id'];
        $list['title'] = $row['judul'];
        $list['time'] = $row['waktu'];
        $list['items'] = $row['jumlah_item'];
        $list['language'] = $row['bahasa'];

        $tmp = $row['id'];
        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$tmp'") or die(mysqli_connect_error());
        $counter = mysqli_num_rows($resultTmp);

        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$tmp' AND pengguna = '$uid'") or die(mysqli_connect_error());
        $checkUser = mysqli_num_rows($resultTmp);
        
        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna WHERE id = '$uid'") or die(mysqli_connect_error());
        $userData = mysqli_fetch_array($resultTmp);
        $language = $userData['preferensi_bahasa'];

        if(($counter < (int) $row['kuota']) && $checkUser === 0 && ($language === $row['bahasa'] || $language === "0")) {
            array_push($response['list'], $list);
        }
    }

    echo json_encode($response);
?>