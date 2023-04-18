<?php
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }
  
    $response = array();

    $uid = $_POST['uid'];
    $id = (int) $_POST['id'];
    $answerData = json_decode($_POST['answerData']);
    $achievement6 = $_POST['achievement6'];
    $achievement7 = $_POST['achievement7'];

    date_default_timezone_set('Asia/Bangkok');
    $time = date('Y-m-d H:i:s');

    require_once __DIR__ . '/dbconfig.php';

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());

    $score = 0;
    $correctAnswers = 0;
    for($i = 0; $i < count($answerData); $i++) {
        $score += $answerData[$i]->points;

        if($answerData[$i]->points > 0) {
            $correctAnswers++;
        }

        $sentiment = $answerData[$i]->sentiment;
        $data = $answerData[$i]->id;

        mysqli_query($db, "
            INSERT INTO pengguna_melabeli_data (
                sentimen,
                pengguna,
                data
            )
            VALUES (
                '$sentiment',
                '$uid',
                '$data'
            )
        ");
    }

    mysqli_query($db, "
        INSERT INTO pengguna_mengerjakan_set (
            pengguna,
            set_data,
            waktu,
            skor,
            status_selesai
        )
        VALUES (
            '$uid',
            '$id',
            '$time',
            '$score',
            0
        )
    ");

    if($achievement6 === "1") {
        $achievement = 6;

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

    if($achievement7 === "1") {
        $achievement = 7;

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

    $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$id' ORDER BY skor DESC") or die(mysqli_connect_error());
    $rank = 0;
    $previousScore = 2147483647;
    while($row = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
        if($score < $previousScore) {
            $rank++;
        }
        $previousScore = (int) $row['skor'];

        if($row['pengguna'] === $uid) {
            break;
        }
    }

    if($rank === 1) {
        $firstRank = 1;

        $achievement = 2;

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
    else {
        $firstRank = 0;
    }

    $checkUser = mysqli_query($db, "SELECT * FROM pengguna WHERE id = '$uid'") or die(mysqli_connect_error());
    $userData = mysqli_fetch_array($checkUser);
    
    $level = (int) $userData['level'];
    $neededXp = 1000 * pow(2, $level);
    $xp = (int) $userData['xp'] + $score;

    while($xp > $neededXp && $level < 10) {
        $level++;
        $xp -= $neededXp;

        $title = "Kenaikan Level Pemain";
        $description =
            "Anda berhasil mencapai level " .
            $level .
            " dan memperoleh hadiah bingkai profil."
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

        $neededXp = 1000 * pow(2, $level);
    }

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
        $userData = mysqli_fetch_array($checkUser);
        
        $weeklyXp = (int) $userData['xp_mingguan'] + $score;
    }
    else {
        $weeklyXp = $score;
    }

    mysqli_query($db, "
        UPDATE pengguna
        SET
            partisipasi = partisipasi + 1,
            jawaban_benar = jawaban_benar + $correctAnswers,
            peringkat_satu_awal = peringkat_satu_awal + $firstRank,
            level = '$level',
            xp = '$xp',
            xp_mingguan = '$weeklyXp',
            waktu_berlaku_xp_mingguan = '$endTime'
        WHERE
            id = '$uid'
    ");

    $badgeData = mysqli_query($db, "SELECT * FROM kategori_medali") or die(mysqli_connect_error());

    while($row = mysqli_fetch_array($badgeData, MYSQLI_ASSOC)) {
        $tmp = $row['id'];
        $resultTmp = mysqli_query($db, "SELECT id FROM medali WHERE kategori = '$tmp'") or die(mysqli_connect_error());
        
        $badgeIds = "(";
        while($r = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
            $badgeIds = $badgeIds . $r['id'] . ", ";
        }
        $badgeIds = $badgeIds . "-1)";

        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_memiliki_medali WHERE pengguna = '$uid' AND medali IN $badgeIds") or die(mysqli_connect_error());
        $status = mysqli_num_rows($resultTmp);

        $resultTmp = mysqli_query($db, "SELECT * FROM medali WHERE kategori = '$tmp'") or die(mysqli_connect_error());
        
        $badge = "-1";
        $target = 0;
        $counter = 0;
        while($r = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
            if($counter === $status) {
                $badge = $r['id'];
                $badgeTitle = $r['nama'];
                $target = (int) $r['syarat'];

                break;
            }
            
            $counter++;
        }

        $colName = $row['syarat'];
        $resultTmp = mysqli_query($db, "SELECT $colName FROM pengguna WHERE id = '$uid' LIMIT 1") or die(mysqli_connect_error());
        $userData = mysqli_fetch_array($resultTmp);
        $counter = (int) $userData[$colName];

        if($counter >= $target && $badge !== "-1") {
            $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_medali WHERE pengguna = '$uid' AND medali = '$badge'") or die(mysqli_connect_error());
    
            if(mysqli_num_rows($checkStatus) === 0) {
                mysqli_query(
                    $db, "
                        INSERT INTO pengguna_memiliki_medali (
                            pengguna,
                            medali
                        )
                        VALUES (
                            '$uid',
                            '$badge'
                        )
                    "
                );
    
                $title = "Anda Memperoleh Medali";
                $description =
                    "Anda memperoleh medali \"" .
                    $badgeTitle .
                    "\"."
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
            }
        }
    }

    $checkComplete = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$id'") or die(mysqli_connect_error());
    $counter = mysqli_num_rows($checkComplete);

    $resultTmp = mysqli_query($db, "SELECT * FROM set_data WHERE id = '$id'") or die(mysqli_connect_error());
    $setData = mysqli_fetch_array($resultTmp);
    $target = (int) $setData['kuota'];

    if($counter === $target) {
        while($row = mysqli_fetch_array($checkComplete, MYSQLI_ASSOC)) {
            $thisUid = $row['pengguna'];
            
            $bonus = 0;

            $tmp = mysqli_query($db, "SELECT * FROM `data` WHERE set_data = '$id'") or die(mysqli_connect_error());

            while($r = mysqli_fetch_array($tmp, MYSQLI_ASSOC)) {
                $dataId = $r['id'];
                
                $resultTmp = mysqli_query($db, "SELECT sentimen FROM pengguna_melabeli_data WHERE `data` = '$dataId' AND pengguna = '$thisUid'") or die(mysqli_connect_error());
                $answerTmp = mysqli_fetch_array($resultTmp);
                $answer = $answerTmp['sentimen'];

                $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$dataId' AND sentimen = 1") or die(mysqli_connect_error());
                $positiveResult = mysqli_num_rows($resultTmp);

                $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$dataId' AND sentimen = 0") or die(mysqli_connect_error());
                $neutralResult = mysqli_num_rows($resultTmp);

                $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_melabeli_data WHERE `data` = '$dataId' AND sentimen = -1") or die(mysqli_connect_error());
                $negativeResult = mysqli_num_rows($resultTmp);

                $maxResult = max($positiveResult, $neutralResult, $negativeResult);
                $correct = true;

                if($answer === "1" && ($neutralResult === $maxResult || $negativeResult === $maxResult)) {
                    $correct = false;
                }

                if($answer === "0" && ($positiveResult === $maxResult || $negativeResult === $maxResult)) {
                    $correct = false;
                }

                if($answer === "-1" && ($positiveResult === $maxResult || $neutralResult === $maxResult)) {
                    $correct = false;
                }

                if($correct) {
                    $bonus += 5 * $maxResult;
                }
            }

            $newScore = (int) $row['skor'] + $bonus;

            mysqli_query($db, "
                UPDATE pengguna_mengerjakan_set
                SET
                    skor = '$newScore',
                    status_selesai = 1
                WHERE
                    set_data = '$id' AND
                    pengguna = '$thisUid'
            ");

            $title = "Ada Perubahan pada Papan Peringkat";
            $description =
                "Set #" .
                sprintf("%06s", $id) .
                " telah mencapai target jumlah pemain. Anda mendapatkan bonus " .
                $bonus .
                " point."
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
                        '$thisUid'
                    )
                "
            );

            $checkUser = mysqli_query($db, "SELECT * FROM pengguna WHERE id = '$thisUid'") or die(mysqli_connect_error());
            $userData = mysqli_fetch_array($checkUser);
            
            $level = (int) $userData['level'];
            $neededXp = 1000 * pow(2, $level);
            $xp = (int) $userData['xp'] + $bonus;

            while($xp > $neededXp && $level < 10) {
                $level++;
                $xp -= $neededXp;

                $title = "Kenaikan Level Pemain";
                $description =
                    "Anda berhasil mencapai level " .
                    $level .
                    " dan memperoleh hadiah bingkai profil."
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
                            '$thisUid'
                        )
                    "
                );

                $neededXp = 1000 * pow(2, $level);
            }

            $checkUser = mysqli_query($db, "
                SELECT *
                FROM pengguna
                WHERE
                    waktu_berlaku_xp_mingguan = '$endTime' AND
                    id = '$thisUid'
            ") or die(mysqli_connect_error());

            if(mysqli_num_rows($checkUser) > 0) {
                $userData = mysqli_fetch_array($checkUser);
                
                $weeklyXp = (int) $userData['xp_mingguan'] + $bonus;
            }
            else {
                $weeklyXp = $bonus;
            }

            mysqli_query($db, "
                UPDATE pengguna
                SET
                    level = '$level',
                    xp = '$xp',
                    xp_mingguan = '$weeklyXp',
                    waktu_berlaku_xp_mingguan = '$endTime'
                WHERE
                    id = '$thisUid'
            ");
        }

        $resultTmp = mysqli_query($db, "SELECT * FROM pengguna_mengerjakan_set WHERE set_data = '$id' ORDER BY skor DESC") or die(mysqli_connect_error());
        $rank = 0;
        $previousScore = 2147483647;
        while($row = mysqli_fetch_array($resultTmp, MYSQLI_ASSOC)) {
            $score = (int) $row['skor'];
            if($score < $previousScore) {
                $rank++;
            }
            $previousScore = (int) $row['skor'];

            if($rank > 1) {
                break;
            }

            if($rank === 1) {
                $thisUid = $row['pengguna'];
                $achievement = 3;

                $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_achievement WHERE pengguna = '$thisUid' AND achievement = '$achievement'") or die(mysqli_connect_error());

                if(mysqli_num_rows($checkStatus) === 0) {
                    mysqli_query(
                        $db, "
                            INSERT INTO pengguna_memiliki_achievement (
                                pengguna,
                                achievement
                            )
                            VALUES (
                                '$thisUid',
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
                                '$thisUid'
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
                                    '$thisUid',
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
                                    '$thisUid',
                                    '$namecardId'
                                )
                            "
                        );
                    }
                }
            }
        }
    }

    $result = mysqli_query($db, "
        SELECT *
        FROM pengguna
        WHERE waktu_berlaku_xp_mingguan = '$endTime'
        ORDER BY xp_mingguan DESC
    ") or die(mysqli_connect_error());

    $rank = 0;
    $previousXp = 2147483647;
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $xp = (int) $row['xp_mingguan'];
        if($xp < $previousXp) {
            $rank++;
        }
        $previousXp = (int) $row['xp_mingguan'];

        if($rank > 5) {
            break;
        }

        if($rank <= 5) {
            $thisUid = $row['id'];
            $achievement = 4;

            $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_achievement WHERE pengguna = '$thisUid' AND achievement = '$achievement'") or die(mysqli_connect_error());

            if(mysqli_num_rows($checkStatus) === 0) {
                mysqli_query(
                    $db, "
                        INSERT INTO pengguna_memiliki_achievement (
                            pengguna,
                            achievement
                        )
                        VALUES (
                            '$thisUid',
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
                            '$thisUid'
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
                                '$thisUid',
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
                                '$thisUid',
                                '$namecardId'
                            )
                        "
                    );
                }
            }
        }

        if($rank === 1) {
            $thisUid = $row['id'];
            $achievement = 5;

            $checkStatus = mysqli_query($db, "SELECT * FROM pengguna_memiliki_achievement WHERE pengguna = '$thisUid' AND achievement = '$achievement'") or die(mysqli_connect_error());

            if(mysqli_num_rows($checkStatus) === 0) {
                mysqli_query(
                    $db, "
                        INSERT INTO pengguna_memiliki_achievement (
                            pengguna,
                            achievement
                        )
                        VALUES (
                            '$thisUid',
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
                            '$thisUid'
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
                                '$thisUid',
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
                                '$thisUid',
                                '$namecardId'
                            )
                        "
                    );
                }
            }
        }
    }

    echo json_encode($response);
?>