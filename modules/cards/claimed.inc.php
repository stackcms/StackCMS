<?php
/**********************************************
 * Module:			Deck Claims
 * Description:		Shows list of claimed decks
 */


echo '<h1>Cards : Claimed Decks</h1>
<p>Below is the complete list of our claimed decks. These decks are no longer subject for claiming and/or donation and are already on its way to being made. Make sure to check this list first before sending in a donation.</p>';

// SHOW SEARCH FORM
$general->cardSearch('donations','deck','Claims');

echo '<table width="100%" class="table table-sliced table-striped"><thead>
<tr>
	<td align="center" width="15%"><b>Category</b></td>
	<td align="center" width="25%"><b>Features</b></td>
	<td align="center" width="20%"><b>Set</b></td>
	<td align="center" width="20%"><b>Donator</b></td>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_donations` WHERE `deck_type`='Claims' ORDER BY `deck_filename` ASC");
while( $row=mysqli_fetch_assoc( $sql ) )
{
	$c = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['deck_cat']."'");
	$s = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['deck_set']."'");
	echo '<tr>
	<td align="center">'.$c['cat_name'].'</td>
	<td align="center">'.$row['deck_feature'].'</td>
	<td align="center">'.$s['set_name'].'</td>
	<td align="center">'.$row['deck_donator'].'</td>
	</tr>';
}

echo '</tbody></table>';
?>