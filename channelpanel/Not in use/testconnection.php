<?php
/**
 * Created by PhpStorm.
 * User: hard
 * Date: 1/5/18
 * Time: 8:16 PM
 */
 require_once __DIR__ . "/../config/config.php";
 include ('funtions.php');
 require_once __DIR__ . "/../lib/ts3phpframework/libraries/TeamSpeak3/TeamSpeak3.php";


//Create a new MySQL Connection
$conn = createDatabaseConnection();


$cuid = "smgDpLgFrZmV2FrK/PwWvFCA2Pw=";
$Game = "1";
$NumberChannel = "1";
$spacer_cid = "1";
$main_cid = "1";
$sub1_cid = "1";
$sub2_cid = "1";
$sub3_cid = "1";
$sub4_cid = "1";
$spacerE_cid = "1";


//Get the data from the table
$sqlGet = "SELECT id, game , last_channel_number , last_channel_id , spacer_name_number , spacer_number FROM channel_area WHERE id=$Game LIMIT 1";
$result = $conn->query($sqlGet);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $ChannelNumber = $row["last_channel_number"];
        $lastchannel = $row["last_channel_id"];
        $spacerNameNumber = $row["spacer_name_number"];
        $spacerNumber = $row["spacer_number"];
    }
} else {
    echo "0 results - Error";
}


$sqlChannels = "INSERT INTO channels ( channel_creator_id, game_area_id, channel_number, spacer_bar_id, channel_name_id, sub_channel1_cid, sub_channel2_cid, sub_channel3_cid, sub_channel4_cid, spacer_empty_id )
VALUES ( '$cuid' , $Game, $NumberChannel, $spacer_cid, $main_cid, $sub1_cid, $sub2_cid, $sub3_cid, $sub4_cid, $spacerE_cid);";


if ($conn->query($sqlChannels) === TRUE) {
    echo "Successfull!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$sqlUser = "INSERT INTO channel_members ( channel_id, client_uid, rank, email, last_ip )
VALUES ( 1 , 'smgDpLgFrZmV2FrK/PwWvFCA2Pw=' , 3 , '123' , '92.1.9.204');";

if ($conn->query($sqlUser) === TRUE) {
    echo "Successfull!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

echo $spacerNameNumber;

echo $Game;
echo $NumberChannel;
echo $spacer_cid;
echo $main_cid;
echo $sub1_cid;
echo $sub2_cid;
echo $sub3_cid;
echo $sub4_cid;
echo $spacerE_cid;


?>
