<?php
/*******************************************************************
 * Module:			TCG Items
 * Description:		Show complete list of available TCG items/groups
 */


// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_collateral` WHERE `collateral_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the TCG items were not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The TCG items were deleted successfully!";
	}
}


// Show TCG items list and deletion form
echo '<h1>TCG Items</h1>
<p>If you want to add another TCG item that is currently not on the list below, you can <a href="'.$tcgurl.'admin/settings.php?mod='.$mod.'&action=add">add them here</a>.</p>

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
<form method="post" action="'.$tcgurl.'admin/settings.php?mod='.$mod.'">
<table id="admin-tcgitems" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="40%">Item/Collateral</th>
	<th scope="col" align="center" width="10%">Limit Per Set</th>
	<th scope="col" align="center" width="10%">Card Reward</th>
	<th scope="col" align="center" width="15%">Currency Reward</th>
	<th scope="col" align="center" width="20%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_collateral` ORDER BY `collateral_id` ASC");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['collateral_id'].'" /></td>
	<td align="center">'.$row['collateral_id'].'</td>
	<td>'.$row['collateral_name'].'</td>
	<td align="center">'.$row['collateral_limit'].'</td>
	<td align="center">'.$row['collateral_cards'].'</td>
	<td align="center">'.$row['collateral_currency'].'</td>
	<td align="center">
	<button type="button" onclick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=edit&id='.$row['collateral_id'].'\';" class="btn btn-success" title="Edit this item" data-toggle="tooltip" data-placement="bottom" /><i class="bi-gear" role="image"></i></button> 
	<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=delete&id='.$row['collateral_id'].'\';" class="btn btn-danger" title="Delete this item" data-toggle="tooltip" data-placement="bottom" /><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="6">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected items" data-toggle="tooltip" data-placement="bottom" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';

?>