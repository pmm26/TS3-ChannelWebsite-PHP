<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../include/tsutils.php';
require_once __DIR__ . '/../include/channelfuntions.php';
require_once __DIR__ . '/../include/clientfunctions.php';
require_once __DIR__ . '/../lib/ts3phpframework/libraries/TeamSpeak3/TeamSpeak3.php';

addChannelAdmin($tsAdmin, $cldbid, $subChannelListC, $channelgroup, true);

$tsAdmin = getTeamspeakConnection();
$conn = createDatabaseConnection();

$adminChannelGroup = $configChannel['channel']['adminChannelGroup'];

$cid = 994;
$cldbid = 2;
//$clUid = $tsAdmin->clientGetByUid("slOGgvdBuVrdr5EAPVdtdpiO2I8=");
//$dbID = $tsAdmin->clientGetByDbid(14);
//$
$mainChannelObj = channelGetById($tsAdmin, 992);
$subChannelList = subChannelList($tsAdmin,$mainChannelObj);

addChannelAdmin($tsAdmin, $cldbid, $subChannelList, $adminChannelGroup, true);


//$db_id = $clUid->infoDb();
//$clId = $tsAdmin->clientGetById(13);
echo "<br> START";
//var_dump($clUid);
echo "<br> BREAK";
//var_dump($dbID);
echo "<br> BREAK";

var_dump($dbID);
//echo $db_id['client_database_id'];
//echo $clUid->getParent()->clientInfoDb($clUid["client_database_id"]);


echo "<br>END";

?>
