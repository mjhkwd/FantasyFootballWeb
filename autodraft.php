<?php
$q = ($_GET['q']);

$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")									//connect to dbms
			or die("Could not connect: " . pg_last_error());

$query = "UPDATE player SET fantasy_team = '".$q." WHERE fantasy_team IS NULL LIMIT 1)";

$result = pg_query($query);

pg_close($dbconn);
?>

