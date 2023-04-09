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

    $result = mysqli_query($db, "SELECT * FROM ikon") or die(mysqli_connect_error());

    $response['avatars'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $avatar = array();

        $avatar['id'] = $row['id'];
        $avatar['image'] = $row['gambar'];

        if($row['achievement'] === "0") {
            $avatar['title'] = "Pemain";
            $avatar['status'] = "1";
        }
        else {
            $tmp = $row['achievement'];
            $resultTmp = mysqli_query($db, "SELECT nama FROM achievement WHERE id = '$tmp'") or die(mysqli_connect_error());
            $title = mysqli_fetch_array($resultTmp);
            $avatar['title'] = $title['nama'];

            $tmp = $row['id'];
            $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_ikon WHERE pengguna = '$uid' AND ikon = '$tmp'") or die(mysqli_connect_error());

            if(mysqli_num_rows($checkStatus) === 0) {
                $avatar['status'] = "0";
            }
            else {
                $avatar['status'] = "1";
            }
        }

        array_push($response['avatars'], $avatar);
    }

    echo json_encode($response);
?>