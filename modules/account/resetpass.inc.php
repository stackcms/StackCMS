<?php
/**********************************************
 * Module:			Password Reset
 * Description:		Process user password reset
 */


if( empty( $login ) )
{
	header( "Location: account.php?do=login" );
}

else
{
	if( isset( $_POST['submit'] ) )
	{
		$check->Password();
		$id = $sanitize->for_db($_POST['id']);
		$email = $sanitize->for_db($_POST['email']);
		$name = $sanitize->for_db($_POST['name']);

		$pass = $_POST['password'];
		$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
		$hash = md5( rand(0,1000) );

		$recipient = "$email";
		$subject = "$tcgname: Changed Your Password";
		$message = "Your password has been changed to $pass. Please keep this email in a safe place, as we cannot recover lost passwords.\n";
		$headers = "From: $tcgname <$tcgemail> \n";
		$headers .= "Reply-To: $tcgname <$tcgemail>";

		$update = $database->query("UPDATE `user_list` SET `usr_pass`='$hashed_pass', `usr_hash`='$hash' WHERE `usr_id`='$id'");

		// Use PHP send mail function
		if( function_exists( 'mail' ) )
		{
			$email = "$email";
			$subject = "$tcgname: Changed Your Password";
			$message = "Your password has been changed to $pass. Please keep this email in a safe place, as we cannot recover lost passwords.";
			$headers = "From: $tcgname <$tcgemail> \n";
			$headers .= "Reply-To: $tcgname <$tcgemail>";

			if( $update === TRUE ) {
				mail($email,$subject,$message,$headers);
				session_destroy();
				header("Location: account.php?do=login");
			}

			$success[] = "Your password has been changed and has been sent to your email.";
		}

		// Use Google SMTP if send mail doesn't exist
		else if ( !function_exists( 'mail' ) )
		{
			$email = "$email";
			$name = "$name";
			$subject = "$tcgname: Changed Your Password";
			$message = "Your password has been changed to $pass. Please keep this email in a safe place, as we cannot recover lost passwords.";

			if( $update === TRUE )
			{
				@include($tcgpath.'admin/mail/index.php');
				session_destroy();
				header("Location: account.php?do=login");
			}

			$success[] = "Your password has been changed and has been sent to your email.";
		}

		// Throw error if both SMTP and send mail doesn't work
		else
		{
			$error[] = "Sorry, there was an error and your password was not updated.";
		}
	}

	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
	echo '<h1>Change Your Password</h1>
	<p>Use this form to change your password. <b>You will be logged out after this change</b>. Make sure you have any card activity logged before this.</p>

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
	<input type="hidden" name="id" value="'.$row['usr_id'].'" />
	<input type="hidden" name="email" value="'.$row['usr_email'].'" />
	<input type="hidden" name="name" value="'.$player.'" />
	<table width="100%" class="table table-sliced table-striped">
	<tbody>
		<tr>
			<td width="25%"><b>Current Password:</b></td>
			<td width="75%"><input type="password" name="current" value="" style="width:95%;" /></td>
		</tr>
		<tr>
			<td><b>New Password:</b></td>
			<td>
				<input type="password" name="password" placeholder="********" style="width:44%;" /> 
				<input type="password" name="password2" placeholder="Retype password for verification" style="width:45%;" />
			</td>
		</tr>
	</tbody></table>
	<input type="submit" name="submit" class="btn-success" value="Change Password" /> 
	<input type="reset" name="reset" class="btn-danger" value="Reset" />
	</form>';
}
?>