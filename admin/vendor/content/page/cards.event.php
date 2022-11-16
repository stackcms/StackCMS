<?php
/*****************************************************
 * Action:			Event Cards
 * Description:		Show main page of event cards list
 */


// Process mass delete event cards
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_cards_event` WHERE `event_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the event cards were not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The event cards were deleted successfully!";
	}
}


// Show event cards list and form
echo '<h1>Event Cards</h1>
<p>Do you want to <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=add">add an event card</a>?</p>

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

$sql = $database->query("SELECT * FROM `tcg_cards_event` ORDER BY `event_date` DESC");
$num = $database->num_rows("SELECT * FROM `tcg_cards_event`");

if( $num == 0 )
{
	echo "<p>There are currently no event cards added.</p>\n";
}

else
{
	echo '<div class="box">
	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">
	<table width="100%" id="admin-cardsevent" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="30%">Filename</th>
		<th scope="col" align="center" width="10%">Group</th>
		<th scope="col" align="center" width="10%">Released</th>
		<th scope="col" align="center" width="10%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		echo '<tr>
		<td align="center"><input type="checkbox" name="id[]" value="'.$row['event_id'].'" /></td>
		<td align="center">'.$row['event_id'].'</td>
		<td align="center">'.$row['event_title'].' ('.$row['event_filename'].')</td>
		<td align="center">'.$row['event_group'].'</td>
		<td align="center">'.$row['event_date'].'</td>
		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['event_id'].'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this event card"><i class="bi-gear" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['event_id'].'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this event card"><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '</tbody>

	<tfoot>
	<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="5">With selected: <input type="submit" name="mass-delete" value="Delete" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete selected event cards" /></td>
	</tr>
	</tfoot>
	</table>
	</form>
	</div>';
}
?>