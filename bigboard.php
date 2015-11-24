<?php
$q = ($_GET['q']);

$dbconn = pg_connect("host=dbhost-pgsql.cs.missouri.edu dbname=cs3380f14grp7 user=cs3380f14grp7 password=73X14dDP")									//connect to dbms
			or die("Could not connect: " . pg_last_error());


$query = "SELECT full_name, team FROM ".$q." WHERE fantasy_team IS NULL LIMIT 25";
$header = "<th>Rank</th><th>Player Name</th><th>Team</th>\n";

$result = pg_query($query);
$rows = pg_num_rows($result);

echo "<table border=1>\n";
echo $header;
$x = 1;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
	echo "\t<tr>\n";
	echo "<td>$x</td>";
	foreach ($line as $col_value){
		echo "\t\t<td>$col_value</td>\n";
	}
	$x = $x+1;
	echo "\t</tr>\n";
}
echo "</table>\n";


pg_close($dbconn);
?>
