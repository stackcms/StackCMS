<?php
/*********************************************
 * Module:			Lost Password
 * Description:		Process user lost password
 */


if( isset($_POST['submit']) )
{
	$check->Value();
	if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,6})$/",strtolower($_POST['email'])) )
	{
		exit("<h1>Error</h1>\nThat e-mail address is not valid, please use another.<br /><br />");
	}

	$email = $sanitize->for_db($_POST['email']);
	$name = $sanitize->for_db($_POST['name']);

	$password = substr(md5(date("c")), 0, 8);
	$hashed_pass = password_hash($password, PASSWORD_DEFAULT);
	$scrampass = md5( $password );

	$num = $database->num_rows("SELECT * FROM `user_list` WHERE `usr_email`='$email'");
	$sql = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$email'");

	if( $num === 0 )
	{
		exit("<h1>Error</h1>\nThat email address does not exist in our database. Please go back and check your spelling and try again.");
	}

	else
	{
		$id = $sql['usr_id'];
		$update = $database->query("UPDATE `user_list` SET `usr_pass`='$hashed_pass', `usr_hash`='$scrampass' WHERE `usr_id`='$id'");

		// Use PHP send mail function
		if( function_exists( 'mail' ) )
		{
			$email = "$email";
			$subject = "$tcgname: Reset Your Password";
			$message = "Your password has been reset to $password. Please log in and change it.\n";
			$headers = "From: $tcgname <$tcgemail>\n";
			$headers .= "Reply-To: $tcgname <$tcgemail>";

			if( $update === TRUE )
			{
				mail($email,$subject,$message,$headers);
				$success[] = "Your password has been reset and sent to the email you have provided.<br />Please log in and change your password once you have checked your email.";
			}	
		}

		// Use Google SMTP if send mail doesn't exist
		else if( !function_exists( 'mail' ) )
		{
			$email = "$email";
			$name = "$name";
			$subject = "$tcgname: Reset Your Password";
			$message = "Your password has been reset to $password. Please log in and change it.";

			@include($tcgpath.'admin/mail/index.php');
			$success[] = "Your password has been reset and sent to the email you have provided.<br />Please log in and change your password once you have checked your email.";
		}

		// Throw error if both SMTP and send mail doesn't work
		else
		{
			$error[] = "Sorry, there was an error and your password was not updated.";
		}
	}
}

echo '<h1>Lost Password</h1>
<p>If you have forgotten your password, please feel free to use the reset password form below.</p>

<center>';
if( isset( $error ) )
{
	foreach( $error as $msg )
	{
		echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
	}
}
if( isset( $success ) )
{
	foreach( $success as $msg )
	{
		echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<form method="post" action="'.$tcgurl.'account.php?do='.$do.'">
<input type="hidden" name="name" value="'.$player.'" />
<center><table width="70%" class="border" cellspacing="3">
<tr>
	<td class="headLine">Email:</td>
	<td class="tableBody">
		<input type="text" name="email" value="" style="width:62%;" /> 
		<input type="submit" name="submit" class="btn-success" value="Reset Password" />
	</td>
</tr>
</table></center>
</form>';
?>