<?php
/*************************************************
 * Module:			Cards Main
 * Description:		Shows main page for card decks
 */


if( empty( $set ) )
{
	echo'<h1>Cards</h1>
	<table width="100%" cellspacing="3">
	<tr>
		<td width="35%" valign="top"><img src="'.$tcgcards.'filler.'.$tcgext.'" align="left" /> <img src="'.$tcgcards.'pending.'.$tcgext.'" align="left" /></td>
		<td width="65%" valign="top">Below you will find all of the current card decks arranged in sets where decks are sorted by <em>categories</em>, then by file name. The <b>#</b> indicates the number of cards, while the <b>$</b> indicates the worth of the cards. If you can\'t find a deck in particular, try using the <i>ctrl/command + f</i> to find it.</td>
	</tr>
	</table>

	<center>
		<div class="box-info">
			<b>Pro Tip!</b> If you are having troubles with your eTCG\'s auto upload function, you can grab the weekly releases <a href="'.$tcgurl.'cards.php?view=zips">here</a>.
		</div>
	<br />';

	$sql_set = $database->query("SELECT `card_set`, COUNT(card_deckname) FROM `tcg_cards` WHERE `card_status`='Active' GROUP BY `card_set` ORDER BY `card_set` ASC");
	while( $row = mysqli_fetch_assoc( $sql_set ) )
	{
		$cardSET = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['card_set']."'");
		echo '<a href="'.$tcgurl.'cards.php?set='.$cardSET['set_name'].'"><img src="'.$tcgcards.''.$cardSET['set_name'].'.png" border="0" /></a> ';
	}
	echo '</center>';
}

else
{
	echo'<h1>Cards : '.$set.'</h1>';

	// SHOW SEARCH FORM
	$general->cardSearch('cards','card','Active');

	$escape = $database->escape($set);
	$cardSET = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_name`='$escape'");
	$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_set`='".$cardSET['set_id']."' AND `card_status`='Active' ORDER BY `card_filename` ASC");
	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		$digits = rand(01,$row['card_count']);
		if( $digits < 10 )
		{
			$digit = "0$digits";
		}
		else
		{
			$digit = $digits;
		}
		$card = $row['card_filename'].''.$digit;
		echo '<div class="deck_prev">
			<a href="'.$tcgurl.'cards.php?view=released&deck='.$row['card_filename'].'"><img src="'.$tcgcards.''.$card.'.'.$tcgext.'"></a><br />
			<a href="'.$tcgurl.'cards.php?view=released&deck='.$row['card_filename'].'">'.$row['card_deckname'].'</a>
		</div>';
	}

	$tcgName = substr_replace($settings->getValue( 'tcg_name' ), "", -4);
	if( $set == $tcgName )
	{
		$events = $database->query("SELECT * FROM `tcg_cards_event` ORDER BY `event_group` ASC, `event_date` DESC");
		$group = null;
		while( $row = mysqli_fetch_assoc( $events ) )
		{
			if( $row['event_group'] != $group )
			{
				$group = $row['event_group'];
				echo '<h2>'.$row['event_group'].'</h2>';
			}
			echo '<div class="deck_prev">
				<img src="'.$tcgcards.''.$row['event_filename'].'.'.$tcgext.'" title="'.$row['event_title'].' ('.$row['event_filename'].')" /><br />
				<b>'.$row['event_title'].'</b>
			</div>';
		}
	}
}
?>