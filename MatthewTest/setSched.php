<?php
	$users = array();

	$result = pg_query($dbconn, "SELECT username FROM user1.user_info LIMIT 8"); //order by for consistency
	while($line = pg_fetch_array($result))
	{
		$users[] = $line[username];
	}
	$user0  = $users[0];
	$user1 = $users[1];
	$user2 = $users[2];
	$user3 = $users[3];
	$user4 = $users[4];
	$user5 = $users[5];
	$user6 = $users[6];	
	$user7 = $users[7];
	
	$result = pg_query($dbconn, "
	INSERT INTO user1.schedule (week,home,away,complete) 
	VALUES
	(1,$user1,$user0,false),
	(1,$user3,$user2,false),
	(1,$user5,$user4,false),
	(1,$user7,$user6,false),
	
	(2,$user2,$user0,false),
	(2,$user3,$user1,false),
	(2,$user6,$user5,false),
	(2,$user4,$user7,false),
	
	(3,$user7,$user1,false),
	(3,$user5,$user2,false),
	(3,$user0,$user3,false),
	(3,$user4,$user6,false),
	
	(4,$user6,$user3,false),
	(4,$user0,$user4,false),
	(4,$user1,$user5,false),
	(4,$user2,$user7,false),
	
	(5,$user5,$user0,false),
	(5,$user4,$user1,false),
	(5,$user6,$user2,false),
	(5,$user3,$user7,false),
	
	(6,$user1,$user2,false),
	(6,$user3,$user4,false),
	(6,$user7,$user5,false),
	(6,$user0,$user6,false),
	
	(7,$user7,$user0,false),
	(7,$user5,$user3,false),
	(7,$user2,$user4,false),
	(7,$user1,$user6,false),
	
	(8,$user0,$user1,false),
	(8,$user2,$user3,false),
	(8,$user4,$user5,false),
	(8,$user6,$user7,false),
	
	(9,$user0,$user2,false),
	(9,$user1,$user3,false),
	(9,$user7,$user4,false),
	(9,$user5,$user6,false),
	
	(10,$user3,$user0,false),
	(10,$user6,$user4,false),
	(10,$user2,$user5,false),
	(10,$user1,$user7,false),
	
	(11,$user4,$user0,false),
	(11,$user5,$user1,false),
	(11,$user7,$user2,false),
	(11,$user3,$user6,false),
	
	(12,$user7,$user3,false),
	(12,$user1,$user4,false),
	(12,$user0,$user5,false),
	(12,$user2,$user6,false),
	
	(13,$user6,$user0,false),
	(13,$user2,$user1,false),
	(13,$user4,$user3,false),
	(13,$user5,$user7,false),
	
	(14,$user6,$user1,false),
	(14,$user4,$user2,false),
	(14,$user3,$user5,false),
	(14,$user0,$user7,false)
	");
?>
