<?php
/**
 * Created by PhpStorm.
 * User: hard
 * Date: 1/18/18
 * Time: 2:57 PM
 */


require_once __DIR__ . '/include/config.php';
require_once __DIR__ . '/include/tsutils.php';
require_once __DIR__ . '/include/dbconnect.php';
require_once __DIR__ . '/include/channelfuntions.php';
require_once __DIR__ . '/include/api/TeamSpeak3.php';

//Creating connections
$tsAdmin = getTeamspeakConnection();
$conn = dbquery();

date_default_timezone_set('Europe/London'); //Change Here!

//passed in by the website
$zgcid = 75;


/**
 * Get data about the users channel
 */

$sqlGet = "SELECT *  FROM channels WHERE zgcid = $zgcid LIMIT 1;";
$result = dbquery($conn, $sqlGet, []);


if (!empty($result)) {
    foreach ($result as $row) {
        $ownerUuid = $row['owner_uuid'];
        $channelName = $row['channel_name'];
        $channelOrder = $row['channel_order'];
        $spacerNumber = $row['spacer_number'];
        $mainCid = $row['main_channel_id'];
        $creationDate = $row['creation_date'];
        $serverGroupId = $row['servergroup_id'];
        $creationDate = $row['creation_date'];
        $nextMove = $row['next_move'];
    }
} else {
    echo "0 results - Error";
}

/**
 * Check current data and compare the next move date.
 */
$currentDate = date("Y-m-d H:i:s");

//TODO: FIX
//if ($nextMove < $currentDate) {
if (true) {

   
    /**
     * Get a list of all the channels that are available.
     * Print all the Free Channels
     */
/*
    $sql = "SELECT `zgcid`, `channel_name`, `channel_order`, `game_area` FROM channels WHERE owner_uuid is Null;";
    $r = dbquery($conn, $sql, []);

  if (!empty($r)) {
    foreach ($r as $row) {
          $newZgcid = $row['zgcid'];
          $newChannelOrder = $row['channel_order'];
          $newChannelName = $row['channel_name'];
          $newGameArea = $row['game_area'];

          echo "Channel ID: " . $newZgcid . " | Channel Name: " . $newChannelOrder . " - " . $newChannelName . " | Game Area: " . $newGameArea;
          echo "<br />";
      }
    } else {
        echo "No Channels Avalaible to Move!";
    }
*/

    /**
     * User has made a desiton on the channel that he wants.
     * TODO
     * TODO
     * TODO
     */

     $pickedZgcid = 76;

    if (true) {



     $mainChannelObj = channelGetById($tsAdmin, $mainCid);
     $subChannelList = subChannelList($tsAdmin,$mainChannelObj);

    freeUpChannels($conn, $tsAdmin, $zgcid, $spacerNumber, $channelOrder, $mainChannelObj, $subChannelList);




    $sqlChannelMembers = "UPDATE `channels_members` SET `zgcid` = $pickedZgcid WHERE `zgcid` = $zgcid;";
    dbquery($conn, $sqlChannelMembers, []);





    /**
     * MOVE TO NEW CHANNEL
     */

/*
    freeup old channels
    claim

    Move users from one group to the other. - SQL
    Set up new Group    - SQL
    Freeup old Channel  - SQL
*/

    $sqlGet = "SELECT * FROM channels WHERE zgcid = $pickedZgcid LIMIT 1;";
    $result = dbquery($conn, $sqlGet, []);

    if (!empty($result)) {
        foreach ($result as $row) {
            $pOwnerUuid = $row['owner_uuid'];
            $pChannelOrder = $row['channel_order'];
            $pMainCid = $row['main_channel_id'];
        }

    } else {
        echo "0 results - Error";
    }

  $ChannelFullName = "[cspacer]" . $pChannelOrder . " - ♦ " . $channelName . " ♦";

  $pMainChannelObj = channelGetById($tsAdmin, $pMainCid);
  $pSubChannelList = subChannelList($tsAdmin, $pMainChannelObj);

  claimExistingChannel($tsAdmin, $pMainChannelObj, $pSubChannelList, $ChannelFullName, "", $channelDescription, $channelTopic);


    /*
     * Store date until next possible change.
     */

    //TODO: Disabled to Admin Mode / Re-enable Later re-enalbe the move time.


    $my_date1 = date('Y-m-d H:i:s', strtotime($currentDate . ' +' . $DaysToMove . ' day'));

    if ($serverGroupId == null) {
      $sqlUpdateChannel = "UPDATE `channels` SET `channel_name` = '$channelName', `owner_uuid` = '$ownerUuid', `creation_date` = '$creationDate', `next_move` = '$my_date1' WHERE `zgcid` = '$pickedZgcid';";

    } else {
      $sqlUpdateChannel = "UPDATE `channels` SET `channel_name` = '$channelName', `owner_uuid` = '$ownerUuid', `creation_date` = '$creationDate', `next_move` = '$my_date1', `servergroup_id` = '$serverGroupId' WHERE `zgcid` = '$pickedZgcid';";

    }

    dbquery($conn, $sqlUpdateChannel, []);

    $sqlFreeUp = "UPDATE `channels` SET `channel_name` = 'free', `owner_uuid` = NULL, `servergroup_id` = NULL WHERE `zgcid` = $zgcid;";
    dbquery($conn, $sqlFreeUp, []);

    //Disabled to Admin Mode / Re-enable Later
    //fixChannelAdmin($conn, $tsAdmin, $pickedZgcid, $pSubChannelList);

 }

} else {


    $date = strtotime($nextMove); //Converted to a PHP date (a second count)
    //Calculate difference
    $remaining = $date - time();//time returns current time in seconds
    $days_remaining = floor($remaining / 60 / 60 / 24);
    $hours_remaining = floor(($remaining - ($days_remaining * 60 * 60 * 24)) / 60 / 60);
    $minutes_remaining = floor(($remaining - ($days_remaining * 60 * 60 * 24) - ($hours_remaining * 60 * 60)) / 60);
    $seconds_remaining = floor(($remaining - ($days_remaining * 60 * 60 * 24) - ($hours_remaining * 60 * 60)) - ($minutes_remaining * 60));

    //Report
    echo "Sorry you need to wait " . $days_remaining . " day(s), " . $hours_remaining . " hour(s), " . $minutes_remaining . " minute(s) and " . $seconds_remaining . " second(s), until you can move your channel again!";


}
?>
