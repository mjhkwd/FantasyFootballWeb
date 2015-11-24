<?php
	session_start();
	if($_SERVER['HTTPS']!="on") //is this on https or http?
	{
		$address = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //redirect if not on https
		header("location: {$address}");
	}
	
	if($_SESSION['loggedin']) //if already logged in
	{
		header("location: https://babbage.cs.missouri.edu/~cs3380f14grp7/cs3380group7/fantasy/index.php");
		exit;
	}
	else
	{
		$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")
		or die("Could not connect: " . pg_last_error());
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fantasy Football - Home</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/business-casual.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="brand">Fantasy Football</div>

    <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
                <a class="navbar-brand" href="index.php">Fantasy Football</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="draft.php">Draft</a>
                    </li>
                    <li>
                        <a href="standings.php">League Standings</a>
                    </li>
                    <li>
                        <a href="transactions.php">Transactions</a>
                    </li>
                    <li>
                        <a href="scoring.php">Scoring</a>
                    </li>
					<li>
						<?php
							if(!$_SESSION["loggedin"])
								echo"<a href='login.php'>Login</a>";
							else
								echo"<a href='logout.php'>Logout</a>";
						?>
					</li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <div class="container">

		<div class="row">
            <div class="box">
                <div class="col-lg-12">
					Please login
					<br>
					<form method='post' action='login.php'>
						<label for 'username'>Username:</label>
						<input id='username' type='text' name='username'></input>
						
						<label for 'password'>Password:</label>
						<input id='password' type='password' name='password'></input>
						
						<br>
						
						<input type='submit' value='submit' name='submit'></input>
					</form>
					<a href='registration.php'>Register Here</a>
					<?php
						if($_POST['submit'])
						{
							$username = htmlspecialchars($_POST['username']);
							$password = $_POST['password'];
							
							$result = pg_prepare($dbconn, "checkcreds", 'SELECT salt, password_hash FROM user1.authentication WHERE username = $1'); //grab password credentials
							$result = pg_execute($dbconn, "checkcreds", array($username));
							
							if(pg_num_rows($result)==0)
							{
								echo "<br>An incorrect username was provided. Try again.<br>"; //no password stored for that username
							}
							else
							{
								$row = pg_fetch_assoc($result);
								$hashvalidate = htmlspecialchars(sha1($password . $row['salt'])); //encrypt password from POST data
								
								if($hashvalidate == $row['password_hash']) //does ^ match what is stored in db?
								{
									$_SESSION['loggedin'] = 'true'; //set SESSION variables for easier checking later
									$_SESSION['name'] = $username;
									
									$date = date("Y-m-d G:i:s"); //current dateTime
									$ipaddress = $_SERVER['REMOTE_ADDR'];
								
									$result = pg_prepare($dbconn, "logUpdate", 'INSERT INTO user1.log (username, ip_address, log_date, action) VALUES($1, $2, $3, $4)');
									$result = pg_execute($dbconn, "logUpdate", array($username, $ipaddress, $date, 'login'));
									
									$result = pg_query($dbconn, "SELECT * FROM user1.draft");
									$row = pg_fetch_assoc($result);
									
									if($row[draft_true] == true)
									{
										$_SESSION["draft"] = 'true';
									}
									
									$_SESSION[$username] = 'online';
									$result = pg_query($dbconn, "UPDATE user1.user_info SET description = 'online' WHERE username = '{$username}'");
									
									$users = array();

									$result = pg_query($dbconn, "SELECT username FROM user1.user_info WHERE username <> '{$_SESSION['name']}' AND description = 'online'");
									while($line = pg_fetch_array($result))
									{
										$users[] = $line[username];
									}
									
									$len = count($users);
									
									print_r($len);
									
									for ($x = 0; $x < $len; $x++)
									{
										$_SESSION[$users[$x]] = 'online';
									} 
									
									header('location: index.php');
								}
								else
								{
									echo "<br>Incorrect Password!";
								}
							}
						}
					?>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Copyright &copy; CS3380 Fall 2014 Group 7</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
