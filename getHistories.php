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

    $result = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE pengguna = '$uid' ORDER BY waktu DESC") or die(mysqli_connect_error());

    $response['histories'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $history = array();

        $history['setId'] = $row['set_data'];
        $history['time'] = $row['waktu'];
        $history['score'] = $row['skor'];
        $history['status'] = $row['status_selesai'];

        $tmp = $row['set_data'];
        $resultTmp = mysqli_query($db, "SELECT judul FROM set_data WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
        $setTitle = mysqli_fetch_array($resultTmp);
        $history['setTitle'] = $setTitle['judul'];

        $tmp = $row['set_data'];
        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$tmp' ORDER BY skor DESC") or die(mysqli_connect_error());
        $counter = 1;
        while($r = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
            if($r['pengguna'] === $row['pengguna']) {
                break;
            }
            $counter++;
        }

        $history['rank'] = $counter;
        
        array_push($response['histories'], $history);
    }

    echo json_encode($response);
?>