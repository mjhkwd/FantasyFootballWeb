<?php

	session_start();
	if(!$_SESSION['loggedin'])
	{
		header('location: index.php'); //only view if logged in
	}
		
	$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")									//connect to dbms
			or die("Could not connect: " . pg_last_error());
	

	require 'functions/getLoggedIn.php';
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
                        <?php
                        if (!$_SESSION["draft"])
                        {
								echo "<a href='draft.php'>Draft</a>";
						}
                        ?>
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
						Check your team<strong> scores.</strong>
                    </h2>
                    <hr>
					<?php
							if(!$_SESSION["loggedin"])
								Header("Location: login.php");
							else
							{
					?> 
					<!--Start code here-->
					 <h1 class="text-center">
							<strong> Week 4</strong>
					</h1>
						<div class="col-lg-6">
							<h2 class = "text-center">
								<u>Sneaky Kickers</u>
							</h2>
							<table border="1" class="table">
								<?php
									$query = "SELECT player_id, gsis_name, team, position FROM player WHERE fantasy_team = 'Sneaky Kickers' AND position != 'K' ORDER BY position";
									$result = pg_query($query) or die('Query failed: '.pg_last_error());
									$player_id = array();
									$i = 0;
									$player_row;
									$team_points = 0;
									while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
										echo "\t<tr>\n";
										$player_id[i] = $line['player_id'];
										$i++;
										$player_row = "<td align = 'right'> (".$line['team']." -".$line['position'].") <b>".$line['gsis_name']."</b> <br>";
										
										$query2 = "SELECT SUM(play_player. kicking_fgm) as fgmade, SUM(play_player.kicking_fgm_yds) as fgyds, SUM(play_player.kicking_fgmissed) as fgmissed, SUM(play_player.passing_int) as ints, SUM(play_player.passing_tds) as passtds, SUM(play_player.passing_twoptm) as passtwopts, SUM(play_player.passing_yds) as passyds, SUM(play_player.receiving_tds) as rectds, SUM(play_player.receiving_twoptm) rectwopts, SUM(play_player.receiving_yds) as recyds, SUM(play_player.rushing_tds) as rushtds, SUM(play_player.rushing_twoptm) as rushtwopt, SUM(play_player.rushing_yds) rushyds, SUM(play_player.kickret_tds) as kickrettds, SUM(play_player.puntret_tds) as puntretyds, SUM(play_player.fumbles_rec_tds) as fumbrectds, SUM(play_player.fumbles_lost ) AS fumblost FROM play_player LEFT JOIN player ON player.player_id = play_player.player_id LEFT JOIN game ON game.gsis_id = play_player.gsis_id WHERE game.season_year = 2013 AND game.season_type = 'Regular' AND game.week = 4 AND player.player_id = '".$line['player_id']."' AND fantasy_team = 'Sneaky Kickers' AND position != 'K' group by position ORDER BY position";
										$result2 = pg_query($query2) or die('Query failed: '.pg_last_error());
										
										$flag = 0;
										while($line2 = pg_fetch_array($result2,null,PGSQL_ASSOC)){
												$flag = 1;
												$player_points = calc_pts($line2['fgmade'], $line2['fgyds'], $line2['fgmissed'], $line2['ints'], $line2['passtds'], $line2['passtwopts'], $line2['passyds'], $line2['rectds'], $line2['rectwopts'], $line2['recyds'], $line2['rushtds'], $line2['rushtwopt'], $line2['rushyds'], $line2['kickrettds'], $line2['puntretyds'], $line2['fumbrectds'], $line2['fumblost']);
												
												$player_row = $player_row. " Points: <b>".$player_points ."</td></b>";
												
												$team_points = $team_points + $player_points;
										}
										if($flag == 0){
											$player_row = $player_row."Points: <b>N/A</b></td>";
										}
										
										echo $player_row = $player_row. "</td>";
										
										echo "\t</tr>\n";
									}
									
									$query = "SELECT gsis_name, team, position FROM player WHERE fantasy_team = 'Sneaky Kickers' AND position = 'K'";
									$result = pg_query($query) or die('Query failed: '.pg_last_error());
									$player_id = array();
									$i = 0;
									$player_row2;
									while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
										echo "\t<tr>\n";
										$player_id[i] = $line['player_id'];
										$i++;
										$player_row2 = "<td align = 'right'> (".$line['team']." -".$line['position'].") <b>".$line['gsis_name']."</b><br>";
										
										$query2 = "SELECT SUM(play_player. kicking_fgm) as fgmade, SUM(play_player.kicking_fgm_yds) as fgyds, SUM(play_player.kicking_fgmissed) as fgmissed, SUM(play_player.passing_int) as ints, SUM(play_player.passing_tds) as passtds, SUM(play_player.passing_twoptm) as passtwopts, SUM(play_player.passing_yds) as passyds, SUM(play_player.receiving_tds) as rectds, SUM(play_player.receiving_twoptm) rectwopts, SUM(play_player.receiving_yds) as recyds, SUM(play_player.rushing_tds) as rushtds, SUM(play_player.rushing_twoptm) as rushtwopt, SUM(play_player.rushing_yds) rushyds, SUM(play_player.kickret_tds) as kickrettds, SUM(play_player.puntret_tds) as puntretyds, SUM(play_player.fumbles_rec_tds) as fumbrectds, SUM(play_player.fumbles_lost ) AS fumblost FROM play_player LEFT JOIN player ON player.player_id = play_player.player_id LEFT JOIN game ON game.gsis_id = play_player.gsis_id WHERE game.season_year = 2013 AND game.season_type = 'Regular' AND game.week = 9 AND player.player_id = '".$line['player_id']."' AND fantasy_team = 'Sneaky Kickers' AND position = 'K'";
										$result2 = pg_query($query2) or die('Query failed: '.pg_last_error());
										
										$flag = 0;
										while($line2 = pg_fetch_array($result2,null,PGSQL_ASSOC)){
												$flag = 1;
												$player_points = calc_pts($line2['fgmade'], $line2['fgyds'], $line2['fgmissed'], $line2['ints'], $line2['passtds'], $line2['passtwopts'], $line2['passyds'], $line2['rectds'], $line2['rectwopts'], $line2['recyds'], $line2['rushtds'], $line2['rushtwopt'], $line2['rushyds'], $line2['kickrettds'], $line2['puntretyds'], $line2['fumbrectds'], $line2['fumblost']);
												
												$player_row2 = $player_row2. " Points: <b>".$player_points ."</td></b>";
												$team_points = $team_points + $player_points;
										}
										if($flag == 0){
											$player_row = $player_row."Points: <b>N/A</b></td>";
										}
										echo $player_row2 = $player_row2. "</td>";
										echo "\t</tr>\n";
									}
									echo "<h4 class = 'text-center'> Total Points this Week: <h2 class = 'text-center'>".$team_points."</h2></h4>";
									
								?>
							</table>
							
						</div>
						
						<div class="col-lg-6">
							<h2 class = "text-center">
								<u>Tester</u>
							</h2>
							<table border="1" class="table">
								<?php
									$query = "SELECT player_id, gsis_name, team, position FROM player WHERE fantasy_team = '".$_SESSION['name']."' AND position != 'K' ORDER BY position";
									$result = pg_query($query) or die('Query failed: '.pg_last_error());
									$player_id = array();
									$i = 0;
									$player_row3;
									$team_points = 0;
									while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
										echo "\t<tr>\n";
										$player_id[i] = $line['player_id'];
										$i++;
										$player_row3 = "<td> (".$line['team']." -".$line['position'].") <b>".$line['gsis_name']."</b> <br>";
										
										$query2 = "SELECT SUM(play_player. kicking_fgm) as fgmade, SUM(play_player.kicking_fgm_yds) as fgyds, SUM(play_player.kicking_fgmissed) as fgmissed, SUM(play_player.passing_int) as ints, SUM(play_player.passing_tds) as passtds, SUM(play_player.passing_twoptm) as passtwopts, SUM(play_player.passing_yds) as passyds, SUM(play_player.receiving_tds) as rectds, SUM(play_player.receiving_twoptm) rectwopts, SUM(play_player.receiving_yds) as recyds, SUM(play_player.rushing_tds) as rushtds, SUM(play_player.rushing_twoptm) as rushtwopt, SUM(play_player.rushing_yds) rushyds, SUM(play_player.kickret_tds) as kickrettds, SUM(play_player.puntret_tds) as puntretyds, SUM(play_player.fumbles_rec_tds) as fumbrectds, SUM(play_player.fumbles_lost ) AS fumblost FROM play_player LEFT JOIN player ON player.player_id = play_player.player_id LEFT JOIN game ON game.gsis_id = play_player.gsis_id WHERE game.season_year = 2013 AND game.season_type = 'Regular' AND game.week = 1 AND player.player_id = '".$line['player_id']."' AND fantasy_team = '".$_SESSION['name']."' AND position != 'K' group by position ORDER BY position";
										
										$result2 = pg_query($query2) or die('Query failed: '.pg_last_error());+
										$flag = 0;
										while($line2 = pg_fetch_array($result2,null,PGSQL_ASSOC)){
											$flag = 1;
											$player_points = calc_pts($line2['fgmade'], $line2['fgyds'], $line2['fgmissed'], $line2['ints'], $line2['passtds'], $line2['passtwopts'], $line2['passyds'], $line2['rectds'], $line2['rectwopts'], $line2['recyds'], $line2['rushtds'], $line2['rushtwopt'], $line2['rushyds'], $line2['kickrettds'], $line2['puntretyds'], $line2['fumbrectds'], $line2['fumblost']);
											$player_row3 = $player_row3. " Points: <b>".$player_points ."</td></b>";
											
											$team_points = $team_points + $player_points;
										}
										if($flag == 0){
											$player_row3 = $player_row3."Points: <b>N/A</b></td>";
										}
										echo $player_row3 = $player_row3. "</td>";
										echo "\t</tr>\n";
									}
									
									$query = "SELECT gsis_name, team, position FROM player WHERE fantasy_team = '".$_SESSION['name']."' AND position = 'K'";
									$result = pg_query($query) or die('Query failed: '.pg_last_error());
									$player_id = array();
									$i = 0;
									$player_row3;
									while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
										echo "\t<tr>\n";
										$player_id[i] = $line['player_id'];
										$i++;
										$player_row3 = "<td> (".$line['team']." -".$line['position'].") <b>".$line['gsis_name']."</b> <br>";
										
										$query2 = "SELECT SUM(play_player. kicking_fgm) as fgmade, SUM(play_player.kicking_fgm_yds) as fgyds, SUM(play_player.kicking_fgmissed) as fgmissed, SUM(play_player.passing_int) as ints, SUM(play_player.passing_tds) as passtds, SUM(play_player.passing_twoptm) as passtwopts, SUM(play_player.passing_yds) as passyds, SUM(play_player.receiving_tds) as rectds, SUM(play_player.receiving_twoptm) rectwopts, SUM(play_player.receiving_yds) as recyds, SUM(play_player.rushing_tds) as rushtds, SUM(play_player.rushing_twoptm) as rushtwopt, SUM(play_player.rushing_yds) rushyds, SUM(play_player.kickret_tds) as kickrettds, SUM(play_player.puntret_tds) as puntretyds, SUM(play_player.fumbles_rec_tds) as fumbrectds, SUM(play_player.fumbles_lost ) AS fumblost FROM play_player LEFT JOIN player ON player.player_id = play_player.player_id LEFT JOIN game ON game.gsis_id = play_player.gsis_id WHERE game.season_year = 2013 AND game.season_type = 'Regular' AND game.week = 1 AND player.player_id = '".$line['player_id']."' AND fantasy_team = '".$_SESSION['name']."' AND position = 'K'";
										
										$result2 = pg_query($query2) or die('Query failed: '.pg_last_error());
										
										$flag = 0;
										while($line2 = pg_fetch_array($result2,null,PGSQL_ASSOC)){
											$flag = 1;
											$player_points = calc_pts($line2['fgmade'], $line2['fgyds'], $line2['fgmissed'], $line2['ints'], $line2['passtds'], $line2['passtwopts'], $line2['passyds'], $line2['rectds'], $line2['rectwopts'], $line2['recyds'], $line2['rushtds'], $line2['rushtwopt'], $line2['rushyds'], $line2['kickrettds'], $line2['puntretyds'], $line2['fumbrectds'], $line2['fumblost']);
											$player_row3 = $player_row3. " Points: <b>".$player_points ."</td></b>";
											
											$team_points = $team_points + $player_points;
										}
										if($flag == 0){
											$player_row3 = $player_row3."Points: <b>N/A</b></td>";
										}
										echo $player_row3 = $player_row3. "</td>";
										echo "\t</tr>\n";
									}
									echo "<h4 class = 'text-center'> Total Points this Week: <h2 class = 'text-center'>".$team_points."</h2></h4>";
								?>
							</table>
							
						</div>
						<div>
							
						</div>
						<!--End code here-->
						
					<?php
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
<?php
	function calc_pts($kfgm, $kfgyds, $kfgmissed, $passints, $passtds, $passtwopts, $passyds, $rectds, $rectwopt, $recyds, $rushtds, $rushtwopt, $rushyds, $ktds, $punttds, $fumrectds, $fumblost){
		$kick_pts = $kfgm + ($kfgyds/50)*5 - $kfgmissed;
		$pass_pts = $passtds*4 + $passtwopts*2 + ($passyds/25)*2 - $passints;
		$rec_pts = $rectds*6 + $rectwopt*2 + ($recyds/10)*2;
		$rush_pts = $rushyds/10 + $rushtwopt*2 + $rushtds*6;
		$misc = $ktds*6 + $punttds*6 + $fumrectds*6 - $fumblost*2;
		
		return $kick_pts + $pass_pts + $rec_pts + $rush_pts + $misc;
	}
?>
</body>

</html>
