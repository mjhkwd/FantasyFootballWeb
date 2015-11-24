<?php
session_start();
	$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")									//connect to dbms
	or die("Could not connect: " . pg_last_error());

	require 'functions/getLoggedIn.php';

$query = "UPDATE player SET fantasy_team = null";
$result = pg_query($query);

$result = pg_query($dbconn, "DELETE FROM user1.draft");
//pg_close($dbconn);

	if(!$_SESSION['loggedin'])
	{
		header('location: index.php'); //only view if logged in
	}
	
	require 'functions/getOtherUsers.php';
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
					<hr>
					<h2 class="intro-text text-center">
						<strong>Draft</strong> your team.
					</h2>
					<hr>
					<?php
							if(!$_SESSION["loggedin"])
								Header("Location: login.php");
							else
							{
					?>
						<div id="contain"p>
								<div id="draftinfo" align="center">
							<div id="countdown" class="timer" style="font-size:500%" align="center"></div>
							<input id="start" type="button" value="START DRAFT" onclick="start();" />
							<div align="center">
							<span id="roundcount" style="font-size:200%"></span>
							<span id="pickcount" style="font-size:150%"></span>
							</div>	
							</div>

							<script>
								
								var rounds = 1;
								var picks = 1;
								var maxSeconds = 5;
								var seconds = maxSeconds;
								
								var myArray = ['<?php echo $_SESSION['name']?>','<?php echo $user1?>','<?php echo $user2?>','<?php echo $user3?>','<?php echo $user4?>','<?php echo $user5?>','<?php echo $user6?>','<?php echo $user7?>'];
								var newArray = shuffle(myArray);

    								function draftcycle() {
									var hold = picks;

									document.getElementById("order3").innerHTML = "<b>Picking Now: " + newArray[hold-1];
									if (hold == 8){
										document.getElementById("order2").innerHTML = "Picking Next: " + newArray[0];
									}else{
										document.getElementById("order2").innerHTML = "Picking Next: " + newArray[picks];
									}

    									var minutes = Math.round((seconds - 30)/60);
    									var remainingSeconds = seconds % 60;
   									if (remainingSeconds < 10) {
        									remainingSeconds = "0" + remainingSeconds; 
    									}
    									document.getElementById('countdown').innerHTML = minutes + ":" +    remainingSeconds;
									document.getElementById('roundcount').innerHTML = "Round " + rounds;
									document.getElementById('pickcount').innerHTML = "Pick #" + picks;
    									if (seconds == 0) {
										switch (rounds){
											
											case 1:
												autoDraft("draft_qb"); 
												break;
											case 2:
												autoDraft("draft_qb"); 
												break;
											case 3:
												autoDraft("draft_rb");
												break;
											case 4:
												autoDraft("draft_rb");
												break;
											case 5:
												autoDraft("draft_wr");
												break;
											case 6:
												autoDraft("draft_wr");
												break;
											case 7:
												autoDraft("draft_wr");
												break;
											case 8:
												autoDraft("draft_te");
												break;
											case 9:
												autoDraft("draft_rb");
												break;
											case 10:
												autoDraft("draft_rb");
												break;
											case 11:
												autoDraft("draft_wr");
												break;
											case 12:
												autoDraft("draft_te");	
												break;
											case 13:
												autoDraft("draft_wr");
												break;
											case 14:
												autoDraft("draft_k");
												break;
											case 15:
												autoDraft("draft_rb");
												break;
										}

										seconds = maxSeconds;
										if (picks == 8){
											maxSeconds = 0;
											if(rounds >= 15){
												document.getElementById('draftinfo').innerHTML = "DRAFT COMPLETE!";
												<?php $result = pg_query($dbconn, "INSERT INTO user1.draft VALUES ('true')"); if(!result){echo "An error occured";}?>
												<?php $_SESSION["draft"] = 'true'; ?>
												document.getElementById('order2').innerHTML = "N/A";
												document.getElementById('order3').innerHTML = "N/A";
											}else{
												picks = 1;
												rounds++;
												seconds = maxSeconds;
											}
										}else{
											picks++;
										}											
										
      									} else {    
        									seconds--;
    									}
    								}

								function start(){
									var countdownTimer = setInterval('draftcycle()', 1000);
									document.getElementById("start").style.visibility = "hidden";
								}

								function shuffle(o){
									for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
									return o;
								}
	
											
								function autoDraft(str){
									
								
									if (str=="") {
    										document.getElementById("txtHint").innerHTML="";
   										return;
  									} 
  									if (window.XMLHttpRequest) {
    										// code for IE7+, Firefox, Chrome, Opera, Safari
  										xmlhttp=new XMLHttpRequest();
 									} else { // code for IE6, IE5
   										xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  									}
 									xmlhttp.onreadystatechange=function() {
   										if (xmlhttp.readyState==4 && xmlhttp.status==200) {
     											document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
   										}
  									}
									var test = picks - 1;
									var newString = newArray[test] + "' WHERE player_id = (SELECT player_id FROM ";
									str = newString + str;
									
 									xmlhttp.open("GET","autodraft.php?q="+str,true);
  									xmlhttp.send();

								}
							
								function bestAvailable(str) {
  									if (str=="") {
    										document.getElementById("txtHint").innerHTML="";
   										return;
  									} 
  									if (window.XMLHttpRequest) {
    										// code for IE7+, Firefox, Chrome, Opera, Safari
  										xmlhttp=new XMLHttpRequest();
 									} else { // code for IE6, IE5
   										xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  									}
 									xmlhttp.onreadystatechange=function() {
   										if (xmlhttp.readyState==4 && xmlhttp.status==200) {
     											document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
   										}
  									}
 									xmlhttp.open("GET","bigboard.php?q="+str,true);
  									xmlhttp.send();
								}
								
								function manualDraft(str) {
									if (str=="") {
    										document.getElementById("txtHint").innerHTML="";
   										return;
  									} 
  									if (window.XMLHttpRequest) {
    										// code for IE7+, Firefox, Chrome, Opera, Safari
  										xmlhttp=new XMLHttpRequest();
 									} else { // code for IE6, IE5
   										xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  									}
 									xmlhttp.onreadystatechange=function() {
   										if (xmlhttp.readyState==4 && xmlhttp.status==200) {
     											document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
   										}
  									}
									holdagain = picks - 1;
									var newString = myArray[holdagain] + "' WHERE full_name = '" + str;
									alert(newString);
									 
 									xmlhttp.open("GET","draftpick.php?q="+newString,true);
									xmlhttp.send();								
								}
	
							</script>
							
							<form>
							<select name="users" onchange="bestAvailable(this.value)">
								<option value="draft_qb">Sort by position:</option>
								<option value="draft_qb">QB</option>
								<option value="draft_rb">RB</option>
								<option value="draft_wr">WR</option>
								<option value="draft_te">TE</option>
								<option value="draft_k">K</option>
							</select>
							</form>
							<br>
							<div style="width:100%; overflow:hidden;">
							<div id="txtHint" style="width:300px; float:left;"><b>Big Board</b></div>
					
					
							<div id="order" style="margin-left:460px;">
							
							</div>


							<br>
							<div id="order3" style="margin-left:500px;"></div>
							<br>
							<div id="order2" style="margin-left:500px;"></div>
							<br>
							</div>	
							
						</div>
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

</body>

</html>
