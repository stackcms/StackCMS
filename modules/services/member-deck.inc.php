<?php
/************************************************
 * Module:			Add a Member Deck
 * Description:		Process user member deck form
 */


// Process add a member deck form
if( isset( $_POST['submit'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$colr = $sanitize->for_db($_POST['color']);
	$url = $sanitize->for_db($_POST['url']);
	$cardcount = $settings->getValue( 'xtra_mdeck_count' );
	$cardbreak = $settings->getValue( 'xtra_mdeck_break' );

	// Check if user have member decks activated
	$countCHK = $database->num_rows("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$name'");
	if( $countCHK == 0 )
	{
		$deck = strtolower($name);
		$deckname = $deck.'01';
	}

	else
	{
		$row = $database->get_assoc("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$name' ORDER BY `ud_name` DESC");
		$deck = strtolower($name);
		$digit = substr($row['ud_name'], -2);
		$added = $digit + 1;
		if( $added < 10 )
		{
			$deckname = $deck.'0'.$added;
		}

		else
		{
			$deckname = $deck.''.$added;
		}
	}

	$insert = $database->query("INSERT INTO `tcg_cards_user` (`ud_name`,`ud_deck`,`ud_color`,`ud_url`,`ud_count`,`ud_break`,`ud_completed`) VALUES ('$name','$deckname','$colr','$url','$cardcount','$cardbreak','0')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error while processing your form.<br />Send the information to ".$tcgemail." and we will send you a reply ASAP. ".mysqli_error($insert);
	}

	else {
		$success[] = "Your member deck has been added to the database!";
	}
}

echo '<h1>Add a Member Deck</h1>
<p>A Member Deck is an activity that allows a player to create their custom-made decks based on the player\'s preference by using images and color of choice. This is basically like donating and creating a regular deck, but will have the player\'s name as its deck name.</p>
<p>'.$tcgname.'\'s member deck is composed of <i>15 cards</i>, all of which are worth <i>0</i>. These cards are only obtainable by completing the specific tasks assigned to each card. Once all the cards in the deck have been unlocked, it can then be mastered both by the player and others for rewards.</p>

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
echo '</center>';

// Let's check if the player has an existing and/or unfinished member deck!
$counts = $database->num_rows("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$player' AND `ud_completed`='0'");
if( $counts == 0 )
{
	echo '<form method="post" action="'.$tcgurl.'services.php?form='.$form.'">
	<input type="hidden" name="name" value="'.$player.'" />
	<p>Are you sure you want to activate your member deck?<br />If yes, send in the URL for your deck images and color of choice and then click the button below:</p>
	<b>URL:</b> <input type="text" name="url" placeholder="http://" style="width: 38%;" /> <b>HEX Color:</b> <input type="text" name="color" placeholder="#ff0000" style="width: 38%;" /><br />
	<input type="submit" name="submit" class="btn-success" value="Activate Member Deck" />
	</form>';
}

else
{
	echo '<center><div class="box-error">
	It looks like you still have an unfinished member deck! Kindly please finish your current member deck first before applying for a new one. Thank you!
	</div></center>';
}
?>