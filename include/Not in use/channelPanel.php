<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../include/tsutils.php';
require_once __DIR__ . '/../include/mysqlconnection.php';
require_once __DIR__ . '/../include/channelfuntions.php';
require_once __DIR__ . '/../include/api/TeamSpeak3.php';

function validator($channel, $zgcid) {
  foreach ($channel["zgcid"] as $i => $zgcidf) {
    if (($zgcidf == $zgcid) & ($channel["rank"][$i] >= 2) ) {
      return true;
    }
  }
  return false;
}


function getUserZgcid($tsAdmin, $conn, $cuid) {
  //Channel 1
  $m = 1;
  /**
  * Get the ZGCID
  */
  $sqlMembers = "SELECT * FROM `channels_members` WHERE `uuid` = '$cuid'";
  $rm = simpleQuery($conn, $sqlMembers);

  if ($rm->num_rows > 0) {
    while ($row = $rm->fetch_assoc()) {
      $channel["zgcid"][$m] = $row['zgcid'];
			$channel["rank"][$m] = $row['rank'];

      $m++;
    }
  } else {
    return false;
  }
  return $channel;
}

function getGenerateTeam($tsAdmin, $conn, $cuid) {
  //Channel 1
  $m = 1;
  /**
  * Get the ZGCID
  */
  $sqlMembers = "SELECT * FROM `channels_members` WHERE `uuid` = '$cuid'";
  $rm = simpleQuery($conn, $sqlMembers);

  if ($rm->num_rows > 0) {
    while ($row = $rm->fetch_assoc()) {
      $zgcid[$m] = $row['zgcid'];
			$channel["rank"][$m] = $row['rank'];

      $m++;
    }
  } else {
    echo "No Channels have been found!";

  }

  for ($c=1; $c < $m ; $c++) {
		//Get the Name of the Team and other information about the team.
			$sqlChannel = "SELECT * FROM `channels` WHERE `zgcid` = '$zgcid[$c]'";
			$rc = simpleQuery($conn, $sqlChannel);

		if ($rc->num_rows > 0) {
			while ($row = $rc->fetch_assoc()) {
			  $channel["channel_name"][$c] = $row['channel_name'];
				$channel["game_area"][$c] = $row['game_area'];
				$channel["cid"][$c] = $row['main_channel_id'];
				$channel["creation_date"][$c] = $row['creation_date'];
		  }
		} else {
			$teamName = "Channel Not Found!";
		}
	}

  //temporary
	$teamRank = 0;
	$numberMembers = 0;

  //Generate multipe for Multiple channels.

  for ($i=1; $i < $c ; $i++) {
  channelpanel($tsAdmin, $zgcid[$i], $channel["cid"][$i], $channel["channel_name"][$i], $channel["rank"][$i], $channel["game_area"][$i], $cuid, $teamRank, $numberMembers, $channel["creation_date"][$i] );
  }

}

function channelpanel($tsAdmin, $zgcid, $mainCid, $channelName, $rank, $gameArea, $clientuid, $teamRank, $numberMembers, $creationDate ) {

  echo "<h1>Your Team</h1>";
  echo "<p>Name: <b>" . $channelName . "</b></p>";
  echo "<p>Game area: <b>" . $gameArea . "</b></p>";
  echo "<p>Team Rank: <b>" . $channelName . "</b></p>";
  echo "<p>Number of Members: <b>" . $numberMembers . "</b></p>";
  echo "<p>Creation Date: <b>" . $numberMembers . "</b></p>";

?>

  <form action = "channelpanel/createServerGroup.php" method = "post">
  <input type="hidden" name="zgcid" value="<?php echo $zgcid; ?>" />
  <input type="submit" name="submitter" value="Create Server Group!" />
  </form>

<?php
  echo "<div>";
  $channelObj = channelGetById($tsAdmin, $mainCid);
  $channelName = $channelObj->__toString();
  echo "<br>";

  //Get and array filled with object of all the sub channels
  $subChannelList = subChannelList($tsAdmin,$channelObj);

  //$tsAdmin->clientSetChannelGroup(2,766,5);
  //Iterate throw the array

  foreach ($subChannelList as $channel) {

 //get the name of the channel)
  echo $channel->__toString();

  //Get an array with objects of all the clients in the room.
  $clientList = $channel->clientList();
  echo " - ";

  //Count the number of clients in the room
  echo countArray($clientList);

  echo "<br>";
  //Iterate throw the array
  foreach ($clientList as $clients) {
    //get the name of the Client)
    echo "<li>";
    echo $clients->__toString();
    echo "<br>";
  }
  echo "<br>";
  echo "<br>";
}


echo "</div>";
}
?>
