<?php
/************************************************************
 * Page:			Decks
 * Description:		Show main page of decks list per category
 */


if( empty( $id ) )
{
	echo '<h1>All Decks</h1>
	<p>This is the list of all your decks from all of your deck categories, sorted by their ID.</p>
	<div class="box">
	<table width="100%" id="admin-cardsall" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="40%">Deck Name</th>
		<th scope="col" align="center" width="20%">Category</th>
		<th scope="col" align="center" width="20%">Status</th>
	</tr></thead>
	<tbody>';

	$sql = $database->query("SELECT * FROM `tcg_cards` ORDER BY `card_id`");
	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['card_cat']."'");
		echo '<tr>
		<td align="center">'.$row['card_id'].'</td>
		<td align="center">'.$row['card_deckname'].'</td>
		<td align="center">'.$cat['cat_name'].'</td>
		<td align="center">'.$row['card_status'].'</td>
		</tr>';
	}

	echo '</tbody>
	</table>
	</div>';
}


else
{
	$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='$id'");
	echo '<h1>Category: '.$cat['cat_name'].'</h1>
	<div class="box">
	<table width="100%" id="admin-cardspercat" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="40%">Deck Name</th>
		<th scope="col" align="center" width="20%">File Name</th>
		<th scope="col" align="center" width="20%">Status</th>
	</tr></thead>
	<tbody>';

	$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_cat`='$id' ORDER BY `card_id`");
	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		echo '<tr>
		<td align="center">'.$row['card_id'].'</td>
		<td align="center">'.$row['card_deckname'].'</td>
		<td align="center">'.$cat['cat_name'].'</td>
		<td align="center">'.$row['card_status'].'</td>
		</tr>';
	}

	echo '</tbody>
	</table>
	</div>';
}
?>