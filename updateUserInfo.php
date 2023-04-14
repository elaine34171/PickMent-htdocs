<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();

    $uid = $_POST['uid'];
    $name = $_POST['name'];
    $birthYear = $_POST['birthYear'];
    $sex = $_POST['sex'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $location = $_POST['location'];
    $occupation = $_POST['occupation'];
    $education = $_POST['education'];

    $achievement = 1;

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $result = mysqli_query($db, "
        UPDATE pengguna
        SET nama_lengkap = '$name',
            tahun_lahir = '$birthYear',
            jenis_kelamin = '$sex',
            no_telepon = '$phone',
            email = '$email',
            domisili = '$location',
            pekerjaan = '$occupation',
            pendidikan = '$education'
        WHERE id = '$uid'
    ");

    if($result) {
        if(
            $name !== "" &&
            $birthYear !== "" &&
            $sex !== "" &&
            $phone !== "" &&
            $email !== "" &&
            $location !== "" &&
            $occupation !== "" &&
            $education !== ""
        ) {
            $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_achievement WHERE pengguna = '$uid' AND achievement = '$achievement'") or die(mysqli_connect_error());

            if(mysqli_num_rows($checkStatus) === 0) {
                mysqli_query(
                    $db, "
                        INSERT INTO pengguna_memiliki_achievement (
                            pengguna,
                            achievement
                        )
                        VALUES (
                            '$uid',
                            '$achievement'
                        )
                    "
                );

                $resultTmp = mysqli_query($db, "SELECT * FROM achievement WHERE id = '$achievement'") or die(mysqli_connect_error());
                $achievementData = mysqli_fetch_array($resultTmp);
                $achievementTitle = $achievementData['nama'];

                $checkIconReward = mysqli_query($db, "SELECT * FROM ikon WHERE achievement = '$achievement'") or die(mysqli_connect_error());
                $checkCardReward = mysqli_query($db, "SELECT * FROM kartu_nama WHERE achievement = '$achievement'") or die(mysqli_connect_error());
                
                if(mysqli_num_rows($checkIconReward) > 0 && mysqli_num_rows($checkCardReward) > 0) {
                    $achievementReward = "ikon profil dan kartu nama";
                }
                else {
                    if(mysqli_num_rows($checkIconReward) > 0) {
                        $achievementReward = "ikon profil";
                    }
                    else {
                        $achievementReward = "kartu nama";
                    }
                }

                date_default_timezone_set('Asia/Bangkok');
                $time = date('Y-m-d H:i:s');
                $title = "Anda Memperoleh Achievement";
                $description =
                    "Anda berhasil menyelesaikan achievement \"" .
                    $achievementTitle .
                    "\" dan memperoleh hadiah " .
                    $achievementReward .
                    "."
                ;

                mysqli_query(
                    $db, "
                        INSERT INTO notifikasi (
                            waktu,
                            judul,
                            isi,
                            status_dibaca,
                            pengguna
                        )
                        VALUES (
                            '$time',
                            '$title',
                            '$description',
                            0,
                            '$uid'
                        )
                    "
                );

                if(mysqli_num_rows($checkIconReward) > 0) {
                    $avatar = mysqli_fetch_array($checkIconReward);
                    $avatarId = $avatar['id'];

                    mysqli_query(
                        $db, "
                            INSERT INTO pengguna_memiliki_ikon (
                                pengguna,
                                ikon
                            )
                            VALUES (
                                '$uid',
                                '$avatarId'
                            )
                        "
                    );
                }

                if(mysqli_num_rows($checkCardReward) > 0) {
                    $namecard = mysqli_fetch_array($checkCardReward);
                    $namecardId = $namecard['id'];

                    mysqli_query(
                        $db, "
                            INSERT INTO pengguna_memiliki_kartu (
                                pengguna,
                                kartu_nama
                            )
                            VALUES (
                                '$uid',
                                '$namecardId'
                            )
                        "
                    );
                }
            }
        }

        $response['success'] = 1;
        $response['message'] = "Perubahan Data Diri berhasil.";
    }
    else {
        $response['success'] = 0;
        $response['message'] = "Perubahan Data Diri gagal.";
    }

    echo json_encode($response);
?>