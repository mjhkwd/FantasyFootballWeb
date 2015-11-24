<?php
	$user = $_SESSION['name'];

                if($_SESSION["draft"])
                        $draft = 'true';
                else
                        $draft = false;

                if($_SESSION['loggedin'])
                        $logged = 'true';

                session_unset();

                if($logged)
                        $_SESSION['loggedin'] = true;

                $_SESSION['name'] = $user;
                if($draft == true)
                        $_SESSION['draft'] = true;


                $result = pg_query($dbconn, "SELECT username FROM user1.user_info WHERE username <> '{$_SESSION['name']}' AND description = 'online'");
                while($line = pg_fetch_array($result))
                {
                        $users[] = $line[username];
                }
                $len = count($users);

                for ($x = 0; $x < $len; $x++)
                {
                        $_SESSION[$users[$x]] = 'online';
                }

?>
