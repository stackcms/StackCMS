<?php
/**************************************************
 * Action:			Approve Members
 * Description:		Show page for approving members
 */


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Process form
else
{
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
	$update = $database->query("UPDATE `user_list` SET `usr_status`='Active', `usr_level`='1', `usr_mcard`='Yes' WHERE `usr_id`='$id'");

	if( $update )
	{
		$activity = '<span class="fas fa-user" aria-hidden="true"></span> <a href="'.$tcgurl.'/members.php?id='.$row['usr_name'].'">'.$row['usr_name'].'</a> became a member of '.$tcgname.'!';
		
		$date = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_slug`,`act_rec`,`act_date`) VALUES ('".$row['usr_name']."','members','$activity','$date')");
		
		$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('$name','Gift','Yes','".$settings->getValue('prize_start_bonus')."','".$settings->getValue('prize_start_cur')."','$date')");

		$email = "$row[usr_email]";
		$name = $row['usr_name'];
		$subject = "$tcgname: Approved!";

		$message = "Thank you for joining $tcgname! You have been approved by $tcgowner and can now begin to play games and take freebies. You should have received your starter pack already on the site and in your email. You do not need to reply to this email.\n\n";
		$message .= "Now that you are an active member, there are lots of fun things to do. Check out the interactive section for the current game rounds or post your wishlists!\n\n";
		$message .= "Don't forget that all members should be active at least once per two months. If you need to go on hiatus, please let us know! $tcgname does not delete members from the member list for inactivity, however any member who is inactive for two months will be listed as inactive and must submit an update form to reactive themselves.\n\n";
		$message .= "That should be everything you need to know! Have any questions? Make sure to look through the Information page and if you still can't find the answer, shoot us an email! Thanks again for joining and happy trading!\n\n";
		$message .= "-- $tcgowner\n";
		$message .= "$tcgname: $tcgurl\n";

		$headers = "From: $tcgname <$tcgemail> \n";
		$headers .= "Reply-To: $tcgname <$tcgemail>";

		// Use PHP send mail() function if available
		if( function_exists( 'mail' ) )
		{
			if( !mail($email,$subject,$message,$headers) )
			{
				echo '<p>'.$row['name'].' has been updated in the database but has not be emailed. Please send them an email to let them know they have been approved.</p>';
			}

			else
			{
				echo '<p>'.$row['usr_name'].' has been successfully emailed and has been updated in the database.</p>';
			}
		}

		// Use SMTP if send mail() function is not available
		else
		{
			@include($tcgpath.'admin/mail/index.php');
			echo '<p>'.$row['usr_name'].' has been successfully emailed and has been updated in the database.</p>';
		}
	}

	else
	{
		echo '<p>Sorry, there was an error and the email could not be sent to '.$row['usr_name'].' @ '.$row['usr_email'].'. They also were not updated in the database. Please send them an email to let them know they have been approved and use the edit form from the <a href="'.$PHP_SELF.'?mod='.$mod.'">members</a> page to update their status.</p>';
	}
}
?>