<?php
/*****************************************
 * Module:			Account Verification
 * Description:		Verify user account
 */


if( isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']) )
{
	$email = $sanitize->for_db($_GET['email']);
	$hash = $sanitize->for_db($_GET['hash']);

	$match = $database->num_rows("SELECT `usr_email`, `usr_hash`, `usr_active` FROM `user_list` WHERE `usr_email`='".$email."' AND `usr_hash`='".$hash."' AND `usr_active`='0'");

	if( $match > 0 )
	{
		// We have a match, activate the account
		$database->query("UPDATE `user_list` SET `usr_active`='1' WHERE `usr_email`='".$email."' AND `usr_hash`='".$hash."'");
		echo '<h1>Account Verified</h1>';
		echo '<p>Your account has been activated, you can now login.</p>';
	}

	else
	{
		// No match -> invalid url or account has already been activated.
		echo '<h1>Error Verification</h1>';
		echo '<p>The URL is either invalid or you already have activated your account.</p>';
	}
}

else
{
	// Invalid approach
	echo '<h1>Error Verification</h1>';
	echo '<p>Invalid approach, please use the link that has been sent to your email.</p>';
}
?>