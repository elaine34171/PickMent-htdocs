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

    $result = mysqli_query($db, "SELECT * FROM pengguna WHERE id ='$uid'") or die(mysqli_connect_error());

    $response['profile'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $profile = array();

        date_default_timezone_set('Asia/Bangkok');
        $endTime = date('Y-m-d H:i:s', strtotime('monday next week - 1 second'));
        $checkUser = mysqli_query($db, "
            SELECT *
            FROM pengguna
            WHERE 
                waktu_berlaku_xp_mingguan = '$endTime' AND
                id = '$uid'
        ") or die(mysqli_connect_error());

        if(mysqli_num_rows($checkUser) > 0) {
            $resultTmp = mysqli_query($db, "
                SELECT *
                FROM pengguna
                WHERE waktu_berlaku_xp_mingguan = '$endTime'
                ORDER BY xp_mingguan DESC, jawaban_benar DESC, partisipasi DESC, peringkat_satu_awal DESC
            ") or die(mysqli_connect_error());
        
            $rank = 0;
            $previousXp = 2147483647;
            while($r = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
                $xp = (int) $r['xp_mingguan'];
                if($xp < $previousXp) {
                    $rank++;
                }
                $previousXp = (int) $r['xp_mingguan'];

                if($r['id'] === $uid) {
                    break;
                }
            }

            $profile['rank'] = $rank;
            $profile['weeklyXp'] = $row['xp_mingguan'];
        }
        else {
            $profile['rank'] = "-";
            $profile['weeklyXp'] = "0";
        }

        $profile['username'] = $row['nama_pengguna'];
        $profile['status'] = $row['status_admin'];
        $profile['level'] = $row['level'];
        $profile['xp'] = $row['xp'];

        $profile['avatarId'] = $row['ikon'];
        $tmp = $row['ikon'];
        $resultTmp = mysqli_query($db, "SELECT gambar FROM ikon WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
        $avatarImage = mysqli_fetch_array($resultTmp);
        $profile['avatarImage'] = $avatarImage['gambar'];

        $profile['frameId'] = $row['bingkai'];
        $tmp = $row['bingkai'];
        $resultTmp = mysqli_query($db, "SELECT warna FROM bingkai WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
        $frameColor = mysqli_fetch_array($resultTmp);
        $profile['frameColor'] = $frameColor['warna'];

        $profile['namecardId'] = $row['kartu_nama'];
        $tmp = $row['kartu_nama'];
        $resultTmp = mysqli_query($db, "SELECT gambar FROM kartu_nama WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
        $namecardImage = mysqli_fetch_array($resultTmp);
        $profile['namecardImage'] = $namecardImage['gambar'];

        $profile['badge1id'] = $row['medali_1'];
        $tmp = $row['medali_1'];
        if($tmp === "0") {
            $profile['badge1image'] = "";
        }
        else {
            $resultTmp = mysqli_query($db, "SELECT gambar FROM medali WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
            $badge1image = mysqli_fetch_array($resultTmp);
            $profile['badge1image'] = $badge1image['gambar'];
        }

        $profile['badge2id'] = $row['medali_2'];
        $tmp = $row['medali_2'];
        if($tmp === "0") {
            $profile['badge2image'] = "";
        }
        else {
            $resultTmp = mysqli_query($db, "SELECT gambar FROM medali WHERE id = '$tmp' LIMIT 1") or die(mysqli_connect_error());
            $badge2image = mysqli_fetch_array($resultTmp);
            $profile['badge2image'] = $badge2image['gambar'];
        }

        array_push($response['profile'], $profile);
    }

    echo json_encode($response);
?>