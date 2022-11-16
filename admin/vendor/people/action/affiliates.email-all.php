<?php
/********************************************************
 * Action:			Email All Affiliates
 * Description:		Show page for emailing all affiliates
 */


// Process email all affiliates form
if( isset( $_POST['email-all'] ) )
{
	$check->Value();

	echo '<p>Your email was sent to the following:</p>';
	$sql = $database->query("SELECT * FROM `tcg_affiliates` ORDER BY `aff_owner`");
	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		// Send email if all queries are correct
		// Use PHP send mail() function if exists
		if( function_exists( 'mail' ) )
		{
			$email = $row['aff_email'];
			$subject = $tcgname.": Affiliate Contact Form";

			$message = "$tcgowner at $tcgname has sent you the following message:\n";
			$message .= "{$_POST['message']}\n\n";
			$message .= "-- $tcgowner\n";
			$message .= "$tcgname: $tcgurl\n";

			$headers = "From: $tcgname <$tcgemail> \n";
			$headers .= "Reply-To: $tcgname <$tcgemail>";

			if( mail($email,$subject,$message,$headers) )
			{
				echo "Success: ".$row['aff_owner']." (".$row['aff_subject'].") @ ".$row['aff_email']."<br />\n";
			}

			else
			{
				echo "Failed: ".$row['aff_owner']." (".$row['aff_subject'].") @ ".$row['aff_email']."<br />\n";
			}
		}

		// Use SMTP if send mail() function doesn't exist
		else
		{
			$email = $row['aff_email'];
			$name = $row['aff_owner'];
			$subject = $tcgname.": Affiliate Contact Form";

			$message = "$tcgowner at $tcgname has sent you the following message:<br />";
			$message .= "{$_POST['message']}<br /><br />";
			$message .= "-- $tcgowner<br />";
			$message .= "$tcgname: $tcgurl<br />";

			@include($tcgpath.'admin/mail/index.php');
			echo "Success: ".$row['aff_owner']." (".$row['aff_subject'].") @ ".$row['aff_email']."<br />\n";
		}
	}
	echo '</div>';
}


// Show email all affiliates form
echo '<h1>Email All Affiliates</h1>
<p>Need to contact all of '.$tcgname.'\'s affiliates? Use this form.<br />
If you need to email one affiliate, please use the contact form from <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&action=email">this page</a>.</p>

<div class="box" style="width: 600px;">
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'">
<b>Message:</b>
<textarea name="message" rows="5" class="form-control"></textarea><br />
<input type="submit" name="email-all" class="btn btn-success" value="Send Message" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
</form>
</div>';
?>