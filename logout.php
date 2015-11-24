<?php
	session_start();
	
	$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")									//connect to dbms
			or die("Could not connect: " . pg_last_error());
	
	$username = $_SESSION['name'];
	$result = pg_query($dbconn, "UPDATE user1.user_info SET description = '' WHERE username = '{$username}'");

	$_SESSION = array(); //reset all variables inside session

	if (ini_get("session.use_cookies")) { //reset all cookies if they were used
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}								

	session_destroy(); //end session
	
	header("location: index.php"); //Redirect after logout
?>
