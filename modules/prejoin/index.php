<?php
/***************************************************
 * Module:			Prejoin Main
 * Description:		Shows main page of prejoin phase
 */


echo '<h1>Prejoin Donations</h1>
<p>Below is the complete list of the prejoin claimed and donated decks. If you want to claim or donate a deck, you can do so <a href="'.$tcgurl.'prejoin.php?form=claims">here (for claims)</a> and <a href="'.$tcgurl.'prejoin.php?form=donations">here (for donations)</a>.</p>

<p>Please keep in mind that you have to claim the deck first before sending in your donations!</p>

<table width="100%" cellspacing="3" class="border">
<tr>
	<td class="headLine" width="10%">Status</td>
	<td class="headLine" width="15%">Category</td>
	<td class="headLine" width="25%">Features</td>
	<td class="headLine" width="20%">Set/Series</td>
	<td class="headLine" width="20%">Donator</td>
</tr>';
$sql = $database->query("SELECT * FROM `tcg_donations` ORDER BY `deck_cat`, `deck_filename` ASC");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	$set = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['deck_set']."'");
	echo '<tr>
	<td class="tableBody" align="center">';
	if( $row['deck_url'] == "" )
	{
		echo '<button type="button" class="btn-dead">Claimed</button>';
	}

	else if( $row['deck_maker'] != "" )
	{
		echo '<button type="button" class="btn-primary">Queueing</button>';
	}

	else {
		echo '<button type="button" class="btn-dead">Donated</button>';
	}
	echo '</td>
	<td class="tableBody" align="center">';
		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['deck_cat']."'");
		echo $cat['cat_name'];
	echo '</td>
	<td class="tableBody" align="center">';
	if( $row['deck_url'] == "" )
	{
		echo $row['deck_feature'];
	}

	else
	{
		echo '<a href="'.$row['deck_url'].'" target="_blank">'.$row['deck_feature'].'</a>';
	}
	echo '</td>
	<td class="tableBody" align="center">'.$set['set_name'].'</td>
	<td class="tableBody" align="center">'.$row['deck_donator'].'</td>
	</tr>';
}
echo '</table>';
?>