<?php
/*************************************************
 * Page:			User Trades
 * Description:		Show page of user's trade list
 */


// Check if user is accessing the page directly
$name = isset($_GET['name']) ? $_GET['name'] : null;
if( empty( $name ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

else
{
	// Process mass deletion form
	if( isset( $_POST['mass-delete'] ) )
	{
		$getID = $_POST['id'];
		foreach( $getID as $id )
		{
			$delete = $database->query("DELETE FROM `user_trades` WHERE `trd_id`='$id'");
		}

		if ( !$delete )
		{
			$error[] = "Sorry, there was an error and the trade logs were not deleted from the database. ".mysqli_error($delete);
		}

		else
		{
			$success[] = "The trade logs were deleted successfully from the database.";
		}
	}

	// Show user trades list
	$trades = $settings->getValue( 'item_per_page' );
	if( !isset($_GET['p']) )
	{
		$p = 1;
	}

	else
	{
		$p = (int)$_GET['p'];
	}

	$from = (($p * $trades) - $trades);
	$log = $database->query("SELECT * FROM `user_trades` WHERE `trd_name`='$name' ORDER BY `trd_date` DESC");

	echo '<h1>User Trade Logs</h1>
	<p>Below shows the detailed log of the user\'s activities.</p>';

	$sql = $database->get_assoc("SELECT * FROM `user_trades_rec` WHERE `trd_name`='$name'");
	echo '<p>'.$name.' has turned in a total of <b>'.$sql['turnins'].'</b> trade cards, has redeemed a total of <b>'.$sql['redeemed'].'</b> points and currently has <b>'.$sql['points'].'</b> unredeemed points.</p>

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
	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'">
	<table id="admin-memberstrades" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="10%">Date</th>
		<th scope="col" align="center" width="30%">Log</th>
		<th scope="col" align="center" width="30%">Trader</th>
		<th scope="col" align="center" width="20%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $log ) )
	{
		$tradelog = "Traded ".$row['trd_out']." for ".$row['trd_inc'];
		if( mb_strlen($tradelog) >= 60 )
		{
			$tradelog = substr($tradelog, 0, 60);
			$tradelog = $tradelog . "...";
		}
		echo '<tr>
		<td align="center"><input type="checkbox" name="id[]" value="'.$row['trd_id'].'" /></td>
		<td align="center">'.$row['trd_id'].'</td>
		<td align="center">'.date("Y/m/d", strtotime($row['trd_date'])).'</td>
		<td align="center">'.$tradelog.'</td>
		<td align="center">With '.$row['trd_trader'].'</td>
		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'&action=edit&id='.$row['trd_id'].'\';" class="btn btn-success" title="Edit this trade log" data-toggle="tooltip" data-placement="bottom" /><i class="bi-gear" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'&action=delete&id='.$row['trd_id'].'\';" class="btn btn-danger" title="Delete this trade log" data-toggle="tooltip" data-placement="bottom" /><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '</tbody>

	<tfoot>
	<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected trade logs" data-toggle="tooltip" data-placement="bottom" /></td>
	</tr>
	</tfoot>
	</table>
	</form>
	</div>';
}
?>