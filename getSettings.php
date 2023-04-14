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

    $response['settings'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $setting = array();

        $setting['language'] = $row['preferensi_bahasa'];

        array_push($response['settings'], $setting);
    }

    echo json_encode($response);
?>