<?php

	$user = $_SESSION['name'];
        $users = array();

        $result = pg_query($dbconn, "SELECT DISTINCT fantasy_team FROM player WHERE fantasy_team IS NOT NULL AND fantasy_team <> '{$user}'");
        while($line = pg_fetch_array($result, $i, PGSQL_ASSOC))
        {
        	$users[] = $line[fantasy_team];
        }
        $user1 = $users[0];
        $user2 = $users[1];
        $user3 = $users[2];
        $user4 = $users[3];
        $user5 = $users[4];
        $user6 = $users[5];
        $user7 = $users[6];
?>
