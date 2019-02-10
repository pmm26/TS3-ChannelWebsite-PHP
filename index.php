<?php


    //Check if the user is logged in
    session_start();
    if(!isset($_SESSION['loggedin'])) {
        header('Location: loginform.html');
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Document</title>
</head>
<body>
    <div id="website">
    <h1><?php echo "Welcome back: " . $_SESSION['username'] ?></h1>
    <ul>
        <li><a href="createc.php">Create Channel</a></li>
        <br />
        <li><a href="moveChannel.php">Move Channel</a></li>
        <br />
        <br />
        <a href="logout.php">Logout</a>
    </ul>
        
    </div>
</body>
</html>

