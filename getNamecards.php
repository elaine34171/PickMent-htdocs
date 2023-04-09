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

    $result = mysqli_query($db, "SELECT * FROM kartu_nama") or die(mysqli_connect_error());

    $response['namecards'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $namecard = array();

        $namecard['id'] = $row['id'];
        $namecard['image'] = $row['gambar'];

        if($row['achievement'] === "0") {
            $namecard['title'] = "Pemain";
            $namecard['status'] = "1";
        }
        else {
            $tmp = $row['achievement'];
            $resultTmp = mysqli_query($db, "SELECT nama FROM achievement WHERE id = '$tmp'") or die(mysqli_connect_error());
            $title = mysqli_fetch_array($resultTmp);
            $namecard['title'] = $title['nama'];

            $tmp = $row['id'];
            $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_kartu WHERE pengguna = '$uid' AND kartu_nama = '$tmp'") or die(mysqli_connect_error());

            if(mysqli_num_rows($checkStatus) === 0) {
                $namecard['status'] = "0";
            }
            else {
                $namecard['status'] = "1";
            }
        }

        array_push($response['namecards'], $namecard);
    }

    echo json_encode($response);
?>