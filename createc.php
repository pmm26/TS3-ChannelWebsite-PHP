<?php 
    //Check if the user is logged in
    session_start();
    if(!isset($_SESSION['loggedin'])) {
        header('Location: loginform.html');
    }

require_once __DIR__ . '/include/dbconnect.php';
require_once __DIR__ . '/include/tsutils.php';

//Start TeamSpeak Querry Connection
$tsAdmin = getTeamspeakConnection();

//Get a list of all members in the server
$userList = $tsAdmin->clientList();

//Connect to the Database
$dbhandle = mysqlConnection();

//Query the database Reagarding the Game Areas available
$sql = "SELECT id, game FROM game_area";
$gameAreas = dbquery( $dbhandle, $sql, []);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>DeepGaming Create Channel</title>
</head>
<body>
<div id="website">
    <h1>Create Channel!</h1>
    <form action="create_script.php" method="POST">
        <input type="hidden" name="email" value="muskito@gay.com">



      <div class="form formlabel">
      <label for="cname">Channel Name:</label>
      </div>
      <div class="form">
      <input type="text" name="cname">
      </div>
      <br />

        <div class="form formlabel">
        <label for="cpass">Channel Password:</label>
        </div>
        <div class="form">
        <input type="text" name="cpass">
        </div>
        <br />



        <div class="form formlabel">
        <label for="uuid">Cliet uuid</label>
        </div>
        <div class="form">
        <select name="uuid">

        <?php		
            foreach ($userList as $user) {
                echo "<option value=" . $user->client_unique_identifier . ">" . $user . "</option>";
            }   
        ?>
        </select>
        </div>
         <br />


        <div class="form formlabel">
        <label for="uuid">Game Area:</label>
        </div>
        <div class="form">
        <select name="garea">

        <?php		
            foreach ($gameAreas as $area) {
                echo "<option value=" . $area['id'] . ">" . $area['game'] . "</option>";
            }   
        ?>		
      </select> 
        </div>
      <br />
      <br />
      <input type="submit" value="Submit">
      <br />
      <br />
      <a href="index.php">Back menu!</a>
    </form>

</div>
</body>
</html>