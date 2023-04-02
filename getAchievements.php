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

    $result = mysqli_query($db, "SELECT * FROM achievement") or die(mysqli_connect_error());

    $response['achievements'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $achievement = array();

        $achievement['id'] = $row['id'];
        $achievement['title'] = $row['nama'];
        $achievement['description'] = $row['keterangan'];

        $tmp = $row['id'];
        $checkIconReward = mysqli_query($db, "SELECT * FROM ikon WHERE achievement = '$tmp'") or die(mysqli_connect_error());
        $checkCardReward = mysqli_query($db, "SELECT * FROM kartu_nama WHERE achievement = '$tmp'") or die(mysqli_connect_error());
        
        if(mysqli_num_rows($checkIconReward) > 0 && mysqli_num_rows($checkCardReward) > 0) {
            $achievement['reward'] = "Ikon profil, kartu nama";
        }
        else {
            if(mysqli_num_rows($checkIconReward) > 0) {
                $achievement['reward'] = "Ikon profil";
            }
            else {
                $achievement['reward'] = "Kartu nama";
            }
        }

        $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_achievement WHERE pengguna = '$uid' AND achievement = '$tmp'") or die(mysqli_connect_error());

        if(mysqli_num_rows($checkStatus) > 0) {
            $achievement['status'] = "1";
        }
        else {
            $achievement['status'] = "0";
        }

        array_push($response['achievements'], $achievement);
    }

    echo json_encode($response);
?>