<?php
    //Check if the user is logged in
    session_start();
    if(isset($_SESSION['loggedin'])) {
        echo "Good Bye!" . $_SESSION['username'];
    } 
    //destroys the new session that was created to check if the user was logged in and the already existing one.
    session_destroy();

    header('Location: loginform.html');

?>