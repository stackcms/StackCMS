<?php
/*******************************************************
 * Action:			Approve Affiliates
 * Description:		Show page for approving an affiliate
 */


// Check is page is being accessed directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

else {
	// Process approval form
	$row = $database->get_assoc("SELECT * FROM `tcg_affiliates` WHERE `aff_id`=$id");
	$update = $database->query("UPDATE `tcg_affiliates` SET `aff_status`='Active' WHERE `aff_id`='$id'");

	// Send email if all queries are correct
	// Use PHP send mail() function if exists
	if( function_exists( 'mail' ) )
	{
		$recipient = $row['aff_email'];
		$subject = $tcgname.': Affiliate Approved!';

		$message = "Thank you for affiliating with $tcgname! Your application has been approved.\n\n";
		$message .= "-- $tcgowner\n";
		$message .= "$tcgname: $tcgurl\n";
				
		$headers = "From: $tcgname <$tcgemail> \n";
		$headers .= "Reply-To: $tcgname <$tcgemail>";

		if( mail($recipient,$subject,$message,$headers) && $update === TRUE )
		{
			$activity = '<span class="fas fa-globe" aria-hidden="true"></span> <a href="'.$row['aff_url'].'" target="_blank">'.$row['aff_subject'].' TCG</a> has been added as '.$tcgname.'\'s new affiliate.';
			
			$date = date("Y-m-d", strtotime("now"));
			$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_date`) VALUES ('$name','$activity','$date')");
			echo '<p>'.$row['aff_owner'].' has been successfully emailed and has been updated in the database.</p>';
		}

		else
		{
			echo '<p>Sorry, there was an error and the email could not be sent to '.$row['aff_owner'].' @ '.$row['aff_email'].'. They also were not updated in the database. Please send them an email to let them know they have been approved and use the edit form from the <a href="'.$tcgurl.'admin/affiliates.php">affiliates</a> page to update their status.</p>';
		}
	}

	// Use SMTP if send mail function is not available
	else
	{
		$activity = '<span class="fas fa-globe" aria-hidden="true"></span> <a href="'.$row['aff_url'].'" target="_blank">'.$row['aff_subject'].' TCG</a> has been added as '.$tcgname.'\'s new affiliate.';

		$date = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_date`) VALUES ('$name','$activity','$date')");

		$email = $row['aff_email'];
		$name = $row['aff_owner'];
		$subject = "$tcgname: Affiliate Approved!";
			
		$message = "Thank you for affiliating with $tcgname! Your application has been approved.<br /><br />";
		$message .= "-- $tcgowner<br />";
		$message .= "$tcgname: $tcgurl";

		@include($tcgpath.'admin/mail/index.php');
		echo '<p>'.$row['aff_owner'].' has been successfully emailed and has been updated in the database.</p>';
	}
}
?>