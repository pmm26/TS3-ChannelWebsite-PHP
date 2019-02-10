<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/api/TeamSpeak3.php';

function pingTeamspeakServerFromConfig() {
    return pingTeamspeakServer(getTeamspeakConnection("?use_offline_as_virtual=1&no_query_clients=1"));
}

function pingTeamspeakServer() {
    try {
        $tsAdmin = getTeamspeakConnection();

        if ($tsAdmin->isOffline())
            throw new Exception("Server is offline");

        return $tsAdmin->getInfo();
    } catch (TeamSpeak3_Exception $e) {
        return false;
    }
}

function getTeamspeakConnection($arguments = '') {
    try {
        global $config;
        $host       = $config['teamspeak']['host'];
        $login      = $config['teamspeak']['login'];
        $passwd     = $config['teamspeak']['password'];
        $sport      = $config['teamspeak']['server_port'];
        $qport      = $config['teamspeak']['query_port'];

        $tsNodeHost = TeamSpeak3::factory("serverquery://$host:$qport/$arguments");
        $tsNodeHost->login($login, $passwd);
        $tsAdmin = $tsNodeHost->serverGetByPort($sport);
        changeQuerryName($tsAdmin);
        return $tsAdmin;
    } catch (Exception $e) {
        throw $e;
    }

}

function changeQuerryName($tsAdmin) {
  global $config;
  $queryname  = $config['teamspeak']['queryname2'];
  $queryname2 = $config['teamspeak']['queryname2'];
  try
  {
    $tsAdmin->selfUpdate(array('client_nickname'=>$queryname));
  }
  catch(Exception $e)
  {
    try
    {
      $tsAdmin->selfUpdate(array('client_nickname'=>$queryname2));
    }
    catch(Exception $e)
    {
      echo'<span class="red"><b> Error: ' .$e->getCode().':</b> '.$e->getMessage().'</span><br>';
    }
  }
}

function getClientIp() {
      if (!empty($_SERVER['HTTP_CLIENT_IP']))
          return $_SERVER['HTTP_CLIENT_IP'];
      else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
          return $_SERVER['HTTP_X_FORWARDED_FOR'];
      else if(!empty($_SERVER['HTTP_X_FORWARDED']))
          return $_SERVER['HTTP_X_FORWARDED'];
      else if(!empty($_SERVER['HTTP_FORWARDED_FOR']))
          return $_SERVER['HTTP_FORWARDED_FOR'];
      else if(!empty($_SERVER['HTTP_FORWARDED']))
          return $_SERVER['HTTP_FORWARDED'];
      else if(!empty($_SERVER['REMOTE_ADDR']))
          return $_SERVER['REMOTE_ADDR'];
      else
          return false;
  }

  function getUidByIp($tsAdmin) {
    $FLAG = false;
    foreach ($tsAdmin->clientList(array('client_type' => '0', 'connection_client_ip' => getClientIp())) as $client) {
        $clientuid = $client->client_unique_identifier;
        $FLAG = true;
        break;
    }
    if (!$FLAG){
        echo "<p><b>'You are not present on the server please sign in.'.</b></p><br/>";
		header("refresh: 10; url = ./");
		die;
    }
    return $clientuid;
  }
