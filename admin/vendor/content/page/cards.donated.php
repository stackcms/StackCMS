<?php
/****************************************************
 * Action:			Donated Decks
 * Description:		Show page for the donated deck list
 */


// Process mass claim donated decks
if( isset( $_POST['mass-claim'] ) )
{
	$getID = $_POST['id'];
	$maker = $_POST['maker'];

	foreach( $getID as $id )
	{
		$claim = $database->query("UPDATE `tcg_donations` SET `deck_maker`='$maker' WHERE `deck_id`='$id'");
	}

	if( !$claim )
	{
		$error[] = "Sorry, there was an error and the deck was not updated. ".mysqli_error($claim)."";
	}

	else
	{
		$success[] = "You have claimed to make the deck!";
	}
}

// Process mass delete donated decks
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_donations` WHERE `deck_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the donated deck was not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The donated deck was successfully deleted.";
	}
}


// Show donated decks list and form
echo '<h1>Donated Decks</h1>
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
echo '</center>';

$sql = $database->query("SELECT * FROM `tcg_donations` ORDER BY `deck_date` ASC");
$num = mysqli_num_rows($sql);

if( $num == 0 )
{
	echo "<p>There are currently no donated decks.</p>\n";
}

else
{
	echo '<div class="box">
	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">
	<input type="hidden" name="maker" value="'.$player.'" />
	<table width="100%" id="admin-cardsdonation" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="25%">Filename</th>
		<th scope="col" align="center" width="8%">Maker</th>
		<th scope="col" align="center" width="10%">Category</th>
		<th scope="col" align="center" width="17%">Set/Series</th>
		<th scope="col" align="center" width="10%">Date</th>
		<th scope="col" align="center" width="18%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['deck_cat']."'");
		$set = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['deck_set']."'");
		echo '<tr>
		<td align="center"><input type="checkbox" name="id[]" value="'.$row['deck_id'].'" /></td>
		<td align="center">'.$row['deck_filename'].'</td>
		<td align="center">'.$row['deck_maker'].'</td>
		<td align="center">'.$cat['cat_name'].'</td>
		<td align="center">'.$set['set_name'].'</td>
		<td align="center">'.$row['deck_date'].'</td>
		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=claim&id='.$row['deck_id'].'\';" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Claim this donation"><i class="bi-person-check" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['deck_id'].'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this donation"><i class="bi-gear" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$row['deck_url'].'\';" target="_blank" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download this donation"><i class="bi-download" role="image"></i></button>
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['deck_id'].'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this donation"><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '</tbody>

	<tfoot>
	<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="6">With selected: 
			<input type="submit" name="mass-claim" value="Claim" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Claim selected donations" />
			<input type="submit" name="mass-delete" value="Delete" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete selected donations" />
		</td>
	</tr>
	</tfoot>
	</table>
	</form>
	</div>';
}
?>