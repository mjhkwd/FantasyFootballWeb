<?php
	// Create database connection
	$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")								
		or die("Could not connect: " . pg_last_error());
	session_start();
	if(!$_SESSION['loggedin'])
	{
		// Can only view if logged in
		header('location: index.php'); 
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
                    <hr>
                    <h2 class="intro-text text-center">
						<strong>Adjust</strong> your team.
                    </h2>
                    </hr>
					<?php
							if(!$_SESSION["loggedin"])
								Header("Location: login.php");
							else
							{
							}
					?>
					<!-- Drop Players Section -->
					<h4 class="intro-text text-left"><strong>Drop</strong> players on your team.</h4>
					<p>
						<table border="1" class="table">
								<tr>
									<th>Full Name</th>
									<th>Position</th>
									<th>College</th>
									<th>Height</th>
									<th>Weight</th>
									<th>Years Pro</th>
									<th>Action</th>
								</tr>
								<?php
									$query = "SELECT player_id, full_name, position, college, height, weight, years_pro FROM player WHERE fantasy_team = '".$_SESSION['name']."';";
									$result = pg_query($query) or die('Query failed: '.pg_last_error());
									// Print all players on currently logged in team
									while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
										echo "\n<tr>\n\t";
										echo '<form method="GET" action="/~cs3380f14grp7/cs3380group7/fantasy/transactions.php">';
										echo "\n\t\t";
										echo '<td>'.$line['full_name'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['position'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['college'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['height'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['weight'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['years_pro'].'</td>';
										echo "\n\t\t<input type = \"hidden\" name = 'player_id' value = '".$line['player_id']."'/>";
										echo "\n\t\t";
										echo '<td><input type="submit" name="remove" value="Remove"/></td>';
										echo "\n\t</form>\n</tr>\n";
									}
									// If remove button is pressed, player is deleted from the team
									if (isset($_GET['remove'])) {
										$query = "UPDATE player SET fantasy_team = null WHERE player_id = $1";
										$result1 = pg_prepare($dbconn, "remove", $query);
										$result1 = pg_execute($dbconn, "remove", array($_GET['player_id']));
									}
									// If add button is pressed, player will be added to your team
									if (isset($_GET['Add'])) {
										$countQuery = "SELECT count(*) from player WHERE fantasy_team = $1 GROUP BY full_name;";
										$countResult = pg_prepare($dbconn, "count", $countQuery);
										$countResult = pg_execute($dbconn, "count", array($_SESSION['name']));
										$numPlayers = pg_num_rows($countResult);
										// Teams can't have more than 15 players
										if ($numPlayers == 15) {
											echo "<h5>Roster is already filled, you must remove a player before adding another one.</h5>";
										}
										else {
											$query = "UPDATE player SET fantasy_team = $1 WHERE player_id = $2";
											$result = pg_prepare($dbconn, "add", $query);
											$result = pg_execute($dbconn, "add", array($_SESSION['name'], $_GET['player_id']));
										}
										header('Location: transactions.php');
									}
								?>
						</table>
						<?php
							//$count = pg_num_rows($result1);
							//echo "<h5>Total Number of Players: ".$count."<h5>";
						?>  
					</p>
					<br />
					<!-- Add Players Section -->
					<h4 class="intro-text text-left">
						<strong>Add</strong> players to your team.
                    </h4>
					<p> 
						<form class="form-horizontal" role="form" action="/~cs3380f14grp7/cs3380group7/fantasy/transactions.php">
							<div class="form-group">
								<label class="col-sm-2 control-label">Search for a player by name: </label>
								<div class="col-sm-10">
									<input class="form-control" name="search">
									<br />
									<input type="submit" name="submit2" value="Execute" class="btn btn-default" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Search for a player by position: </label>
								<form method="POST" action="/~cs3380f14grp7/cs3380group7/fantasy/transactions.php">
									<div class="col-sm-10">
										<select class="form-control" name="positions" id="positions">
											<!-- Sort by position: -->
											<option value="1" <?php if ($_POST['positions'] == '1') echo 'selected = "selected"'; ?>>QB</option>
											<option value="2" <?php if ($_POST['positions'] == '2') echo 'selected = "selected"'; ?>>RB</option>
											<option value="3" <?php if ($_POST['positions'] == '3') echo 'selected = "selected"'; ?>>WR</option>
											<option value="4" <?php if ($_POST['positions'] == '4') echo 'selected = "selected"'; ?>>TE</option>
											<option value="5" <?php if ($_POST['positions'] == '5') echo 'selected = "selected"'; ?>>K</option>
										</select>
									</div>
									<div class="col-sm-10">
										<br />
										<input type="submit" name="submit" value="Execute" class="btn btn-default" />
									</div>
								</form>
							</div>
						</form>
						<div class="Tables"></div> 
						<div>
							<?php
								if(isset($_GET['submit'])){
									$query;
									$result;
									// Search for a player by position
									switch ($_GET['positions']) {
										case "1":
											$query = "SELECT player_id, full_name, position, college, height, weight, years_pro FROM player WHERE position = 'QB' AND years_pro IS NOT NULL AND fantasy_team IS NULL ORDER BY years_pro DESC LIMIT 20;";
											break;
										case "2":
											$query = "SELECT player_id, full_name, position, college, height, weight, years_pro FROM player WHERE position = 'RB' AND years_pro IS NOT NULL AND fantasy_team IS NULL ORDER BY years_pro DESC LIMIT 20;";
											break;
										case "3":
											$query = "SELECT player_id, full_name, position, college, height, weight, years_pro FROM player WHERE position = 'WR' AND years_pro IS NOT NULL AND fantasy_team IS NULL ORDER BY years_pro DESC LIMIT 20;";
											break;
										case "4":
											$query = "SELECT player_id, full_name, position, college, height, weight, years_pro FROM player WHERE position = 'TE' AND years_pro IS NOT NULL AND fantasy_team IS NULL ORDER BY years_pro DESC LIMIT 20;";
											break;
										case "5":
											$query = "SELECT player_id, full_name, position, college, height, weight, years_pro FROM player WHERE position = 'K' AND years_pro IS NOT NULL AND fantasy_team IS NULL ORDER BY years_pro DESC LIMIT 20;";
											break;
									}
									$result = pg_query($query) or die('Query failed: '.pg_last_error());
									printTable($result);
								}
								// Search for a player by name
								if(isset($_GET['submit2'])){
									$query;
									$result;
									$string = $_GET['search'] . "%";
									$query = "SELECT player_id, full_name, position, college, height, weight, years_pro FROM player WHERE full_name ILIKE $1 AND fantasy_team IS NULL;";
									$result = pg_prepare($dbconn, "Search", $query);
									$result = pg_execute($dbconn, "Search", array($string));
									printTable($result);
								}
								// Function to print table for players to be added
								function printTable($result){
									echo "\n<table border=1 class='table'>\n 
											<tr> 
												<th>Full Name</th> 
												<th>Position</th> 
												<th>College</th> 
												<th>Height</th> 
												<th>Weight</th> 
												<th>Years Pro</th> 
												<th>Action</th> 
											</tr>";
									while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
										echo "\n<tr>\n\t";
										echo '<form method="GET" action="/~cs3380f14grp7/cs3380group7/fantasy/transactions.php">';
										echo "\n\t\t";
										echo '<td>'.$line['full_name'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['position'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['college'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['height'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['weight'].'</td>';
										echo "\n\t\t";
										echo '<td>'.$line['years_pro'].'</td>';
										echo "\n\t\t<input type = 'hidden' name = 'player_id' value = '{$line['player_id']}'/>";
										echo "\n\t\t";
										echo '<td><input type="submit" name="Add" value="Add" /></td>';
										echo "\n\t</form>\n</tr>\n";
									}
								}
							?>
							</table>
						</div>
					</p>
					<p>
					<br />
						<!-- Trade Players Section -->
						<h4 class="intro-text text-left">
							<?php echo "<strong>Trade</strong> players with another team." ?>
						</h4>
						<div class="form-group">
							<label class="col-sm-2 control-label">Trade players with another team: </label>
							<form method="GET" action="/~cs3380f14grp7/cs3380group7/fantasy/transactions.php">
								<div class="col-sm-10">
									<select class="form-control" name="team" id="team">
										<!-- Sort by team: -->
										<option value="2" <?php if ($_POST['team'] == '2') echo 'selected = "selected"'; ?>>Midnight Heroes</option>
										<option value="3" <?php if ($_POST['team'] == '3') echo 'selected = "selected"'; ?>>Silver Hurricanes</option>
										<option value="4" <?php if ($_POST['team'] == '4') echo 'selected = "selected"'; ?>>Ghost Dragons</option>
										<option value="5" <?php if ($_POST['team'] == '5') echo 'selected = "selected"'; ?>>Kamikaze Ducks</option>
										<option value="6" <?php if ($_POST['team'] == '6') echo 'selected = "selected"'; ?>>Moose Racers</option>
										<option value="7" <?php if ($_POST['team'] == '7') echo 'selected = "selected"'; ?>>Sneaky Kickers</option>
										<option value="8" <?php if ($_POST['team'] == '8') echo 'selected = "selected"'; ?>>Sharpshooters</option>
									</select>
								</div>
								<div class="col-sm-10">
									<br />
									<input type="submit" name="submit3" value="Execute" class="btn btn-default" />
								</div>
							</form>
						</div>
						<?php 
							$team;
							// If trade button is pressed
							if(isset($_GET['submit3'])){
								$query;
								$result;
								// Create query to view another team depending on which team is selected
								switch ($_GET['team']) {
									case "2":
										$query = "SELECT full_name, position, player_id FROM player WHERE fantasy_team = 'Midnight Heroes';";
										global $team;
										$team = 'Midnight Heroes';
										break;
									case "3":
										$query = "SELECT full_name, position, player_id FROM player WHERE fantasy_team = 'Silver Hurricanes';";
										$team = 'Silver Hurricanes';
										break;
									case "4":
										$query = "SELECT full_name, position, player_id FROM player WHERE fantasy_team = 'Ghost Dragons';";
										$team = 'Ghost Dragons';
										break;
									case "5":
										$query = "SELECT full_name, position, player_id FROM player WHERE fantasy_team = 'Kamikaze Ducks';";
										$team = 'Kamikaze Ducks';
										break;
									case "6":
										$query = "SELECT full_name, position, player_id FROM player WHERE fantasy_team = 'Moose Racers';";
										$team = 'Moose Racers';
										break;
									case "7":
										$query = "SELECT full_name, position, player_id FROM player WHERE fantasy_team = 'Sneaky Kickers';";
										$team = 'Sneaky Kickers';
										break;
									case "8":
										$query = "SELECT full_name, position, player_id FROM player WHERE fantasy_team = 'Sharpshooters';";
										$team = 'Sharpshooters';
										break;
								}
								$result = pg_query($query) or die('Query failed: '.pg_last_error());	
								echo '<div class="col-lg-12">';
								echo '<form method="GET" action="/~cs3380f14grp7/cs3380group7/fantasy/transactions.php" class="myForms">';
								echo '<div class="col-lg-6">';
								echo '<table border="1" class="table"><div class="input-group">';
								echo '<tr><th>Full Name</th><th>Position</th><th>Trade</th></tr>';			
								echo '<h5>Team: '.$_SESSION['name'].'</h5>';
								$queryHome = "SELECT player_id, full_name, position FROM player WHERE fantasy_team = '".$_SESSION['name']."';";
								$resultHome = pg_query($queryHome) or die('Query failed: '.pg_last_error());
								// Print your team
								while($line = pg_fetch_array($resultHome,null,PGSQL_ASSOC)){
									echo "\n";
									echo "\t<tr>\n\t\t";
									echo '<td>'.$line['full_name'].'</td>';
									echo "\n\t\t";
									echo '<td>'.$line['position'].'</td>';
									echo "\n\t\t";
									echo '<td><input type="radio" name="team1" value = '.$line['player_id'].'></td>';
									echo "\n\t</tr>";
								}
								echo '</table></div>';
								echo "\n";
								echo '<div class="col-lg-6">';
								echo "\n";
								echo '<h5>Team: '.$team.'</h5>';
								echo "\n";
								echo '<table border="1" class="table" style="float: left">';
								echo "\n";
								echo '<div class="input-group">';
								echo "\n";
								echo "\t<tr>\n\t\t<th>Full Name</th>\n\t\t<th>Position</th>\n\t\t<th>Trade</th>\n\t</tr>\n";
								//Print opposing team
								while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
									echo "<tr>\n\t\t";
									echo '<td>'.$line['full_name'].'</td>';
									echo "\n\t\t";
									echo '<td>'.$line['position'].'</td>';
									echo "\n\t\t";
									echo '<td><input type="radio" name="team2" value = '.$line['player_id'].'></td>';
									echo "\n\t</tr>";
								}	
								echo "\n\t\t<input type = \"hidden\" name = 'teamName' value = '".$team."'/>";
								echo '</table></div>';
								echo '<div class="col-sm-10">';
								echo '<br />';
								echo '<input type="submit" name="trade" value="Execute" class="btn btn-default" />';
								echo '</div>';
								echo '</form></div>';
							}
							// If trade button is pressed
							if (isset($_GET['trade'])) {
								//Place player 2 on home team
								$query = "UPDATE player SET fantasy_team = $1 WHERE player_id = $2";
								$result = pg_prepare($dbconn, "add", $query);
								$result = pg_execute($dbconn, "add", array($_SESSION['name'], $_GET['team2']));
								
								//Place player 1 on opposing team
								$query = "UPDATE player SET fantasy_team = $1 WHERE player_id = $2";
								$result = pg_prepare($dbconn, "add", $query);
								$result = pg_execute($dbconn, "add", array($_GET['teamName'], $_GET['team1']));
							}
							?>
					</p>		
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
