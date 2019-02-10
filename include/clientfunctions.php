<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/tsutils.php';
require_once __DIR__ . '/channelfuntions.php';
require_once __DIR__ . '/clientfunctions.php';
require_once __DIR__ . '/api/TeamSpeak3.php';

function channelGetById($tsAdmin, $cid) {
    try {
        return $tsAdmin->channelGetById($cid);
    } catch (TeamSpeak3_Exception $e) {
        return false;
    }
}

function subChannelList($tsAdmin,$channelObj) {
    try {
        return $channelObj->subChannelList();
    } catch (TeamSpeak3_Exception $e) {
        return false;
    }
}

function countArray($arrayIn) {
  $numberOfRows=0;
  foreach ($arrayIn as $arrayP) {
    $numberOfRows++;
  }
        return $numberOfRows;
}

?>
