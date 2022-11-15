<?php
/***************************************
 * Module:			Messages Main
 * Description:		Display user's inbox
 */


$sql = $database->query("SELECT * FROM `user_mbox` WHERE `msg_recipient`='$id' AND `msg_box_to`='In' AND `msg_del_to`='0' ORDER BY `msg_id` DESC");
$counts = mysqli_num_rows( $sql );

if( empty( $view ) )
{
	if( isset( $_POST['delete'] ) )
	{
		$getID = $_POST['in_to'];
		foreach( $getID as $mid )
		{
			$del = $_POST['del_to'];
			$update = $database->query("UPDATE `user_mbox` SET `msg_del_to`='$del' WHERE `msg_id`='$mid'");
		}

		if( !$update )
		{
			$error[] = "Sorry, there was an error and your message was not deleted. ".mysqli_error($update)."";
		}

		else
		{
			$success[] = "Your message has been deleted!";
		}
	}


	echo '<h1>My Messages</h1>
	<p>Here are the list of the personal messages that you\'ve received from your fellow traders.</p>
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

	<form method="post" action="'.$tcgurl.'messages.php?id='.$id.'">
	<table width="100%" class="table table-bordered table-striped">';
	if( $counts == 0 )
	{
		echo '<tbody>
		<tr>
			<td width="100%" valign="top" class="tableBody">
				<p>You don\'t have any messages.</p>
			</td>
		</tr>
		</tbody>';
	}

	else
	{
		echo '<thead>
		<tr>
			<td width="10%" align="center"><span class="fas fa-check" aria-hidden="true"></span></td>
			<td width="90%"><b>Message Information:</b></td>
		</tr>
		</thead>

		<tbody>';
		while( $msg = mysqli_fetch_assoc( $sql ) )
		{
			if( $msg['msg_see_to'] == "1" )
			{
				echo '<tr>
				<td align="center">
					<input type="hidden" name="in_to[]" value="'.$msg['msg_id'].'" />
					<input type="checkbox" name="del_to" value="1" />
				</td>
				<td>
					<a href="'.$tcgurl.'messages.php?id='.$id.'&view='.$msg['msg_id'].'"><b>'.$msg['msg_subject'].'</b></a><br />
					From: <b>'.$msg['msg_sender'].'</b> on <b>'.date("F d, Y h:i A", strtotime($msg['msg_date'])).'</b>
				</td>
				</tr>';
			}

			else
			{
				echo '<tr>
				<td align="center">
					<input type="hidden" name="in_to[]" value="'.$msg['msg_id'].'" />
					<input type="checkbox" name="del_to" id="del_to" value="1" />
				</td>
				<td>
					<a href="'.$tcgurl.'messages.php?id='.$id.'&view='.$msg['msg_id'].'">'.$msg['msg_subject'].'</a><br />
					From: '.$msg['msg_sender'].' on '.date("F d, Y h:i A", strtotime($msg['msg_date'])).'</td>
				</tr>';
			}
		} // end while
		echo '<tr>
			<td colspan="2">
				<input type="submit" name="delete" id="delete" class="btn-danger" value="Delete" />
			</td>
		</tr>
		</tbody>';
	}
	echo '</table>
	</form>';
} // end empty view


else
{
	if( isset( $_POST['submit'] ) )
	{
		$from = $sanitize->for_db($_POST['sender']);
		$to = $sanitize->for_db($_POST['recipient']);
		$subject = $sanitize->for_db($_POST['subject']);
		$date = $sanitize->for_db($_POST['timestamp']);
		$origin = $sanitize->for_db($_POST['origin']);
		$message = $_POST['message'];
		$message = nl2br($message);
		$message = str_replace("'", "\'", $message);

		$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('$subject','$message','$from','$to','Out','In','0','1','0','0','$origin','$date')");

		if( !$insert )
		{
			$error[] = "Sorry, there was an error and your message was not sent. ".mysqli_error($insert)."";
		}

		else
		{
			$success[] = "Your message has been sent to ".$to."!";
		}
	}


	$mrow = $database->get_assoc("SELECT * FROM `user_mbox` WHERE `msg_id`='$view' AND `msg_recipient`='$id' AND `msg_box_to`='In'");
	$mid2 = $mrow['msg_id'];
	$subject = stripslashes($mrow['msg_subject']);
	$sentby = stripslashes($mrow['msg_sender']);
	$message = stripslashes($mrow['msg_text']);
	$origin = stripslashes($mrow['msg_origin']);
	$timestamp = date("F d, Y h:i:s", strtotime($mrow['msg_date']));
	$date = date("Y-m-d H:i:s", strtotime("now"));

	$breaks = array("<br />","<br>","<br/>");
	$message = str_ireplace($breaks, " ", $message);

	$update = $database->query("UPDATE `user_mbox` SET `msg_see_to`='0' WHERE `msg_id`='$mid2' AND `msg_recipient`='$id' AND `msg_box_to`='In'");

	echo '<h1>My Messages</h1>
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

	<form method="post" action="'.$tcgurl.'messages.php?id='.$id.'&view='.$view.'">
		<input type="hidden" name="origin" id="origin" value="'.$origin.'" />
		<input type="hidden" name="timestamp" id="timestamp" value="'.$date.'" />
		<input type="hidden" name="sender" id="sender" value="'.$id.'" />
		<table width="100%" class="table table-sliced table-striped">
		<tbody>
		<tr>
			<td width="20%"><b>Subject:</b></td>
			<td width="80%"><input type="text" name="subject" id="subject" value="RE: '.$subject.'" style="width: 95%;" /></td>
		</tr>
		<tr>
			<td><b>Reply to:</b></td>
			<td><input type="text" name="recipient" id="recipient" value="'.$sentby.'" readonly style="width: 95%;" /></td>
		</tr>
		<tr>
			<td><b>Message:</b></td>
			<td><textarea name="message" id="message" rows="10" style="width:95%;">

--------------------
'.$timestamp.'
'.$message.'
			</textarea></td>
		</tr>
		</tbody></table>
		<input type="submit" name="submit" id="submit" class="btn-success" value="Send" /> 
		<input type="reset" name="reset" id="reset" class="btn-danger" value="Reset" />
	</form>';
}
?>