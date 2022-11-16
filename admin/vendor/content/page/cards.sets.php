<?php
/*****************************************************
 * Page:			Sets/Series
 * Description:		Show main page of sets/series list
 */


// Process mass deletion of sets/series
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_cards_set` WHERE `set_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the sets/series were not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The setes were deleted successfully!";
	}
}


// Show set/series list and form
echo '<h1>Card Sets/Series</h1>
<p>Below is the list of current sets or series for your card decks. Feel free to edit or delete the items that suits your own TCG setup.<br />
If you want to add a new deck set/series, <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=add">use this form</a>.</p>

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

<div class="box">
<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">
<table width="100%" id="admin-cardsset" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="75%">Set/Series</th>
	<th scope="col" align="center" width="15%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_cards_set` ORDER BY `set_id` ASC");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['set_id'].'" /></td>
	<td align="center">'.$row['set_id'].'</td>
	<td>'.$row['set_name'].'</td>
	<td align="center">
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['set_id'].'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this set" /><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['set_id'].'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this set" /><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="3">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" data-toggle="tooltip" data-placement="bottom" title="Delete selected sets" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';
?>