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

    $result = mysqli_query($db, "SELECT * FROM kategori_medali") or die(mysqli_connect_error());

    $response['badgeCategories'] = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $badgeCategory = array();

        $badgeCategory['id'] = $row['id'];
        $badgeCategory['image'] = $row['gambar'];
        $badgeCategory['title'] = $row['nama'];
        $badgeCategory['description'] = $row['keterangan'];
        $badgeCategory['target'] = "selesai";

        $colName = $row['syarat'];
        $resultTmp = mysqli_query($db, "SELECT $colName FROM pengguna WHERE id = '$uid' LIMIT 1") or die(mysqli_connect_error());
        $counter = mysqli_fetch_array($resultTmp);
        $badgeCategory['counter'] = $counter[$colName];

        $tmp = $row['id'];
        $resultTmp = mysqli_query($db, "SELECT id FROM medali WHERE kategori = '$tmp'") or die(mysqli_connect_error());
        
        $badgeIds = "(";
        while($r = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
            $badgeIds = $badgeIds . $r['id'] . ", ";
        }
        $badgeIds = $badgeIds . "-1)";

        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_memiliki_medali WHERE pengguna = '$uid' AND medali IN $badgeIds") or die(mysqli_connect_error());
        $status = mysqli_num_rows($resultTmp);
        $badgeCategory['status'] = "$status";

        $resultTmp = mysqli_query($db, "SELECT * FROM medali WHERE kategori = '$tmp'") or die(mysqli_connect_error());
        $counter = 0;
        while($r = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
            if($counter === $status) {
                $badgeCategory['title'] = $r['nama'];
                $badgeCategory['description'] = $r['keterangan'];
                $badgeCategory['target'] = $r['syarat'];

                break;
            }
            
            $counter++;
        }

        array_push($response['badgeCategories'], $badgeCategory);
    }

    echo json_encode($response);
?>