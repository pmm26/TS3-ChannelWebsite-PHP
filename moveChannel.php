

<?php 
    //Check if the user is logged in
    session_start();
    if(!isset($_SESSION['loggedin'])) {
        header('Location: loginform.html');
    }

require_once __DIR__ . '/include/dbconnect.php';


//Connect to the Database
$dbhandle = mysqlConnection();

//Query the database Reagarding the Used channels.
$sql = "SELECT `zgcid`, `channel_name`, `channel_order`, `game_area` FROM channels WHERE owner_uuid is not Null;";
$Channels = dbquery( $dbhandle, $sql, []);

//Query the database Reagarding the unused channels.
$sql = "SELECT `zgcid`, `channel_name`, `channel_order`, `game_area` FROM channels WHERE owner_uuid is Null;";
$freeChannels = dbquery( $dbhandle, $sql, []);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>DeepGaming Move Channel</title>
</head>
<body>
<div id="website">
    <h1>Mover Canais!</h1>
    <form action="moveChannelUp_script.php" method="POST">
        

       <div class="form formlabel">
        <label for="zgcid">Mover Canal:</label>
        </div>
        <div class="form">
        <select name="zgcid">

        <?php		
              if (!empty($Channels)) {
                foreach ($Channels as $row) {

                      echo "<option value=" . $row['zgcid'] . ">" . $row['channel_order'] . " - " . $row['channel_name'] . " | Game Area: " . $row['game_area'] . "</option>";
                  }
                } else {
                    echo "<option value=" . NoValue . ">" . "No Channels available" . "</option>";
                }
        ?>		
      </select> 
        </div>
         <br />


        <div class="form formlabel">
        <label for="pickedZgcid">Canais Livres:</label>
        </div>
        <div class="form">
        <select name="pickedZgcid">

        <?php		
              if (!empty($freeChannels)) {
                foreach ($freeChannels as $row) {

                      echo "<option value=" . $row['zgcid'] . ">" . $row['channel_order'] . " - " . $row['channel_name'] . " | Game Area: " . $row['game_area'] . "</option>";
                  }
                } else {
                    echo "<option value=" . NoValue . ">" . "No Free Channels available" . "</option>";
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