<?php
/******************************************************
 * Module:			Deck Voting
 * Description:		Shows deck voting for prejoin decks
 */


if( isset( $_POST['submit'] ) )
{
	$check->Value();
	$name = $sanitize->for_db($_POST['name']);
	$date = date("Y-m-d H:i:s", strtotime("now"));

	// Check if vote is more than 24 hours
	$t = $database->get_assoc("SELECT * FROM `deck_votes` WHERE `vote_name`='$name'");
	$now = date("Y-m-d H:i:s", strtotime('now'));
	$yesterday = $t['vote_date'];

	if( $now > $yesterday )
	{
		for( $i=1; $i<=10; $i++ )
		{
			$card = "deck$i";
			$deck = $sanitize->for_db($_POST[$card]);
			$decks .= $deck.', ';
			$update = $database->query("UPDATE `tcg_cards` SET `card_votes`=card_votes+'1' WHERE `card_filename`='$deck'");
		} // end for
		$decks = substr_replace($decks,"",-2);

		// Process form if queries are correct
		if( !$update )
		{
			$error[] = '<p>There was an error while processing your votes. Kindly send us your voting details instead at <u>'.$tcgemail.'</u>. Thank you and sorry for the inconvenience!</p> '.mysqli_error($update);
		}

		else
		{
			$database->query("INSERT INTO `deck_votes` (`vote_name`,`vote_decks`,`vote_date`) VALUES ('$name','$decks','$date')");
			$success[] = '<p>Your votes has been added to the database. You can vote again after 24 hours for the decks you\'ve just voted.</p>';
		}
	}

	else
	{
		$error[] = '<p>It seems like it hasn\'t been 24 hours since the last time you\'ve sent in a vote. Kindly wait for a couple of more hours before voting again, thank you!</p>';
	}
} // end form process

// Show deck voting page
echo '<h1>Deck Voting</h1>
<p>Use the form below to vote 10 decks that you wish to be released as prejoin decks.<br />
<u>Please vote only 1 deck per dropdown once a day!</u> You can vote every day until the voting phase expires and you can vote for the same decks per day.</p>

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

echo '<!-- SET YOUR OWN COUNTER -->
	<div data-type="countdown" data-id="2104673" class="tickcounter" style="width: 40%; position: relative; padding-bottom: 12%">
		<a href="//www.tickcounter.com/countdown/2104673/voting-period" title="Voting Period">Voting Period</a>
		<a href="//www.tickcounter.com/" title="Countdown">Countdown</a>
	</div>

	<script>(function(d, s, id) { var js, pjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//www.tickcounter.com/static/js/loader.js"; pjs.parentNode.insertBefore(js, pjs); }(document, "script", "tickcounter-sdk"));</script>
</center><br />

<form method="post" action="'.$tcgurl.'prejoin.php?form='.$form.'">
	<table width="100%" cellspacing="3" class="border">
	<tr>
		<td class="headLine" width="15%">Name:</td>
		<td class="tableBody"><input type="text" name="name" placeholder="Jane Doe" style="width:90%;"></td>
	</tr>';
	for( $i=1; $i<=10; $i++ )
	{
		echo '<tr>
		<td class="headLine">Vote '.$i.'</td>
		<td class="tableBody">
			<select name="deck'.$i.'" style="width:95;%">';
			$decks = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Upcoming' ORDER BY `card_filename` ASC");
			while( $row = mysqli_fetch_assoc( $decks ) )
			{
				echo '<option value="'.$row['card_filename'].'">'.$row['card_deckname'].' ('.$row['card_filename'].')</option>';
			}
			echo '</select>
		</td>
		</tr>';
	}
	echo '<tr>
		<td class="tableBody" align="center" colspan="4">
			<input type="submit" name="submit" class="btn-success" value="Send Votes" /> 
			<input type="reset" name="reset" class="btn-danger" value="Reset" />
		</td>
	</tr>
	</table>
</form>

<h2>Vote Logs</h2>
<div style="padding-right: 20px; margin-top: 20px; line-height: 20px; font-size: 14px; overflow: auto; height: 300px;">';
$vchk = $database->query("SELECT * FROM `deck_votes` ORDER BY `vote_date` DESC");
while( $votes = mysqli_fetch_assoc( $vchk ) )
{
	echo '<u>'.date("Y-m-d H:i:s", strtotime($votes['vote_date'])).':</u> <i>'.$votes['vote_name'].'</i> voted for '.$votes['vote_decks'].'<br />';
}
echo '</div>';
?>