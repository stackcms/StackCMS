<?php
/**************************************************
 * Page:			Upcoming Cards
 * Description:		Process all upcoming card pages
 */


// Process mass release of upcoming decks
if( isset( $_POST['mass-release'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$date = date('Y-m-d');
		$release = $database->query("UPDATE `tcg_cards` SET `card_status`='Active', `card_released`='$date', `card_votes`='0' WHERE `card_id`='$id'");

		// Set activity record
		$row = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_id`='$id'");
		$activity = '<span class="fas fa-paper-plane" aria-hidden="true"></span> <a href="'.$tcgurl.'/members.php?id='.$player.'">'.$player.'</a> released the <a href="'.$tcgurl.'/cards.php?view=released&deck='.$row['card_filename'].'">'.$row['card_deckname'].'</a> deck.';
		$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_slug`,`act_type`,`act_date`) VALUES ('$player','$activity','".$row['card_filename']."','released','$date')");
	}

	if( !$release )
	{
		$error[] = "Sorry, there was an error and the card decks were not released. ".mysqli_error($release)."";
	}

	else
	{
		$success[] = "The card decks were released successfully!";
	}
}

// Process mass deletion of upcoming decks
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_cards` WHERE `card_id`='$id'");

		// Delete activity log
		$sql = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_id`='$id'");
		$activity = $database->query("DELETE FROM `tcg_activities` WHERE `act_type`='upcoming' AND `act_slug`='".$sql['card_filename']."'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the card decks were not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The card decks were deleted successfully!";
	}
}



$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Upcoming' ORDER BY `card_votes` DESC");
$num = mysqli_num_rows($sql);

echo '<h1>Upcoming Deck Administration</h1>
<p>Do you want to <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=add">add an upcoming deck</a>?</p>

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

<div class="box">';

if( $num == 0 )
{
	echo "<p>There are currently no upcoming decks.</p>\n";
}

else {
	echo '<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">
	<table width="100%" id="admin-cardsupcoming" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="45%">Filename</th>
		<th scope="col" align="center" width="10%">Category</th>
		<th scope="col" align="center" width="5%">Votes</th>
		<th scope="col" align="center" width="15%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		$c = $row['card_cat'];
		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='$c'");
		echo '<tr>
		<td align="center"><input type="checkbox" name="id[]" value="'.$row['card_id'].'" /></td>
		<td align="center">'.$row['card_id'].'</td>
		<td align="center">'.$row['card_deckname'].' ('.$row['card_filename'].')</td>
		<td align="center">'.$cat['cat_name'].'</td>
		<td align="center">'.$row['card_votes'].'</td>
		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=release&id='.$row['card_id'].'\';" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Release this deck"><i class="bi-check-lg" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['card_id'].'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this deck"><i class="bi-gear" role="image"></i></button>
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['card_id'].'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this deck"><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '</tbody>

	<tfoot>
	<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="5">With selected: 
			<input type="submit" name="mass-release" value="Release" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Release selected decks" />
			<input type="submit" name="mass-delete" value="Delete" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete selected decks" />
		</td>
	</tr>
	</tfoot>
	</table>
	</form>';
}
echo '</div>';

?>