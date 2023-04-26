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

    $result = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$id' ORDER BY skor DESC") or die(mysqli_connect_error());

    $response['leaderboard'] = array();
    $rank = 0;
    $previousScore = 2147483647;
    $found = 0;
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if($found === 1 && $rank > 5) {
            break;
        }

        $leaderboard = array();

        $leaderboard['uid'] = $row['pengguna'];
        $leaderboard['score'] = $row['skor'];
        
        $score = (int) $row['skor'];
        if($score < $previousScore) {
            $rank++;
        }
        $previousScore = (int) $row['skor'];
        
        $leaderboard['rank'] = $rank;

        $thisUid = $row['pengguna'];
        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna WHERE id = '$thisUid' LIMIT 1") or die(mysqli_connect_error());
        $userData = mysqli_fetch_array($resultTmp);
        $leaderboard['username'] = $userData['nama_pengguna'];
        $avatarId = $userData['ikon'];
        $frameId = $userData['bingkai'];
        $namecardId = $userData['kartu_nama'];
        $badge1Id = $userData['medali_1'];
        $badge2Id = $userData['medali_2'];
        $participation = $userData['partisipasi'];

        $resultTmp = mysqli_query($db, "SELECT gambar FROM ikon WHERE id = '$avatarId' LIMIT 1") or die(mysqli_connect_error());
        $avatarImage = mysqli_fetch_array($resultTmp);
        $leaderboard['avatarImage'] = $avatarImage['gambar'];

        $resultTmp = mysqli_query($db, "SELECT warna FROM bingkai WHERE id = '$frameId' LIMIT 1") or die(mysqli_connect_error());
        $frameColor = mysqli_fetch_array($resultTmp);
        $leaderboard['frameColor'] = $frameColor['warna'];

        $resultTmp = mysqli_query($db, "SELECT gambar FROM kartu_nama WHERE id = '$namecardId' LIMIT 1") or die(mysqli_connect_error());
        $namecardImage = mysqli_fetch_array($resultTmp);
        $leaderboard['namecardImage'] = $namecardImage['gambar'];

        if($badge1Id === "0") {
            $leaderboard['badge1image'] = "";
        }
        else {
            $resultTmp = mysqli_query($db, "SELECT gambar FROM medali WHERE id = '$badge1Id' LIMIT 1") or die(mysqli_connect_error());
            $badge1image = mysqli_fetch_array($resultTmp);
            $leaderboard['badge1image'] = $badge1image['gambar'];
        }

        if($badge2Id === "0") {
            $leaderboard['badge2image'] = "";
        }
        else {
            $resultTmp = mysqli_query($db, "SELECT gambar FROM medali WHERE id = '$badge2Id' LIMIT 1") or die(mysqli_connect_error());
            $badge2image = mysqli_fetch_array($resultTmp);
            $leaderboard['badge2image'] = $badge2image['gambar'];
        }

        if($thisUid === $uid) {
            $found = 1;

            $response['rank'] = $leaderboard['rank'];
            $response['username'] = $leaderboard['username'];
            $response['avatarImage'] = $leaderboard['avatarImage'];
            $response['frameColor'] = $leaderboard['frameColor'];
            $response['namecardImage'] = $leaderboard['namecardImage'];
            $response['badge1image'] = $leaderboard['badge1image'];
            $response['badge2image'] = $leaderboard['badge2image'];
            $response['score'] = $leaderboard['score'];
            $response['participation'] = $participation;
        }

        if($rank <= 5) {
            array_push($response['leaderboard'], $leaderboard);
        }
    }

    $response['found'] = $found;

    echo json_encode($response);
?>