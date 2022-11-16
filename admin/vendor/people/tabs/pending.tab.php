<?php
/********************************************************
 * Tab:				Pending Members
 * Description:		Show main tab of pending members list
 */


// Process mass approval form of pending members
if( isset( $_POST['mass-approve'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
		$update = $database->query("UPDATE `user_list` SET `usr_status`='Active', `usr_level`='1', `usr_mcard`='Yes' WHERE `usr_id`='$id'");

		// Use PHP send mail() function if available
		if( function_exists( 'mail' ) )
		{
			$recipient = "$row[usr_email]";
			$subject = "$tcgname: Approved!";

			$message = "Thank you for joining $tcgname! You have been approved by $tcgowner and can now begin to play games and take freebies. You should have received your starter pack already on the site and in your email. You do not need to reply to this email.\n\n";
			$message .= "Now that you are an active member, there are lots of fun things to do. Check out the interactive section for the current game rounds or post your wishlists!\n\n";
			$message .= "Don't forget that all members should be active at least once per two months. If you need to go on hiatus, please let us know! $tcgname does not delete members from the member list for inactivity, however any member who is inactive for two months will be listed as inactive and must submit an update form to reactive themselves.\n\n";
			$message .= "That should be everything you need to know! Have any questions? Make sure to look through the Information page and if you still can't find the answer, shoot us an email! Thanks again for joining and happy trading!\n\n";
			$message .= "-- $tcgowner\n";
			$message .= "$tcgname: $tcgurl\n";

			$headers = "From: $tcgname <$tcgemail> \n";
			$headers .= "Reply-To: $tcgname <$tcgemail>";

			if( mail($recipient,$subject,$message,$headers) )
			{
				$activity = '<span class="fas fa-user" aria-hidden="true"></span> <a href="'.$tcgurl.'/members.php?id='.$row['usr_name'].'">'.$row['usr_name'].'</a> became a member of '.$tcgname.'!';
				$date = date("Y-m-d", strtotime("now"));
				$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_date`) VALUES ('".$row['usr_name']."','$activity','$date')");
				$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('$name','Gift','Yes','".$settings->getValue('prize_start_reg')."','".$settings->getValue('prize_start_currency')."','$date')");
			}
		}

		// Use SMTP if send mail() function is not available
		else
		{
			$activity = '<span class="fas fa-user" aria-hidden="true"></span> <a href="'.$tcgurl.'/members.php?id='.$row['usr_name'].'">'.$row['usr_name'].'</a> became a member of '.$tcgname.'!';
			$date = date("Y-m-d", strtotime("now"));
			$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_date`) VALUES ('".$row['usr_name']."','$activity','$date')");
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('$name','Gift','Yes','".$settings->getValue('prize_start_reg')."','".$settings->getValue('prize_start_currency')."','$date')");

			$subject = "$tcgname: Approved!";
			$email = $row['usr_email'];
			$name = $row['usr_name'];
			$message = "Thank you for joining $tcgname! You have been approved by $tcgowner and can now begin to play games and take freebies. You should have received your starter pack already on the site and in your email. You do not need to reply to this email.<br /><br />
			Now that you are an active member, there are lots of fun things to do. Check out the interactive section for the current game rounds or post your wishlists!<br /><br />
			Don't forget that all members should be active at least once per two months. If you need to go on hiatus, please let us know! $tcgname does not delete members from the member list for inactivity, however any member who is inactive for two months will be listed as inactive and must submit an update form to reactive themselves.<br /><br />
			That should be everything you need to know! Have any questions? Make sure to look through the Information page and if you still can't find the answer, shoot us an email! Thanks again for joining and happy trading!<br /><br />
			-- $tcgowner<br />
			$tcgname: $tcgurl";
			@include($tcgpath.'admin/mail/index.php');
		}
	}

	if( !$update && !mail($recipient,$subject,$message,$headers) )
	{
		$error[] = 'Sorry, there was an error and the email could not be sent to the members. They also were not updated in the database. Please send them an email to let them know they have been approved and use the edit form from the <a href="'.$tcgurl.'admin/members.php">members</a> page to update their status.';
	}

	else if( !$update )
	{
		$error[] = 'The members has been successfully emailed but has not been updated in the database. Please use the edit form from the <a href="'.$tcgurl.'admin/members.php">members</a> page to update their status.';
	}

	else
	{
		$success[] = 'The members has been successfully emailed and has been updated in the database!';
	}
}

// Process mass deletion of pending members
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `user_list` WHERE `usr_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the members were not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The members were successfully deleted.";
	}
}

// Show pending members list and form
echo '<h2>Pending</h2>
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
echo '</center>';

$admin->members('Pending');
?>