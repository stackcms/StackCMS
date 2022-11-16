<?php
/*****************************************************
 * Action:			Email All Members
 * Description:		Show page for emailing all members
 */


// Process email all members form
if ( isset( $_POST['email-all'] ) )
{
	echo '<p>Your email was sent to the following:</p>';
	$sql = $database->query("SELECT * FROM `user_list` ORDER BY `usr_name`");
	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		$from = $tcgowner;
		$to = $row['usr_name'];
		$date = date("Y-m-d H:i:s", strtotime("now"));
		$message = $_POST['message'];
		$message = nl2br($message);
		$message = str_replace("'", "\'", $message);

		$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('Admin Message','$message','$from','$to','Out','In','0','1','0','0','','$date')");

		if( !$insert )
		{
			$error[] = "Sorry, there was an error and the email could not be sent to your members.<br />
			Send the message directly to them instead. ".mysqli_error($insert);
		}

		else
		{
			$database->query("UPDATE `user_mbox` SET `msg_origin`=LAST_INSERT_ID() WHERE `msg_id`=LAST_INSERT_ID()");
			$success[] = "Your message has been sent to your members! Kindly wait patiently for them to get back to you within the next few days.";
		}
	}
}


// Show email all form
echo '<h1>Email All Members</h1>
<p>Need to contact all of '.$tcgname.'\'s members? Use this form.<br />
If you need to email one member, please use the contact form from <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'">this page</a>.</p>

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

<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'">
<div class="box" style="width: 600px;">
	<p><b>Message:</b><br />
	<textarea name="message" rows="10" class="form-control"></textarea></p>
	<p><input type="submit" name="email" class="btn btn-success" value="Email all members" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
</div>
</form>';
?>