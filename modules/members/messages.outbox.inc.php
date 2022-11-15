<?php
/**************************************************
 * Module:			Sent Messages
 * Description:		Display sent messages of a user
 */


$sql = $database->query("SELECT * FROM `user_mbox` WHERE `msg_sender`='".$id."' AND `msg_box_from`='Out' AND `msg_del_from`='0' ORDER BY `msg_id` DESC");
$counts = mysqli_num_rows( $sql );

if( empty( $view ) )
{
	if( isset( $_POST['delete'] ) )
	{
		$mid = $_POST['out_id'];
		$del = $sanitize->for_db($_POST['del_from']);

		$update = $database->query("UPDATE `user_mbox` SET `msg_del_from`='$del' WHERE `msg_id`='$mid'");

		if( !$update )
		{
			$error[] = "Sorry, there was an error and your message was not deleted. ".mysqli_error($update)."";
		}

		else
		{
			$success[] = "Your message has been deleted!";
		}
	}


	echo '<h1>Sent Messages</h1>
	<p>Here are the list of the personal messages you\'ve sent to your fellow traders.</p>
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
	<table width="100%" class="table table-bordered table-striped">';
	if( $counts == 0 )
	{
		echo '<tbody><tr><td width="100%"><p>You don\'t have any sent messages.</p></td></tr></tbody>';
	}
	else
	{
		echo '<thead><tr><td width="10%" align="center"><span class="fas fa-check" aria-hidden="true"></span></td>
		<td width="90%"><b>Message Information:</b></td></tr></thead>
		<tbody>';
		while( $msg = mysqli_fetch_assoc( $sql ) )
		{
			echo '<tr>
			<td align="center">
				<input type="hidden" name="out_id" value="'.$msg['msg_id'].'" />
				<input type="checkbox" name="del_from" value="1" />
			</td>
			<td><a href="'.$tcgurl.'messages.php?id='.$id.'&page=outbox&view='.$msg['msg_id'].'">'.$msg['msg_subject'].'</a><br />
				Sent to: '.$msg['msg_recipient'].' on '.date("F d, Y h:i A", strtotime($msg['msg_date'])).'</td>
			</tr>';
		}
		echo '<tr>
			<td colspan="2">
				<input type="submit" name="delete" id="delete" class="btn-danger" value="Delete" />
			</td>
		</tr></tbody>';
	}
	echo '</table>
	</form>';
}

else
{
	$mrow = $database->get_assoc("SELECT * FROM `user_mbox` WHERE `msg_id`='$view' AND `msg_sender`='$id' AND `msg_box_from`='Out'");
	$subject = stripslashes($mrow['msg_subject']);
	$sentto = stripslashes($mrow['msg_recipient']);
	$message = stripslashes($mrow['msg_text']);

	$breaks = array("<br />","<br>","<br/>");
	$message = str_ireplace($breaks, "\n", $message);

	echo '<h1>Sent Messages</h1>
	<center>
	<table width="100%" class="table table-sliced table-striped">
	<tbody>
		<tr>
			<td width="20%"><b>Subject:</b></td>
			<td width="80%"><input type="text" name="subject" id="subject" value="'.$subject.'" /></td>
		</tr>
		<tr>
			<td><b>Sent to:</b></td>
			<td><input type="text" name="recipient" id="recipient" value="'.$sentto.'" /></td>
		</tr>
		<tr>
			<td><b>Message:</b></td>
			<td colspan="3"><textarea name="message" id="message" rows="10" style="width:95%;" />'.$message.'</textarea></td>
		</tr>
	</table>
	</center>';
}
?>