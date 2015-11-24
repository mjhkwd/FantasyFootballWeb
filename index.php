<?php
	session_start();
		
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
<?php if(!$_SESSION["loggedin"])
{ 
	echo "
        <div class='row'>
            <div class='box'>
                <div class='col-lg-12 text-center'>
                    <h2 class='brand-before'>
                        <small>Welcome to</small>
                    </h2>
                    <h1 class='brand-name'>Fantasy Football</h1>
                    <hr class='tagline-divider'>
                    <h2>
                        <small>By
                            <strong>Group 7</strong>
                        </small>
                    </h2>
                </div>
            </div>
        </div>
        <?php
        ";
}

        if ($_SESSION['loggedin'])
        { 
			echo $_SESSION["draft"];
			$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")									//connect to dbms
			or die("Could not connect: " . pg_last_error());
			
			require 'functions/getOtherTeams.php';
			
			echo"	
        <div class='row'>
            <div class='box'>
                <div class='col-lg-12'>
                    <hr>
                    <h2 class='intro-text text-center'>
                        <strong>Schedule</strong>
                    </h2>
                    <hr>
                    <div>
";
			$week = 1;
			for($i=1;$i<15;$i++)
			{
				echo "
				<TABLE WIDTH=370 style='float:left;'>	
					<TR>
					  <TD BGCOLOR=BLUE COLSPAN=3 ALIGN=CENTER WIDTH=360><B><FONT COLOR=WHITE>Week #$i</FONT></B></TD>
					<TR>";
			   		$result = pg_query($dbconn,"SELECT home, away FROM user1.schedule WHERE week = $week");

			//		returns 1,2;3,4;5,6;7,8
				while($line = pg_fetch_array($result))
				{
					echo "
						<TR>
						  <TD ALIGN=RIGHT WIDTH=180>{$line[away]}</TD>
						  <TD ALIGN=CENTER WIDTH=10><B> at </B></TD>
						  <TD WIDTH=180 ALIGN=LEFT VALIGN=TOP>{$line[home]}</TD>
						</TR>
					";
				}
						$week++;
						echo "</table>";
			}
			?>
                    </div>
                </div>
            </div>
        </div>
        <?php
	}
	?>
<div class='row'>
	<div class='box'>
		<div class='col-lg-12 text-center'>
			<h2 class='brand-before'>
				<small>Current Active Users:</small>
			</h2>
			<h1 class='brand-name'>
				<?php
					echo $user;
				?>
				<?php
					if($len > 0)
					{
						for($i = 0; $i < $len; $i++)
						{
							echo "<hr class='tagline-divider'>";
							echo "{$usersonline[$i]}";
						}
					}
				?>
			</h1>
		    <hr class='tagline-divider'>
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
