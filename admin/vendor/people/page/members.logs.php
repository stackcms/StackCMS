<?php
/******************************************************
 * Page:			User Logs
 * Description:		Show page of a specific user's logs
 */


// Check if user is accessing the page directly
$name = isset($_GET['name']) ? $_GET['name'] : null;
if( empty( $name ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

else {
	// Process mass deletion form
	if( isset( $_POST['mass-delete'] ) )
	{
		$getID = $_POST['id'];
		foreach( $getID as $id )
		{
			$delete = $database->query("DELETE FROM `user_logs` WHERE `log_id`='$id'");
		}

		if( !$delete )
		{
			$error[] = "Sorry, there was an error and the logs were not deleted from the database. ".mysqli_error($delete);
		}

		else
		{
			$success[] = "The user logs were deleted successfully from the database.";
		}
	}

	// Show user logs list and form
	$logs = $settings->getValue( 'item_per_page' );
	if( !isset($_GET['p']) )
	{
		$p = 1;
	}

	else
	{
		$p = (int)$_GET['p'];
	}

	$from = (($p * $logs) - $logs);
	$log = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$name' ORDER BY `log_date` DESC");

	echo '<h1>User Logs <span class="fas fa-angle-right" aria-hidden="true"></span> '.$name.'</h1>
	<p>Below shows the detailed log of '.$name.'\'s activities.</p>

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

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'">
	<table id="admin-memberslogs" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="10%">Date</th>
		<th scope="col" align="center" width="30%">Log Title</th>
		<th scope="col" align="center" width="30%">Rewards</th>
		<th scope="col" align="center" width="20%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $log ) )
	{
		echo '<tr>
		<td align="center"><input type="checkbox" name="id[]" value="'.$row['log_id'].'" /></td>
		<td align="center">'.$row['log_id'].'</td>
		<td align="center">'.date("Y/m/d", strtotime($row['log_date'])).'</td>
		<td align="center">'.$row['log_title'];
			if( empty($row['log_subtitle']) ) {}
			else
			{
				echo ' '.$row['log_subtitle'];
			}
		echo '</td>

		<td align="center">';
			if( mb_strlen($row['log_rewards']) >= 90 )
			{
				$row['log_rewards'] = substr($row['log_rewards'], 0, 90);
				$row['log_rewards'] = $row['log_rewards'] . "...";
				echo $row['log_rewards'];
			}

			else
			{
				echo $row['log_rewards'];
			}
		echo '</td>

		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'&action=edit&id='.$row['log_id'].'\';" class="btn btn-success" title="Edit this log" data-toggle="tooltip" data-placement="bottom" /><i class="bi-gear" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'&action=delete&id='.$row['log_id'].'\';" class="btn btn-danger" title="Delete this log" data-toggle="tooltip" data-placement="bottom" /><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '<tbody>

	<tfoot>
	<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected logs" data-toggle="tooltip" data-placement="bottom" /></td>
	</tr>
	</tfoot>
	</table>
	</form>';
}
?>