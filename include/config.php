<?php
/*
    Author: Wruczek
    Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9PL5J7ULZQYJQ

    I am happy to take any programming-related requests, add additional features or modify the code to suit your needs for a small donation :)
    I am experienced at Java, PHP, HTML, CSS, Javascript, SQL, server configurations ect.
    For business enquiries only: wruczekk at gmail.com, for anything else please join Telegram chat.


    Need help? Join our telegram group for news, announcements, help and general chat about ts-website: https://telegram.me/tswebsite
*/



/************* General configuration *************/

$config["general"]["title"]         = "ZoneGaming.pt";                                 // Website title - displayed in the menu
$config["general"]["icon"]          = "icon-64.png";                       // Website icon

$config["general"]["subtitle"]      = " - Comunidade Portuguesa!";                  // Website subtitle
$config["general"]["desc"]          = "Since 2012";     // Website description - displayed in Google search engine
$config["general"]["newsDir"]       = "config/news";                                // News folder (relative to project folder)
$config["general"]["timezone"]      = "Europe/London";                              // Your timezone - http://php.net/manual/en/timezones.php
$config["general"]["christmasmode"] = false;                                         // Set to false to permanently disable christmas mode activated in December

$config["general"]["enablehta"]     = false; // Enable / Disable additional website features (recommended, but
                                             // you need to have up-to-date version of Apache and install mod_rewrite)
                                             // After setting to true, go into .htaccess file and uncomment 19 line


/********* TeamSpeak configuration *********/

$config['teamspeak']['host']          = '37.59.63.90';          // TeamSpeak host address
$config['teamspeak']['login']         = 'serveradmin';        // Login
$config['teamspeak']['password']      = '+M2DBgV2';           // Password
$config['teamspeak']['server_port']   = 9987;                 // TeamSpeak server port
$config['teamspeak']['query_port']    = 10011;                // Query port
$config['teamspeak']['displayip']     = '37.59.63.90';       // IP shown to users and used for connections
$config['teamspeak']['queryname']     = 'ZoneGaming - Panel';
$config['teamspeak']['queryname2']    = 'ZoneGaming - CPanel1';


/********* Database configuration *********/
$config['database']['host']       = "148.251.236.165";
$config['database']['username']   = "deepg_channelpanel";
$config['database']['password']   = "4PIaWr4n0czmw1OD";
$config['database']['database']   = "deepg_channelpanel";


/************* Additional navigation links - you can link to your stuff *************/

// TEMPLATE: (ICON is an icon name from: http://fontawesome.io/icons/)
// $config["navlinks"][] = ["icon", "displayed text", "link"];

$config["navlinks"][] = ["fa-facebook-official", "Facebook", "https://facebook.com/ComunidadeZoneGaming/"];
//$config["navlinks"][] = ["fa-twitter-square", "Twitter", "https://twitter.com/Twitter"];
//$config["navlinks"][] = ["fa-comments", "Forum", "forum"];
$config["navlinks"][] = ["fa-comments", "RankSystem", "https://rank.zonegaming.pt/"];



/************* Adminlist configuration *************/

// ID of servergroups displayed as admins in Adminlist. Put it in the same way you want it to be displayed.
//$config["adminlist"] = [27, 28 ,29];
$config["adminlist"] = [6];



/************* Contact panel configuration *************/

$config['contact']['title'] = 'Contactar a Staff';

/*
TIP: You can remove all items below to hide contact panel

CONTACT PANEL SYNTAX:
$config['contact']['items'][] = ["name", "link description", "link"];

FOR EXAMPLE:
$config['contact']['items'][] = ["Telegram", "@Wruczek", "https://telegram.me/Wruczek"];
*/

//$config['contact']['items'][] = ["TeamSpeak", "Canal de Ajuda", "ts3server://ts.zonegaming.pt?cid=30"];

$config['contact']['items'][] = ["TeamSpeak", "Canal de Ajuda", "ts3server://ts.zonegaming.pt?cid=30"];
$config['contact']['items'][] = ["Email", "geral@zonegaming.pt", "mailto:geral@zonegaming.pt"];
$config['contact']['items'][] = ["Facebook", "Pagina do Facebook", "https://facebook.com/ComunidadeZoneGaming/"];
//$config['contact']['items'][] = ["Twitter", "@Twitter", "https://twitter.com/Twitter"];



$configChannel['channel']['freeSpacerName']   = "♦ Vaga ♦";
$configChannel['channel']['subChannelName']   = "● Sala de Convivio";
$configChannel['channel']['awayName']   = "● AFK/Away";

$configChannel['channel']['defaultPassword']   = "26av84sYQ2V";
$configChannel['channel']['freeChannelDescription']   = "Place Holder";
$configChannel['channel']['freeChannelTopic']   = "Place Holder";
$configChannel['channel']['daysToMove']   = 2;
$configChannel['channel']['adminChannelGroup']   = 5;
$configChannel['channel']['coAdminChannelGroup']   = 6;
$configChannel['channel']['memberChannelGroup']   = 7;
$configChannel['channel']['guestChannelGroup']   = 8;
$configChannel['channel']['groupTemplate']   = 9;


$channelparent = '2'; //Channel ID PARENT
$channelgroup = '5'; //Admin channel ID ( NO SORT ID )
$guestchannelgroup = '8'; //guest channel ID ( NO SORT ID )



//Channel Names
$FreeChannelName = "♦ Free Channel / Vaga ♦";
$defSubChannelName = "● Sala de Convivio";
$SubChannelName1 = "● Sala de Convivio 1";
$SubChannelName2 = "● Sala de Convivio 2";
$SubChannelName3 = "● Sala de Convivio 3";
$SubChannelName4 = "● AFK/Away";

$defaultPassoword = "26av84sYQ2V";

//Template group id

$groupTemplateId = "9";

//time required to move channel again.
$DaysToMove=2;

//Discription and Topic for the channel
$channelDescription = "";
$channelTopic = "";
$freeChannelDescription = "";
$freeChannelTopic = "";
/**
 * Database and TeamSpeak Connect
 */


// Game Areas
$configChannel['gameareas']['vip'] = "VIP";
$configChannel['gameareas']['csgo'] = "CSGO";
$configChannel['gameareas']['lol'] = "LOL";
$configChannel['gameareas']['other'] = "OTHER";
