<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/tsutils.php';
require_once __DIR__ . '/channelfuntions.php';
require_once __DIR__ . '/clientfunctions.php';
require_once __DIR__ . '/api/TeamSpeak3.php';

/**
 * Created by PhpStorm.
 * User: hard
 * Date: 1/18/18
 * Time: 6:04 PM
 */

/**
 * [CreateChannel description]
 * @param [type] $tsAdmin            [description]
 * @param [type] $ChannelName        [description]
 * @param [type] $ChannelPassword    [description]
 * @param [type] $channelDescription [description]
 * @param [type] $channelTopic       [description]
 * @param [type] $lastchannel        [description]
 * @param [type] $public             [description]
 */
 function CreateChannel($tsAdmin, $ChannelName, $ChannelPassword, $channelDescription, $channelTopic, $lastchannel, $public) {
  if ($public){
    $maxclients = -1;
  } else {
    $maxclients = 0;
  };
  $id = $tsAdmin->channelCreate(array(
      "channel_name" => $ChannelName,
      "channel_description" => "$channelDescription",
      "channel_codec" => TeamSpeak3::CODEC_OPUS_VOICE,
      "channel_codec_quality" => '10',
      "channel_topic" => "$channelTopic",
      "channel_flag_permanent" => TRUE,
      "channel_maxclients" => "$maxclients",
      "channel_maxfamilyclients" => "$maxclients",
      "channel_flag_maxclients_unlimited" => $public,
      "channel_flag_maxfamilyclients_unlimited" => $public,
      "channel_flag_maxfamilyclients_inherited" => $public,
      "channel_order" => $lastchannel,
  ));
    return $id;
 }


/**
 * Create Sub Channel
 * @param [Object]  $tsAdmin            [The Server]
 * @param [String]  $SubChannelName1    [Name of the Channel]
 * @param [String]  $ChannelPassword    [Password of the Channel]
 * @param [String]   $channeldescription [Channel Description]
 * @param [String]  $channelTopic       [Channel Topic]
 * @param [int]     $main_cid           [Id of Parent Channel]
 * @param [boolean] $public             [Is the Channel locked or not]
 */
function CreateSubChannel($tsAdmin, $SubChannelName, $ChannelPassword, $channeldescription, $channelTopic, $main_cid, $public) {
  if ($public){
    $maxclients = -1;
  } else {
    $maxclients = 0;
  };
 $id = $tsAdmin->channelCreate(array(
     "channel_name" => $SubChannelName,
     "channel_password" => $ChannelPassword,
     "channel_description" => "$channeldescription",
     "channel_codec" => TeamSpeak3::CODEC_OPUS_VOICE,
     "channel_codec_quality" => '10',
     "channel_topic" => "$channelTopic",
     "channel_flag_permanent" => TRUE,
     "channel_maxclients" => "$maxclients",
     "channel_maxfamilyclients" => "$maxclients",
     "channel_flag_maxclients_unlimited" => $public,
     "channel_flag_maxfamilyclients_unlimited" => $public,
     "channel_flag_maxfamilyclients_inherited" => $public,
     "cpid" => $main_cid,
   ));
   return $id;
}
/**
 * [editChannel description]
 * @param  [type] $tsAdmin            [description]
 * @param  [type] $channelObj         [description]
 * @param  [type] $channelId          [description]
 * @param  [type] $changeName         [description]
 * @param  [type] $ChannelName        [description]
 * @param  [type] $ChannelPassword    [description]
 * @param  [type] $channelDescription [description]
 * @param  [type] $channelTopic       [description]
 * @param  [type] $public             [description]
 * @return [type]                     [description]
 */
function editChannel($tsAdmin, $channelObj, $channelId, $changeName, $ChannelName, $ChannelPassword, $channelDescription, $channelTopic, $public) {
  if ($public){
    $maxclients = -1;
  } else {
    $maxclients = 0;
  };

  $channelArray = array(
    "cid" => $channelId,
    "channel_password" => $ChannelPassword,
    "channel_description" => $channelDescription,
    "channel_codec" => TeamSpeak3::CODEC_OPUS_VOICE,
    "channel_codec_quality" => '10',
    "channel_topic" => $channelTopic,
    "channel_flag_permanent" => TRUE,
    "channel_maxclients" => $maxclients,
    "channel_maxfamilyclients" => $maxclients,
    "channel_flag_maxclients_unlimited" => $public,
    "channel_flag_maxfamilyclients_unlimited" => $public,
    "channel_name" => $ChannelName
  );

  if (!$changeName) {
    array_pop($channelArray);
    //echo $removed;
  }

  //If an object is passed in just use the object instead of querring the server about the id.
  if ((!$channelObj==NULL) && ($channelId == 0)) {
    $removed = array_shift($channelArray);
    try {
        $channelObj->modify($channelArray);
    }
    catch (Exception $e) {
      if ($changeName) {
        array_pop($channelArray);
      }
      $channelObj->modify($channelArray);
    }

} else {
  /**
   * Change the channel names to the default
   */
    try {
        $tsAdmin->execute("channeledit", $channelArray);
    } catch (Exception $e) {
      if ($changeName) {
        array_pop($channelArray);
      }
      $tsAdmin->execute("channeledit", $channelArray);
    }
  }
}

function fixChannelAdmin($conn, $tsAdmin, $zgcid, $subChannelList ) {
  global $configChannel;
  $adminChannelGroup = $configChannel['channel']['adminChannelGroup'];
  $coAdminChannelGroup = $configChannel['channel']['coAdminChannelGroup'];
  $memberChannelGroup = $configChannel['channel']['memberChannelGroup'];

$sqlChannelMember = "SELECT * FROM `channels_members` WHERE zgcid = $zgcid";
$result = dbquery($conn, $sqlChannelMember, []);

if (!empty($result)) {
    foreach ($result as $row) {
        $cldbid = $row['cldbid'];
        $rank = $row['rank'];

        switch ($rank) {
          case '0': //membro
            addChannelAdmin($tsAdmin, $cldbid, $subChannelList, $memberChannelGroup, true);
            break;

          case '1': //Co-Lider
            addChannelAdmin($tsAdmin, $cldbid, $subChannelList, $coAdminChannelGroup, true);
            break;

          case '2': //Lider
            addChannelAdmin($tsAdmin, $cldbid, $subChannelList, $adminChannelGroup, true);
            break;

          case '3': //Founder
            addChannelAdmin($tsAdmin, $cldbid, $subChannelList, $adminChannelGroup, true);
            break;
        }
    }
  } else {
      echo "0 results - Error";
  }
}


/**
 * [addChannelAdmin description]
 * @param [type] $clObj           [description]
 * @param [type] $subChannelList [description]
 * @param [type] $channelgroup    [description]
 * @param [type] $move            [description]
 */
function addChannelAdmin($tsAdmin, $cldbid, $subChannelList, $channelgroup, $move) {
//Set Channel Group && move Client
  $count=0;
  foreach ($subChannelList as $SubChannelObj) {
    $cid = $SubChannelObj->getId();
    if ($count == 0 && $move) {
      try {
        $clObj = $tsAdmin->clientGetByDbid($cldbid);
        $clObj->move($cid);
      }
      catch (Exception $e) {
        echo "Error Moving Client";
      }
    }
  $tsAdmin->clientSetChannelGroup($cldbid, $cid, $channelgroup);
  $count++;
  }
}

/**
 * Claim a Set of Channels
 * @param  [object] $tsAdmin         [The Server]
 * @param  [String] $contact         [description]
 * @param  [int] $clID            [description]
 * @param  [String] $cuid         [description]
 * @param  [time] $realTime        [description]
 * @param  [String] $ChannelFullName [description]
 * @param  [String] $ChannelPassword [description]
 * @param  [int] $mainCid   [id of the Parent Channel]
 * @param  [int] $sub1_cid        [If of the the first sub-channel]
 * @param  [int] $sub2_cid        [If of the the second sub-channel]
 * @param  [int] $sub3_cid        [If of the the third sub-channel]
 * @param  [int] $sub4_cid        [If of the the fourth sub-channel]
 */
function claimExistingChannel($tsAdmin, $mainChannelObj, $subChannelList, $ChannelFullName, $ChannelPassword, $channelDescription, $channelTopic) {
  global $defSubChannelName;
    /**
     * Change the channel names to the user specified name
     */
    editChannel($tsAdmin, $mainChannelObj, 0, true, $ChannelFullName, $ChannelPassword, $channelDescription, $channelTopic, false);
    /**
     * make the SubChannels available again
     */

     $newChannelCount = 0;
     foreach ($subChannelList as $SubChannelObj) {
       $newChannelCount++;
       $newSubChannelName = $defSubChannelName . " " . $newChannelCount;
       editChannel($tsAdmin, $SubChannelObj, 0, true, $newSubChannelName, $ChannelPassword, $channelDescription, $channelTopic, true);
     }
  return $subChannelList;
}


/**
 * [freeUpChannels description]
 * @param  [type] $zgcid        [description]
 * @param  [type] $spacerNumber  [description]
 * @param  [type] $channelOrder     [description]
 * @param  [type] $FreeChannelName   [description]
 * @param  [type] $mainCid     [description]
 * @param  [type] $subChannelId1     [description]
 * @param  [type] $subChannelId2     [description]
 * @param  [type] $subChannelId3     [description]
 * @param  [type] $subChannelId4     [description]
 * @param  [type] $tsAdmin           [description]
 * @param  [type] $SubChannelName1   [description]
 * @param  [type] $SubChannelName2   [description]
 * @param  [type] $SubChannelName3   [description]
 * @param  [type] $SubChannelName4   [description]
 * @param  [type] $defaultPassoword  [description]
 * @param  [type] $conn              [description]
 * @param  [type] $guestchannelgroup [description]
 * @return [type]                    [description]
 */
function freeUpChannels($conn, $tsAdmin, $zgcid, $spacerNumber, $channelOrder, $mainChannelObj, $subChannelList) {
  global $configChannel;
  $freeSpacerName = $configChannel['channel']['freeSpacerName'];
  $freeChannelName = $configChannel['channel']['subChannelName'];
  $freeChannelTopic = $configChannel['channel']['freeChannelTopic'];
  $freeChannelDescription = $configChannel['channel']['freeChannelDescription'];
  $guestchannelgroup = $configChannel['channel']['guestChannelGroup'];
    /**
     * Change the Spacer to "Vaga"
     */
    $SpacerChannelNameF = "[cspacer" . $spacerNumber . "]" . $channelOrder . " - " . $freeSpacerName;

    //change the Spacer name

    editChannel($tsAdmin, $mainChannelObj, 0, true, $SpacerChannelNameF, "", $freeChannelDescription, $freeChannelTopic, false);

    //get the object of the channel and the list of channels



    //change the subchannels name
    $newChannelCount=0;
    foreach ($subChannelList as $SubChannelObj) {
      $newChannelCount++;
      $newSubChannelName = $freeChannelName . " " . $newChannelCount;
      editChannel($tsAdmin, $SubChannelObj, 0, true, $newSubChannelName, "", $freeChannelDescription, $freeChannelTopic, false);
    }

    /**
     * Get the DB ID of the Client to later remove the channel admin
     */

    $sqlGetUID = "SELECT `cldbid` FROM channels_members WHERE `zgcid` = $zgcid;";
    $r1 = dbquery($conn, $sqlGetUID, []);

    foreach ($r1 as $row) {
        $cldbid = $row['cldbid'];
            addChannelAdmin($tsAdmin, $cldbid, $subChannelList, $guestchannelgroup, false);
  }
}

?>
