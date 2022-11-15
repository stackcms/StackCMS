<?php
/*************************************************
 * Module:			General Contact
 * Description:		Contact form to contact admins
 */


if ( isset($_POST['submit']) )
{
	$from = $sanitize->for_db($_POST['sender']);
	$to = $sanitize->for_db($_POST['recipient']);
	$date = date("Y-m-d H:i:s", strtotime("now"));
	$message = $_POST['message'];
	$message = nl2br($message);
	$message = str_replace("'", "\'", $message);

	$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('General Contact','$message','$from','$to','Out','In','0','1','0','0','','$date')");

	// Process form if queries are correct
	if( !$insert )
	{
		$error[] = "Sorry, there was an error while processing your form.<br />
		Send the information to ".$tcgemail." and we will send you a reply ASAP. ".mysqli_error($insert)."";
	}

	else
	{
		$database->query("UPDATE `user_mbox` SET `msg_origin`=LAST_INSERT_ID() WHERE `msg_id`=LAST_INSERT_ID()");
		$success[] = "Thank you for sending in a contact form! I will try to get back to you within the next few days.<br />
		If you don't hear from me within a week, please poke me via Discord (Aki#6429).";
	}
} // end form process



// Show contact form
echo '<h1>General Contact</h1>
<p>If you have any inquiries regarding '.$tcgname.' that you wish to ask or share to the administrator, please use the form below to keep in touch with them. Kindly give them at least 48 hours to get back to you! If for any reason that you haven\'t heard from them after the given time, you can poke them directly on our Discord server as chances are your email was missed or it didn\'t reach them at all.</p>

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

<form method="post" action="'.$tcgurl.'services.php?form='.$form.'">
	<input type="hidden" name="sender" value="'.$player.'" />
	<input type="hidden" name="recipient" value="'.$tcgowner.'" />
	<textarea name="message" rows="5" style="width:96%;">Type your message here.</textarea><br />
	<input type="submit" name="submit" class="btn-success" value="Send Inquiry" /> 
	<input type="reset" name="reset" class="btn-danger" value="Reset" />
</form>';
?>