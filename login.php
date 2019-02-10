<?php
$user = $_POST['username'];
$pass = $_POST['password'];

if ($user === "deepg" & $pass === "create") {
    session_start();
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user;

    header('Location: index.php');
} else {
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
        <h1>Invalid ID or PASSWORD!</h1>
        <br />
         <a href="index.php">Back menu!</a>
    </div>
</body>
</html>

<?php
}
?>

