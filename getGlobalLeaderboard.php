<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();
  
    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    date_default_timezone_set('Asia/Bangkok');
    $response['startTime'] = date('Y-m-d H:i:s', strtotime('monday this week'));
    $response['endTime'] = date('Y-m-d H:i:s', strtotime('monday next week - 1 second'));

    $today = new DateTime(date('Y-m-d H:i:s'));
    $timeLeft = $today->diff(new DateTime($response['endTime']));
    
    $response['days'] = $timeLeft->d;
    $response['hours'] = $timeLeft->h;
    $response['minutes'] = $timeLeft->i;

    $endTime = date('Y-m-d H:i:s', strtotime('monday next week - 1 second'));
    $result = mysqli_query($db, "
        SELECT *
        FROM pengguna
        WHERE waktu_berlaku_xp_mingguan = '$endTime'
        ORDER BY xp_mingguan DESC
        LIMIT 5
    ") or die(mysqli_connect_error());

    $response['leaderboard'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $leaderboard = array();

        $leaderboard['uid'] = $row['id'];
        $leaderboard['username'] = $row['nama_pengguna'];
        $leaderboard['weeklyXp'] = $row['xp_mingguan'];

        $tmp = $row['ikon'];
        $resultTmp = mysqli_query($db, "SELECT gambar FROM ikon WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
        $avatarImage = mysqli_fetch_array($resultTmp);
        $leaderboard['avatarImage'] = $avatarImage['gambar'];

        $tmp = $row['bingkai'];
        $resultTmp = mysqli_query($db, "SELECT warna FROM bingkai WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
        $frameColor = mysqli_fetch_array($resultTmp);
        $leaderboard['frameColor'] = $frameColor['warna'];

        $tmp = $row['kartu_nama'];
        $resultTmp = mysqli_query($db, "SELECT gambar FROM kartu_nama WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
        $namecardImage = mysqli_fetch_array($resultTmp);
        $leaderboard['namecardImage'] = $namecardImage['gambar'];

        $tmp = $row['medali_1'];
        if($tmp === "0") {
            $leaderboard['badge1image'] = "";
        }
        else {
            $resultTmp = mysqli_query($db, "SELECT gambar FROM medali WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
            $badge1image = mysqli_fetch_array($resultTmp);
            $leaderboard['badge1image'] = $badge1image['gambar'];
        }

        $tmp = $row['medali_2'];
        if($tmp === "0") {
            $leaderboard['badge2image'] = "";
        }
        else {
            $resultTmp = mysqli_query($db, "SELECT gambar FROM medali WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
            $badge2image = mysqli_fetch_array($resultTmp);
            $leaderboard['badge2image'] = $badge2image['gambar'];
        }

        array_push($response['leaderboard'], $leaderboard);
    }

    echo json_encode($response);
?>