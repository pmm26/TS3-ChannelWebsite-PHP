<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../include/tsutils.php';
require_once __DIR__ . '/../include/channelfuntions.php';
require_once __DIR__ . '/../include/clientfunctions.php';
require_once __DIR__ . '/../lib/ts3phpframework/libraries/TeamSpeak3/TeamSpeak3.php';

$tsAdmin = getTeamspeakConnection();
$conn = createDatabaseConnection();

$cid = 813;
$channelObj = channelGetById($tsAdmin, $cid);
$channelName = $channelObj->__toString();
echo $channelName;
echo "<br>";

//Get and array filled with object of all the sub channels
$subChannelList = subChannelList($tsAdmin,$channelObj);

$tsAdmin->clientSetChannelGroup(2,766,5);
//Iterate throw the array
foreach ($subChannelList as $channel) {
  //get the id of the channel (cid)
 echo $channel->getId ();
 echo " - ";

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
    echo $clients->__toString();
    echo "<br>";
  }
  echo "<br>";
  echo "<br>";

}
?>
