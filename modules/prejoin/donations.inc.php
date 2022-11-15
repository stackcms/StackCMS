<?php
/*************************************************
 * Module:			Deck Donations
 * Description:		Process prejoin deck donations
 */


if( isset($_POST['submit']) )
{
	$check->Value();
	$deck = $sanitize->for_db($_POST['deckname']);
	$pass = $sanitize->for_db($_POST['pass']);
	$url = $sanitize->for_db($_POST['url']);
	$date = date("Y-m-d", strtotime("now"));

	$pass_query = $database->query("SELECT `deck_pass` FROM `tcg_donations` WHERE `deck_filename`='$deck'");
	$row = mysqli_fetch_assoc($pass_query);

	// Check if password matches
	if( $row['deck_pass'] != $pass )
	{
		exit('<h1>Deck Donations : Error</h1><p>It seems like the password you\'ve provided is incorrect!</p>');
	}

	// Else, update donations
	$update = $database->query("UPDATE `tcg_donations` SET `deck_url`='$url', `deck_type`='Donations' WHERE `deck_filename`='$deck' AND `deck_pass`='$pass'");

	// Process form if queries are correct
	if( !$update )
	{
		$error[] = '<p>There was an error while processing your donations. Kindly send us your donation details instead at <u>'.$tcgemail.'</u>. Thank you and sorry for the inconvenience!</p> '.mysqli_error($update);
	}

	else
	{
		$success[] = '<p>Your deck donation has been received and a deck maker will check it!<br />
		Once it is approved, you will receive your rewards on your mailbox. Donate <a href="/prejoin.php?form=deck-donations">more?</a></p>';
	}
} // end form process

echo '<h1>Deck Donations</h1>
<p>Use the form below to submit your donations. Please keep in mind the exclusive guidelines before donating any deck.</p>

<ul>
	<li>Donated images must be in high quality and unedited, preferrably 600x600 pixels up to 1600x1600 pixels.</li>
	<li>Horizontal images are much preferred than vertical ones to avoid the subjects getting cropped just to fit the card template.</li>
	<li>Only images that is related to [TCG SUBJECT] that will fit to our sets are allowed.</li>
	<li>Donations need at least XX images, but more is encouraged.</li>
	<li>For <b>every deck</b> you donate, you will get X random cards and X CURRENCY.</li>
</ul>

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

<form method="post" action="'.$tcgurl.'prejoin.php?form='.$form.'">
	<table width="100%" cellspacing="3" class="border">
	<tr>
		<td class="headLine" width="15%">File Name:</td>
		<td class="tableBody"><input type="text" name="deckname" placeholder="e.g. whitetigers" style="width:90%;"></td>
		<td class="headLine" width="15%">Password:</td>
		<td class="tableBody"><input type="text" name="pass" placeholder="********" style="width:90%;"></td>
	</tr>
	<tr>
		<td class="headLine">Link:</td>
		<td class="tableBody" colspan="3"><input type="text" name="url" placeholder="Link to download your donation" style="width:96%;"></td>
	</tr>
	<tr>
		<td class="tableBody" align="center" colspan="4">
			<input type="submit" name="submit" class="btn-success" value="Send Donation" /> 
			<input type="reset" name="reset" class="btn-danger" value="Reset" />
		</td>
	</tr>
	</table>
</form>';
?>