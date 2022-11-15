<?php
/********************************************
 * Tab:				Trade Form
 * Description:		Display user's trade form
 */


if( isset( $_POST['submit'] ) )
{
	$uemail = $sanitize->for_db($_POST['email']);
	$uname = $sanitize->for_db($_POST['name']);
	$uurl = $sanitize->for_db($_POST['url']);
	$umc = $sanitize->for_db($_POST['member']);
	$give = $sanitize->for_db($_POST['giving']);
	$for = $sanitize->for_db($_POST['for']);
	$id = $sanitize->for_db($_POST['id']);

	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

	// Use PHP send mail function
	if( function_exists( 'mail' ) )
	{
		$email = $row['usr_email'];
		$subject = $tcgname.': Trade Request';

		$message = "The following member has sent you a trade request: \n";
		$message .= "Name: $name \n";
		$message .= "Email: $email \n";
		$message .= "URL: $url \n";
		$message .= "Offering: $give \n";
		$message .= "For: $for \n";
		$message .= "Member Cards?: $member \n";

		$headers = "From: $name <$email> \n";
		$headers .= "Reply-To: <$email>";

		if( mail($email,$subject,$message,$headers) )
		{
			echo '<h2>Success!</h2>
			<p>Your trade request has been successfully sent! The member should (hopefully) respond within a week.</p>';
		}
	}

	// Use Google SMTP if send mail doesn't exist
	else if( !function_exists( 'mail' ) )
	{
		$email = $row['usr_email'];
		$name = $row['usr_name'];
		$subject = $tcgname.': Trade Request';

		$message = "The following member has sent you a trade request:<br />";
		$message .= "Name: $uname<br />";
		$message .= "Email: $uemail<br />";
		$message .= "URL: $uurl<br />";
		$message .= "Offering: $give<br />";
		$message .= "For: $for<br />";
		$message .= "Member Cards?: $umc<br />";

		@include($tcgpath.'admin/mail/index.php');
		echo '<h2>Success!</h2>
		<p>Your trade request has been successfully sent! The member should (hopefully) respond within a week.</p>';
	}

	// Throw error if both SMTP and send mail doesn't work
	else
	{
		echo '<h2>Error</h2>
		<p>It looks like there was an error in processing your trade form. Why don\'t you check out their website to see if they have a trade form there?</p>';
	}
} // end form process


$mem = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_name`='$id'");
echo '<ul>
	<li>Please allow at least <i>7 days</i> for a response to your trade request.</li>
	<li>If the form doesn\'t work, feel free to email me at <b>'.$mem['usr_email'].'</b></li>
	<li><b>Please spell out card names COMPLETELY.</b> (ie. do NOT type cardname01/02; DO type cardname01, cardname02)</li>
	<li>If you aren\'t sure what to give me, just put <b>card00</b> and I\'ll visit your profile!</li>
</ul>

<form method="post" action="'.$tcgurl.'members.php?id='.$id.'">
	<input type="hidden" name="id" value="'.$mem['usr_id'].'" />
	<table width="100%" class="table table-sliced table-striped">
	<tbody><tr>
		<td width="20%" align="right"><b>Name:</b></td>
		<td width="78%"><input type="text" name="name" value="" style="width: 92%;" /></td>
	</tr>
	<tr>
		<td align="right"><b>Email:</b></td>
		<td><input type="text" name="email" value="" style="width: 92%;" />
	</tr>
	<tr>
		<td align="right"><b>Trade Post:</b></td>
		<td><input type="text" name="url" value="http://" style="width: 92%;" /></td>
	</tr>
	<tr>
		<td align="right"><b>Member Cards:</b></td>
		<td>
			<input type="radio" name="member" value="yes" /> Yes &nbsp;&nbsp; 
			<input type="radio" name="member" value="no"> No
		</td>
	</tr>
	<tr>
		<td align="right"><b>You Give:</b></td>
		<td><input type="text" name="giving" value="" style="width: 92%;" /></td>
	</tr>
	<tr>
		<td align="right"><b>You Want:</b></td>
		<td><input type="text" name="for" value="" style="width: 92%;" /></td>
	</tr>
	</tbody></table>
	<input type="submit" name="submit" class="btn-success" value="Trade" /> 
	<input type="reset" name="reset" class="btn-danger" value="Reset" />
</form>';
?>