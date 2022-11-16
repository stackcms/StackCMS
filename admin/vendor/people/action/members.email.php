<?php
/**************************************************
 * Action:			Email a Member
 * Description:		Show page for emailing a member
 */


// Process email a member form
if( isset( $_POST['email'] ) )
{
	$id = $sanitize->for_db($_POST['id']);
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

	$from = $sanitize->for_db($_POST['sender']);
	$to = $sanitize->for_db($_POST['recipient']);
	$date = date("Y-m-d H:i:s", strtotime("now"));
	$message = $_POST['message'];
	$message = nl2br($message);
	$message = str_replace("'", "\'", $message);

	$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('Admin Message','$message','$from','$to','Out','In','0','1','0','0','','$date')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the email could not be sent to ".$row['usr_name']." @ ".$row['usr_email']."<br />
		Send the message directly to ".$row['usr_email']." instead. ".mysqli_error($insert);
	}

	else
	{
		$database->query("UPDATE `user_mbox` SET `msg_origin`=LAST_INSERT_ID() WHERE `msg_id`=LAST_INSERT_ID()");
		$success[] = "Your message has been sent to ".$to."! Kindly wait patiently for ".$to." to get back to you within the next few days.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show email form
else
{
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
	echo '<h1>Email a Member</h1>
	<p>Use this form to send an email to '.$row['name'].'. <b>This is not the form for sending an email to all members.</b><br />
	If you need to send an email to all of the members of '.$tcgname.', please use <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&action=email-all">this form</a>.</p>

	<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="sender" value="'.$tcgowner.'" />
	<div class="box" style="width: 600px;">
		<p><b>Recipient:</b><br />
		<input type="text" name="recipient" value="'.$row['usr_name'].'" class="form-control" readonly /></p>

		<p><b>Message:</b><br />
		<textarea name="message" rows="10" class="form-control"></textarea></p>

		<p><input type="submit" name="email" class="btn btn-success" value="Email Member" /> 
		<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
	</div>
	</form>';
}
?>