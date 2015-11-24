<?php

		$users = array();

		$result = pg_query($dbconn, "SELECT username FROM user1.user_info WHERE username <> '{$_SESSION['name']}'");
		while($line = pg_fetch_array($result))
		{
			$users[] = $line[username];
		}
		$user1  = $users[0];
		$user2 = $users[1];
		$user3 = $users[2];
		$user4 = $users[3];
		$user5 = $users[4];
		$user6 = $users[5];
		$user7 = $users[6];	
?>
