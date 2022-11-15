<?php
/*****************************************
 * Module:			Quit TCG
 * Description:		Process user quit form
 */


if( empty( $login ) )
{
	header( "Location: account.php?do=login" );
}

else
{
	if( isset($_POST['submit']) )
	{
		$from = $sanitize->for_db($_POST['sender']);
		$to = $sanitize->for_db($_POST['recipient']);
		$date = date("Y-m-d H:i:s", strtotime("now"));
		$date2 = date("Y-m-d", strtotime("now"));
		$message = $_POST['message'];
		$message = nl2br($message);
		$message = str_replace("'", "\'", $message);

		$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('Quitting','$message','$from','$to','Out','In','0','1','0','0','','$date')");

		if( !$insert )
		{
			$error[] = "Sorry, there was an error in processing your form.<br />
			Send the information to ".$tcgemail." and we will send you a reply ASAP. ".mysqli_error($insert)."";
		}

		else
		{
			$get = $database->get_assoc("SELECT `usr_reg` FROM `user_list` WHERE `usr_name`='$from'");
			$database->query("UPDATE `user_mbox` SET `msg_origin`=LAST_INSERT_ID() WHERE `msg_id`=LAST_INSERT_ID()");
			$database->query("INSERT INTO `user_list_quit` (`usr_name`,`usr_mcard`,`usr_joined`,`usr_quit`) VALUES ('$from','mc-$from','".$get['usr_reg']."','$date2')");
			$database->query("DELETE FROM `user_list` WHERE `usr_name`='$from'");
			$database->query("DELETE FROM `user_logs` WHERE `log_name`='$from'");
			$database->query("DELETE FROM `user_items` WHERE `itm_name`='$from'");
			$database->query("DELETE FROM `user_trades` WHERE `trd_name`='$from'");
			$database->query("DELETE FROM `user_trades_rec` WHERE `trd_name`='$from'");
			$database->query("DELETE FROM `game_motm_list` WHERE `motm_name`='$from'");
			$success[] = "Sorry to see you leave. Hopefully you change your mind and join us in the future again!";
		}
	}

	echo '<h1>Quit '.$tcgname.'</h1>
	<p>Are you sure about this? ∑(ﾟﾛﾟ〃) If you are&mdash;and we\'re sorry to see you leave, you can use the form below to inform us.</p>

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
	<input type="hidden" name="sender" value="'.$row['usr_name'].'" />
	<input type="hidden" name="recipient" value="'.$tcgowner.'" />
	<textarea name="message" rows="5" style="width:95%;">Please tell us something why you\'re leaving or a farewell message.</textarea><br />
	<input type="submit" name="submit" class="btn-success" value="Send" /> 
	<input type="reset" name="reset" class="btn-danger" value="Reset" />
	</form>';
}
?>