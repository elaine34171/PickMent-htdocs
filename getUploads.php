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

    $result = mysqli_query($db, "SELECT * FROM set_data WHERE pengguna = '$uid' ORDER BY waktu DESC") or die(mysqli_connect_error());

    $response['uploads'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $upload = array();

        $upload['id'] = $row['id'];
        $upload['title'] = $row['judul'];
        $upload['time'] = $row['waktu'];
        $upload['target'] = $row['kuota'];

        $tmp = $row['id'];
        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$tmp'") or die(mysqli_connect_error());
        $upload['counter'] = mysqli_num_rows($resultTmp);

        array_push($response['uploads'], $upload);
    }

    echo json_encode($response);
?>