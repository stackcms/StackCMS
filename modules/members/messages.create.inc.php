<?php
/****************************************************
 * Module:			Create Messages
 * Description:		Process new message form to users
 */


if( isset( $_POST['submit'] ) )
{
	$check->Value();
	$id = $sanitize->for_db($_POST['id']);
	$from = $sanitize->for_db($_POST['sender']);
	$to = $sanitize->for_db($_POST['recipient']);
	$subject = $sanitize->for_db($_POST['subject']);
	$date = date("Y-m-d H:i:s", strtotime("now"));
	$message = $_POST['message'];
	$message = nl2br($message);
	$message = str_replace("'", "\'", $message);

	$insert = $database->query("INSERT INTO `user_mbox` (`msg_id`,`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('','$subject','$message','$from','$to','Out','In','0','1','0','0','','$date')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and your message was not sent. ".mysqli_error($insert)."";
	}

	else
	{
		$database->query("UPDATE `user_mbox` SET `msg_origin`=LAST_INSERT_ID() WHERE `msg_id`=LAST_INSERT_ID()");
		$success[] = "Your message has been sent to ".$to."!<br />They may be able to respond back the next time they logged in.";
	}
} // end form process


$sql = $database->get_assoc("SELECT `usr_name` FROM `user_list` WHERE `usr_name`='$to'");
echo '<h1>Create Message</h1>
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

<form method="post" action="'.$tcgurl.'messages.php?id='.$id.'&page='.$page.'">
	<input type="hidden" name="timestamp" id="timestamp" value="'.date("Y-m-d", strtotime("now")).'" />
	<input type="hidden" name="sender" id="sender" value="'.$player.'" />
	<input type="hidden" name="id" id="id" value="'.$id.'" />
	<table width="100%" class="table table-sliced table-striped">
	<tbody>
	<tr>
		<td width="20%"><b>Subject:</b></td>
		<td width="80%"><input type="text" name="subject" id="subject" style="width:95%;" /></td>
	</tr>
	<tr>
		<td><b>Recipient:</b></td>
		<td>';
		if( empty( $to ) )
		{
			echo '<select name="recipient" id="recipient" style="width:99%;" />
				<option>----- Select Recipient -----</option>';
				$sql = $database->query("SELECT `usr_name` FROM `user_list` ORDER BY `usr_name` ASC");
				while( $row = mysqli_fetch_assoc( $sql ) )
				{
					echo '<option value="'.$row['usr_name'].'">'.$row['usr_name'].'</option>';
				}
			echo '</select>';
		}

		else if( $to = $sql['usr_name'] )
		{
			echo '<input type="text" style="width: 98%;" name="recipient" id="recipient" value="'.$sql['usr_name'].'" readonly />';
		}
		echo '<td>
	</tr>
	<tr>
		<td><b>Message:</b></td>
		<td><textarea name="message" id="message" style="width: 95%;" rows="10" /></textarea></td>
	</tr>
	</tbody></table>
	<input type="submit" name="submit" id="submit" class="btn-success" value="Send" /> 
	<input type="reset" name="reset" id="reset" class="btn-danger" value="Reset" />
</form>';
?>