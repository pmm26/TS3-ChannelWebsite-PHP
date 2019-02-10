<?php
/**
 * Created by PhpStorm.
 * User: hard
 * Date: 1/6/18
 * Time: 12:26 AM
 */
 require_once __DIR__ . '/include/config.php';
 require_once __DIR__ . '/include/dbconnect.php';
 require_once __DIR__ . '/include/tsutils.php';
 require_once __DIR__ . '/include/channelfuntions.php';
 require_once __DIR__ . '/include/api/TeamSpeak3.php';

$tsAdmin = getTeamspeakConnection();
$conn = mysqlConnection();

$sql = "SELECT * FROM channels WHERE  owner_uuid IS NOT NULL";
$r = dbquery($conn, $sql, []);




foreach ($r as $row) {
    $zgcid = $row['zgcid'];
    $cuid = $row['owner_uuid'];
    $channelOrder = $row['channel_order'];
    $spacerNumber = $row['spacer_number'];
    $mainCid = $row['main_channel_id'];
    $serverGroupId = $row['servergroup_id'];

    $binCount = 0;

    $mainChannelObj = channelGetById($tsAdmin, $mainCid);
    $subChannelList = subChannelList($tsAdmin, $mainChannelObj);

    //TODO: if $subChannelList is false give and an error.

    if (true) {

      $subChannelCount = countArray($subChannelList);
      foreach ($subChannelList as $SubChannelObj) {
        $ChannelId= $SubChannelObj->getId();

        //Check Icon & Count
        $checkicon = $tsAdmin->channelPermList($ChannelId, $permsid = FALSE);
        foreach ($checkicon as $rows) {
            if ($rows["permvalue"] == "301694691") {
                $binCount++;
            }
        }
      }

        /**
         *If the channel hasn't been used in X amount of time
         */

        if ($binCount === $subChannelCount) {

            freeUpChannels($conn, $tsAdmin, $zgcid, $spacerNumber, $channelOrder, $mainChannelObj, $subChannelList);

                /**
                 * Delete group
                 */
                if ($serverGroupId != NULL) {

                    $tsAdmin->serverGroupDelete($serverGroupId, true);

                }

                /**
                 * Removes users from the database.
                 */

                $sqlChangeOwnerNull = "UPDATE `channels` SET `channel_name` = 'free', `owner_uuid` = NULL, `servergroup_id` = NULL WHERE `zgcid` = $zgcid;";
                dbquery($conn, $sqlChangeOwnerNull, []);


                $removeChannelMembers = "DELETE FROM channels_members WHERE `zgcid` = $zgcid;";
                dbquery($conn, $removeChannelMembers, []);



                //TODO: FIX THE OBJECTS REMOVAL AND SHIT FUCK
            }

        //end of if
    }
}



?>
