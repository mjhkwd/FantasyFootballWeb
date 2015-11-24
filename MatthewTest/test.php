<?php
	$week = 1;
	for($i=1;$i<15;$i++)
	{
		echo "</table>
		<TABLE WIDTH=370>
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
	}
?>
