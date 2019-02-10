<?php
/**
 * Created by PhpStorm.
 * User: hard
 * Date: 1/7/18
 * Time: 8:20 PM
 */

 require_once __DIR__ . '/../config/config.php';
 require_once __DIR__ . '/../include/tsutils.php';
 require_once __DIR__ . '/../include/channelfuntions.php';
 require_once __DIR__ . '/../lib/ts3phpframework/libraries/TeamSpeak3/TeamSpeak3.php';
 require_once __DIR__ . '/channelPanel.php';

$tsAdmin = getTeamspeakConnection();

//Create a new MySQL Connection
$conn = createDatabaseConnection();


if (isset($_POST['zgcid'])) {
     $zgcid = $_POST['zgcid'];
} else {
  exit;
}

echo "data posted";

$sIp = getClientIp();
$cuid = getUidByIp($tsAdmin);
$channel = getUserZgcid($tsAdmin, $conn, $cuid);
$valid = validator($channel, $zgcid);

$valid = false;

foreach ($channel["zgcid"] as $i => $zgcidf) {
  if (($zgcidf == $zgcid) & ($channel["rank"][$i] >= 2) ) {
    $valid = true;
  }
}


if ($valid) {
  //Get the data from the table
  $sqlGet = "SELECT `channel_name`, `servergroup_id`  FROM channels WHERE `zgcid` = $zgcid LIMIT 1";
  $result = simpleQuery($conn, $sqlGet);


  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $ChannelName = $row["channel_name"];
          $group_id = $row["servergroup_id"];
          $name = $row["channel_name"];
      }
  } else {
      echo "0 results - Error";
  }

  if ($group_id == NULL) {


      /**
       * Create the group for the channel
       */
      $tsgid = 0;
      $type = 1;
      $tsAdmin->serverGroupListReset();

      try {
      $serverGroupId = $tsAdmin->serverGroupCopy($groupTemplateId, $ChannelName, $tsgid, $type);
    } catch (Exception $e) {
      echo "Failed to create Server Group!" ,  $e->getMessage();
    }
      /**
       * Get UID of all the users part of the team.
       */


      $sqlGetUID = "SELECT `cldbid` FROM channels_members WHERE `zgcid` = $zgcid;";
      $r1 = simpleQuery($conn, $sqlGetUID);

      while ($row = $r1->fetch_assoc()) {
          $cldbid = $row['cldbid'];
          /**
           * Add user to group
           */
           try {
          $tsAdmin->serverGroupClientAdd($serverGroupId, $cldbid);
        } catch (Exception $e) {
          echo "Failed to add user to group!"  ,  $e->getMessage();
        }
      }


      /**
       * Add the group id to the Channel Database.
       */

      $sqlAddGroupIdtoChannel = "UPDATE `channels` SET  `servergroup_id` = '$serverGroupId' WHERE `zgcid` = $zgcid;";
      simpleQuery($conn, $sqlAddGroupIdtoChannel);
  } else {
    echo "Group Already Exist!";
  }
} else {
  echo "Invalid Request!";
}
?>
