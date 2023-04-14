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

    $response['info'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $info = array();

        $info['name'] = $row['nama_lengkap'];
        $info['birthYear'] = $row['tahun_lahir'];
        $info['sex'] = $row['jenis_kelamin'];
        $info['phone'] = $row['no_telepon'];
        $info['email'] = $row['email'];
        $info['location'] = $row['domisili'];
        $info['occupation'] = $row['pekerjaan'];
        $info['education'] = $row['pendidikan'];

        array_push($response['info'], $info);
    }

    echo json_encode($response);
?>