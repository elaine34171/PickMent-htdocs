<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = 0;
    $language = 0;
    $weekly = 0;
    date_default_timezone_set('Asia/Bangkok');
    $endTime = date('Y-m-d H:i:s', strtotime('monday next week - 1 second'));
    $xp = 0;
    $level = 0;
    $participation = 0;
    $correctAnswer = 0;
    $firstRank = 0;
    $badge1 = 0;
    $badge2 = 0;
    $frame = 1;
    $namecard = 1;
    $icon = 1;

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $checkUsername = mysqli_query($db, "SELECT * FROM pengguna WHERE nama_pengguna = '$username'") or die(mysqli_connect_error());

    if(mysqli_num_rows($checkUsername) === 0) {
        $result = mysqli_query($db, "
            INSERT INTO pengguna (
                nama_pengguna,
                kata_sandi,
                status_admin,
                preferensi_bahasa,
                nama_lengkap,
                tahun_lahir,
                jenis_kelamin,
                no_telepon,
                email,
                domisili,
                pekerjaan,
                pendidikan,
                xp_mingguan,
                waktu_berlaku_xp_mingguan,
                xp,
                level,
                partisipasi,
                jawaban_benar,
                peringkat_satu_awal,
                medali_1,
                medali_2,
                bingkai,
                kartu_nama,
                ikon
            )
            VALUES (
                '$username',
                '$password',
                '$status',
                '$language',
                '',
                0,
                '',
                '',
                '',
                '',
                '',
                '',
                '$weekly',
                '$endTime',
                '$xp',
                '$level',
                '$participation',
                '$correctAnswer',
                '$firstRank',
                '$badge1',
                '$badge2',
                '$frame',
                '$namecard',
                '$icon'
            )
        ");

        if($result) {
            $response['success'] = 1;
            $response['message'] = "Pendaftaran akun berhasil.";
        }
        else {
            $response['success'] = 0;
            $response['message'] = "Pendaftaran akun gagal.";
        }
    }
    else {
        $response['success'] = 0;
        $response['message'] = "Nama Pengguna telah digunakan oleh pengguna lain.";
    }

    echo json_encode($response);
?>