<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../include/tsutils.php';
require_once __DIR__ . '/../include/channelfuntions.php';
require_once __DIR__ . '/../include/clientfunctions.php';
require_once __DIR__ . '/../lib/ts3phpframework/libraries/TeamSpeak3/TeamSpeak3.php';

$tsAdmin = getTeamspeakConnection();
$conn = createDatabaseConnection();


editChannel($tsAdmin, 772, true, "Error!", "123", $channelDescription, $channelTopic, false);

?>
