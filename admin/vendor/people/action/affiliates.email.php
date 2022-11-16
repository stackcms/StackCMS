<?php
/******************************************************
 * Action:			Email an Affiliate
 * Description:		Show page for emailing an affiliate
 */


// Process affiliates email form
if( isset( $_POST['email'] ) )
{
	$row = $database->get_assoc("SELECT * FROM `tcg_affiliates` WHERE `aff_id`='".$_POST['id']."'");

	// Send email if all queries are correct
	// Use PHP send mail() function if exists
	if( function_exists( 'mail' ) )
	{
		$email = $row['aff_email'];
		$subject = $tcgname.': Affiliate Contact Form';

		$message = "$tcgowner at $tcgname has sent you the following message:\n";
		$message .= "{$_POST['message']}\n\n";
		$message .= "-- $tcgowner\n";
		$message .= "$tcgname: $tcgurl\n";

		$headers = "From: $tcgname <$tcgemail>\n";
		$headers .= "Reply-To: $tcgname <$tcgemail>";

		if( mail($email,$subject,$message,$headers) )
		{
			$success[] = "Your message to ".$row['aff_owner']." @ ".$row['aff_email']." from ".$row['aff_subject']." TCG has been successfully sent!";
		}

		else
		{
			$error[] = "Sorry, there was an error and the email could not be sent to ".$row['aff_owner']." @ ".$row['aff_email']." from ".$row['aff_subject']." TCG.";
		}
	}

	// Use SMTP if send mail() function doesn't exist
	else
	{
		$email = $row['aff_email'];
		$name = $row['aff_owner'];
		$subject = $tcgname.': Affiliate Contact Form';

		$message = "$tcgowner at $tcgname has sent you the following message:<br />";
		$message .= "{$_POST['message']}<br /><br />";
		$message .= "-- $tcgowner<br />";
		$message .= "$tcgname: $tcgurl<br />";

		@include($tcgpath.'admin/mail/index.php');
		$success[] = "Your message to ".$row['aff_owner']." @ ".$row['aff_email']." from ".$row['aff_subject']." TCG has been successfully sent!";
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
	$row = $database->get_assoc("SELECT * FROM `tcg_affiliates` WHERE `aff_id`='$id'");
	echo '<h1>Email an Affiliate</h1>
	<p>Use this form to send an email to '.$row['aff_owner'].', owner of '.$row['aff_subject'].'.<b>This is not the form for sending an email to all affiliates.</b><br />
	If you need to send an email to all of the affiliates of '.$tcgname.', please use <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&action=email-all">this form</a>.</p>

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

	<div class="box" style="width: 600px;">
	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<h4>Recipient: '.$row['aff_owner'].' @ '.$row['aff_subject'].'</h4>
	<b>Message:</b>
	<textarea name="message" rows="10" class="form-control"></textarea><br />
	<input type="submit" name="email" class="btn btn-success" value="Send Message" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
	</form>
	</div>';
}
?>