<?php
	session_start();
  require_once __DIR__ . '/../include/tsutils.php';
	require_once __DIR__ . "/../config/config.php";
	require_once __DIR__ . "/../lib/ts3phpframework/libraries/TeamSpeak3/TeamSpeak3.php";


	  $ts3 = getTeamspeakConnection();
    $FLAG = false;
    foreach ($ts3->clientList(array('client_type' => '0', 'connection_client_ip' => getClientIp())) as $client) {
        $clientuid = $client->client_unique_identifier;
        $FLAG = true;
        break;
    }
    if (!$FLAG){
        echo "<p><b>'You are not present on the server please sign in.'.</b></p><br/>";
		header("refresh: 10; url = ./");
		die;
    }
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create Channel With MyPlatform</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <body>

		<!-- Top menu -->
		<nav class="navbar navbar-inverse navbar-no-bg" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.html">Create a permanent channel at a minute</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="top-navbar-1">
					<ul class="nav navbar-nav navbar-right">
					</ul>
				</div>
			</div>
		</nav>

        <!-- Top content -->
        <div class="top-content">

            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>Create</strong> a permanent channel at a minute</h1>
                            <div class="description">
                            	<p>
	                            	Our server benefits from the most advanced private channel creation system</br>
									through the browser.
                            	</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">

                        	<form role="form" action="crear.php" method="post" class="registration-form">

                        		<fieldset>
		                        	<div class="form-top">
		                        		<div class="form-top-left">
		                        			<h3>Step 1 / 3</h3>
		                            		<p>Channel name and below your channel:</p>
		                        		</div>
		                        		<div class="form-top-right">
		                        			<i class="fa fa-user"></i>
		                        		</div>
		                            </div>
		                            <div class="form-bottom">

				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-first-name">Team Name</label>
				                        	<input type="text" name="name" placeholder="Name of your team..." class="form-first-name form-control" id="form-first-name">
				                        </div>

                                        <!-- needs to be rewritten -->
                                        <h4>Select the game: </h4>
                                        <div class="form-group">
                                            <label class="sr-only" for="form-last-name">Sub-channel name</label>
                                            <select name="Game">
                                                <option value="1">Counter-Strike</option>
                                                <option value="2">League of Legends</option>
                                                <option value="3">Other...</option>
                                            </select>
                                        </div>
                                        <!-- needs to be rewritten -->

				                        <button type="button" class="btn btn-next">Next</button>
				                    </div>

			                    </fieldset>

			                    <fieldset>
		                        	<div class="form-top">
		                        		<div class="form-top-left">
		                        			<h3>Step 2 / 3</h3>
		                            		<p>Set up your passwords:</p>
		                        		</div>
		                        		<div class="form-top-right">
		                        			<i class="fa fa-key"></i>
		                        		</div>
		                            </div>
		                            <div class="form-bottom">
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-repeat-password">Password Channel</label>
				                        	<input type="password" name="ChannelPass" placeholder="Password Channel..." class="form-email form-control" id="form-email">
				                        </div>
				                        <div class="form-group">
				                    		<label class="sr-only" for="form-password">Password sub-channel</label>
				                        	<input type="password" name="SubChannelPass" placeholder="Password sub-channel..." class="form-password form-control" id="form-password">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-email">Contact</label>
				                        	<input type="text" name="form-email" placeholder="Contact (steam account link)..."
				                        				class="form-repeat-password form-control" id="form-repeat-password">
				                        </div>
				                        <button type="button" class="btn btn-previous">Previous</button>
				                        <button type="button" class="btn btn-next">Next</button>
				                    </div>
			                    </fieldset>

			                    <fieldset>
		                        	<div class="form-top">
		                        		<div class="form-top-left">
		                        			<h3>Step 3 / 3</h3>
		                            		<p>ADMIN UNIQ-ID</p>
		                        		</div>
		                        		<div class="form-top-right">
		                        			<i class="fa fa-twitter"></i>
		                        		</div>
		                            </div>
		                            <div class="form-bottom">
				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-facebook">Admin UNIQ-ID</label>
				                        	<input type="text" name="idts" placeholder="Admin UNIQ-ID" value= "<?php echo $clientuid ?>" class="form-facebook form-control" id="form-facebook" readonly>
				                        </div>
				                        <button type="button" class="btn btn-previous">Previous</button>
				                        <button id="signIn_1" type="submit" class="btn">Create channel!</button>
				                    </div>
			                    </fieldset>

		                    </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- Javascript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/retina-1.1.0.min.js"></script>
        <script src="assets/js/scripts.js"></script>

        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>
