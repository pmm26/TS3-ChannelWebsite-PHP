<?php

require_once __DIR__ . '/include/config.php';
require_once __DIR__ . '/include/tsutils.php';
require_once __DIR__ . '/include/dbconnect.php';
require_once __DIR__ . '/include/channelfuntions.php';
require_once __DIR__ . '/include/api/TeamSpeak3.php';



date_default_timezone_set('Europe/London'); //Change Here!
//error_reporting(0);
session_start();
//if(!isset($_SESSION['ts3_last_query']))
//    $_SESSION['ts3_last_query'] = microtime(true);

//if($_SESSION['ts3_last_query'] >= microtime(true))
//    die('I am sorry! You are entitled to a single channel on UNIQ-ID !.');

//$_SESSION['ts3_last_query'] = microtime(true)+10.0;//10 Secounds banned for create another channel

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


$tsAdmin = getTeamspeakConnection();
//Create a new MySQL Connection
$conn = mysqlConnection();

$ipclient = getRealIpAddr();

//Variables from the form
$gameArea = $_POST['garea'];
$channelName = $_POST['cname'];
$ChannelPassword = $_POST['cpass'];
$email = $_POST['email'];
$cuid = $_POST['uuid'];
$unixTime = time();
$realTime = date('[d-m-Y]-[H:i]', $unixTime);

$claimChannel = false;
$channelCreate = false;
$currentDate = date("Y-m-d H:i:s");
$lowestChannelNumber = Null;

if (!isset($gameArea) & !isset($channelName) & !isset($ChannelPassword) & !isset($cuid) & !isset($email)) {
    die;
}

$ListaDeChannels = $tsAdmin->request("channellist")->toString();

// GET DB ID
$clObj = $tsAdmin->clientGetByUid($cuid);
$db_id = $clObj->infoDb();
$cldbid = $db_id['client_database_id'];

/**
 * check if there is a free channel
 */
$sqlFreeCheck = "SELECT * FROM `channels` WHERE `game_area` = $gameArea AND `owner_uuid` IS NULL";

$r = dbquery($conn, $sqlFreeCheck, []);

if (!empty($r)) {
    foreach ($r as $row) {
 
        $channelOrder = $row["channel_order"];

        if ($channelOrder < $lowestChannelNumber || $lowestChannelNumber == Null ) {
            $lowestChannelNumber = $channelOrder;
            $zgcid = $row['zgcid'];
            $mainCid = $row['main_channel_id'];

        }
    }
    $claimChannel = true;
}
else {
    $channelCreate = true;
}

/**
 * Claim an already existing Channel
 */

if ($claimChannel) {

    /**
     * Claim Channel
     */

    $mainChannelObj = channelGetById($tsAdmin, $mainCid);
    $subChannelList = subChannelList($tsAdmin,$mainChannelObj);

    //Variables
    $ChannelFullName = "[cspacer]" . $lowestChannelNumber . " - ♦ " . $channelName . " ♦";
    $subChannelListC = claimExistingChannel($tsAdmin, $mainChannelObj, $subChannelList, $ChannelFullName, $ChannelPassword, $channelDescription, $channelTopic);


    /**
     * Write Changes to the DB
     */

    $sqlChannels = "UPDATE `channels` SET `channel_name` = '$channelName', `owner_uuid` = '$cuid' WHERE `zgcid` = '$zgcid';";
    dbquery($conn, $sqlChannels, []);


    $sqlUser = "INSERT INTO channels_members ( `zgcid`, `uuid`, `cldbid`, `rank`, `email`, `ip` )
    VALUES ( $zgcid , '$cuid' , $cldbid , 3, '$email' , '$ipclient');";
    dbquery($conn, $sqlUser, []);

    /**
     * TODO: remove entries from the Channel Deleter to avoid deletion.
     */
}

/**
 * Create a New Channel
 */


if ($channelCreate) {

    //Get the data from the table
    $sqlGet = "SELECT * FROM game_area WHERE id=$gameArea LIMIT 1";
    $result = dbquery($conn, $sqlGet, []);


    if (!empty($result)) {
        foreach ($result as $row) {
            $lastChannelNumber = $row["last_channel_number"];
            $lastChannel = $row["last_channel_id"];
            $spacerNumber = $row["spacer_number"];
        }
    } else {
        echo "0 results - Error";
    }


    //Variables
    $channelOrder = ++$lastChannelNumber;
    $ChannelFullName = "[cspacer]" . $channelOrder . " - ♦ " . $channelName . " ♦";
    $spacerNumber = ++$spacerNumber;
    $spacerBar = "[*spacer" . $spacerNumber . "]▂▂▂▂";
    $spacerNumber = ++$spacerNumber;
    $spacerEmpty = "[rspacer" . $spacerNumber . "]";


    if (!$ChannelFullName || !$cuid) {
        header("location: error.html");
        exit();
    }

    /**
     * Create the channels
     */

    if (strpos($ListaDeChannels, $ChannelFullName)) {
        header("location: error.html");
        exit();
    }

  /**
   * Create Channels
   */

    $spacer_cid = CreateChannel($tsAdmin, $spacerBar, "", "", "", $lastChannel, false);
    $main_cid = CreateChannel($tsAdmin, $ChannelFullName, "", $channelDescription, $channelTopic, $spacer_cid, false);

      $sub1_cid = CreateSubChannel($tsAdmin, $SubChannelName1, $ChannelPassword, $channelDescription, $channelTopic, $main_cid, true);
      $sub2_cid = CreateSubChannel($tsAdmin, $SubChannelName2, $ChannelPassword, $channelDescription, $channelTopic, $main_cid, true);
      $sub3_cid = CreateSubChannel($tsAdmin, $SubChannelName3, $ChannelPassword, $channelDescription, $channelTopic, $main_cid, true);
      $sub4_cid = CreateSubChannel($tsAdmin, $SubChannelName4, $ChannelPassword, $channelDescription, $channelTopic, $main_cid, true);
    $spacerE_cid = CreateChannel($tsAdmin, $spacerEmpty, "", "", "", $main_cid, false);




    /**
     * Write Changes to the DB
     */
//    $sqlWrite = "UPDATE `game_area` SET `last_channel_number` = '$channelOrder' , `last_channel_id` = '$spacerE_cid', `spacer_number` = '$spacerNumber'  ";

    $sqlWrite = "UPDATE `game_area` SET `last_channel_number` = '$channelOrder', `last_channel_id` = '$spacerE_cid', `spacer_number` = '$spacerNumber' WHERE `id` = '$gameArea';";
    dbquery($conn, $sqlWrite, []);


    $sqlChannels = "INSERT INTO channels ( `channel_name`, `owner_uuid`, `game_area`, `channel_order`, `spacer_number`, `spacer_bar_id`, `main_channel_id`, `spacer_empty_id`, `servergroup_id`,  `next_move`,  `creation_date` )
    VALUES ( '$channelName' , '$cuid' , '$gameArea', '$channelOrder', '$spacerNumber', '$spacer_cid', '$main_cid', '$spacerE_cid' , NULL, '$currentDate', '$currentDate'  );";
    dbquery($conn, $sqlChannels, []);


    $zgcid = $conn->lastInsertId();

    $sqlUser = "INSERT INTO channels_members ( zgcid, uuid, cldbid, rank, email, ip)
    VALUES ( $zgcid , '$cuid', $cldbid, 3 , '$email' , '$ipclient');";
    dbquery($conn, $sqlUser, []);


    /**
     * Get an array filled with objects of the sub channels
     */
     $mainChannelObj = channelGetById($tsAdmin, $main_cid);
     $subChannelListC = subChannelList($tsAdmin,$mainChannelObj);


}

addChannelAdmin($tsAdmin, $cldbid, $subChannelListC, $channelgroup, true);


header("location: index.php");

?>
