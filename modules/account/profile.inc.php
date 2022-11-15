<?php
/********************************************
 * Module:			Edit Profile
 * Description:		Process edit user profile
 */


if( empty( $login ) )
{
	header( "Location: account.php?do=login" );
}

else
{
	if( isset( $_POST['update'] ) )
	{
		$id = $sanitize->for_db($_POST['id']);
		$email = $sanitize->for_db($_POST['email']);
		$url = $sanitize->for_db($_POST['url']);
		$status = $sanitize->for_db($_POST['status']);
		$twitter = $sanitize->for_db($_POST['twitter']);
		$discord = $sanitize->for_db($_POST['discord']);
		$collecting = $sanitize->for_db($_POST['collecting']);
		$random = $sanitize->for_db($_POST['random']);
		$accept = $sanitize->for_db($_POST['accept']);
		$birthday = $_POST['date'];
		$about = $_POST['about'];
		$about = nl2br($about);
		$about = str_replace("'", "\'", $about);

		$update = $database->query("UPDATE `user_list` SET `usr_email`='$email', `usr_url`='$url', `usr_bday`='$birthday', `usr_status`='$status', `usr_deck`='$collecting', `usr_bio`='$about', `usr_twitter`='$twitter', `usr_discord`='$discord', `usr_rand_trade`='$random', `usr_auto_trade`='$accept' WHERE `usr_id`='$id'");

		if( !$update )
		{
			$error[] = "Sorry, there was an error and your info was not updated. ".mysqli_error($update)."";
		}
		else
		{
			$_SESSION['USR_LOGIN'] = $email;
			$success[] = "Your information has been updated!";
		}
	}

	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
	$old_bio = stripslashes($row['usr_bio']);
	echo '<h1>Edit Profile</h1>
	<p>Use this form to edit your information in the database. <b>You cannot use this form to change your password.</b> To change it, please click <a href="'.$tcgurl.'account.php?do=reset-password">here</a>.</p>

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
	<table width="100%" class="table table-sliced table-striped">
	<tbody>';
	$field->Name('edit');
	$field->Email('edit');
	$field->Website('edit');
	$field->Birthday('edit');
	echo '<tr>';
		$field->Collecting('edit');
		$field->Status('edit');
	echo '</tr>
	<tr>
		<td align="right"><b>Twitter:</b></td>
		<td><input type="text" name="twitter" value="'.$row['usr_twitter'].'" style="width: 90%;" /></td>
		<td align="right"><b>Discord:</b></td>
		<td><input type="text" name="discord" value="'.$row['usr_discord'].'" style="width: 90%;" /></td>
	</tr>
	<tr>
		<td align="right"><b>Accepts Random:</b></td>
		<td>';
		if( $row['usr_rand_trade'] == "0" )
		{
			echo '<input type="radio" name="random" value="1" /> Yes &nbsp;&nbsp; 
			<input type="radio" name="random" value="0" checked /> No';
		}
		else
		{
			echo '<input type="radio" name="random" value="1" checked /> Yes &nbsp;&nbsp; 
			<input type="radio" name="random" value="0" /> No';
		}
		echo '</td>
		<td align="right"><b>Allows Trade:</b></td>
		<td>';
		if( $row['usr_auto_trade'] == "0" )
		{
			echo '<input type="radio" name="accept" value="1" /> Yes &nbsp;&nbsp; 
			<input type="radio" name="accept" value="0" checked /> No';
		}
		else
		{
			echo '<input type="radio" name="accept" value="1" checked /> Yes &nbsp;&nbsp;
			<input type="radio" name="accept" value="0" /> No';
		}
		echo '</td>
	</tr>
	<tr>
		<td align="right"><b>Biography:</b></td>
		<td colspan="3">
			<textarea name="about" rows="5" style="width: 96%;">'.$old_bio.'</textarea>
		</td>
	</tr>
	</tbody>
	</table>
	<input type="submit" name="update" class="btn-success" value="Edit Information" />
	</form>';
}
?>