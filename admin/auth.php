<?php
ini_set('display_errors',1);

session_start();

function auth()
{
	if(!empty($_SESSION['s4210']))
		return $_SESSION['s4210']['em'];
	if(!empty($COOKIE['s4210']))
	{
		if($t = jason_decode(stripslashes($_COOKIE['s4210']), true))
		{
			if (time() > $t['exp'])
				return false; // to expire the user
			$db = newDB();
			$q = $db->prepare('SELECT * FROM USER WHERE EMAIL = ?');
			$q->execute(array($t['em']));
			if($res = $q->fetch())
			{
				$realk = hash_hmac('sha1', $t['exp'].$res['PASSWORD'], $res['SALT']);
				if($realk == $t['k']) {
					$_SESSION['s4210'] = $t;
					return $t['em'];
				}
			}
		}		
	}
	return false;
}

?>