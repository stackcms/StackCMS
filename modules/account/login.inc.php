<?php
/*****************************************
 * Module:			Account Login
 * Description:		Process user login
 */


// Begin processing user login
if( $act == "loggedin" )
{
	$email = $_POST["email"];
	$passw = $_POST["password"];
	$redirect = $_POST['redirect'];
	$errMsg="";

	if( $email != "" && $passw != "" )
	{
		$authRow = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_active` = '1' AND `usr_email` = '" . $email . "'");
		$hash = isset($authRow['usr_pass']) ? $authRow['usr_pass'] : '';
		$result = password_verify($passw, $hash);

		$userID = $authRow['usr_id'];
		$userEMAIL = $authRow['usr_email'];
		$userSTAT = $authRow['usr_status'];
		$userNAME = $authRow['usr_name'];

		if( $authRow['usr_active'] == 0 )
		{
			echo '<h1>Login Invalid</h1>It seems like your account hasn\'t been activated yet. Kindly please click the activation link that was sent to your email that you used when you signed up for an account.';
			exit;
		}

		if( $result && $userID != 0 )
		{
			$_SESSION['USER_ID'] = $userID;
			$_SESSION['USR_LOGIN'] = $userEMAIL;
			$_SESSION['USR_NAME'] = $userNAME;
			$_SESSION['USR_STATUS'] = $userSTAT;

			$log = date('Y-m-d H:i:s', strtotime("now"));
			$log2 = date('Y-m-d', strtotime("now"));

			$database->query("UPDATE `user_list` SET `usr_sess`='$log' WHERE `usr_id`='$userID'");
			$database->query("UPDATE `user_trades_rec` SET `trd_date`='$log2' WHERE `trd_name`='$userNAME'");

			// Set status to active when logged in for inactive members
			if( $userSTAT == 'Inactive' )
			{
				$database->query("UPDATE `user_list` SET `usr_status`='Active' WHERE `usr_name`='$userNAME'");
			}

			// Redirect to member panel
			if( $redirect == $tcgurl."account.php?do=login" )
			{
                header("Location: account.php");
			}
			else
			{
                header("Location: $redirect");
			}
		}
		else
		{
			header("Location: account.php?do=login&msg=invalid");
		}
	}

	else
	{
		header ("Location: account.php?do=login&msg=missing");
	}
}

else
{
	if( $msg == "invalid" )
	{
		echo '<h1>Login : Error</h1>
		<p>Oops, it looks like the email and/or password you entered is not in our database. Check your spelling and try again or contact us at '.$tcgemail.'.</p>';
	}

	else if( $msg == "missing" )
	{
		echo '<h1>Login : Error</h1>
		<p>Oops, it looks like one or more values from the form were not entered. Please go back and try again.</p>';
	}

	else
	{
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		echo '<h1>Login</h1>
		<p>Below is the login form for the member panel here at '.$tcgname.'. <b>This is only for current members</b>. If you would like to join, please click <a href="'.$tcgurl.'members.php?page=join">here</a> to see the rules and join.</p>
		<center>
		<form method="post" action="'.$tcgurl.'account.php?do='.$do.'&action=loggedin">
		<input type="hidden" name="redirect" value="'.$actual_link.'">
		<table width="80%" class="table table-sliced table-striped">
		<tbody><tr>
			<td width="15%"><b>Email:</b></td>
			<td width="85%"><input type="text" name="email" placeholder="username@domain.tld" style="width:90%;" /></td>
		</tr>
		<tr>
			<td><b>Password:</b></td>
			<td><input type="password" name="password" placeholder="********" style="width:90%;" /></td>
		</tr></tbody>
		</table>
		<input type="submit" name="submit" class="btn-success" value="Login"> 
		<button onclick="window.location.href=\'account.php?do=lostpass\'" class="btn-primary">Lost Password?</a>
		</form>
		</center>';
	}
}
?>