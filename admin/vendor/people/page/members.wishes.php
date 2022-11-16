<?php
/*****************************************************
 * Page:			User Wishes
 * Description:		Show main page of user wishes list
 */


date_default_timezone_set( $settings->getValue('tcg_timezone') );
$timestamp = date('Y-m-d');

// Process mass grant form
if( isset( $_POST['mass-grant'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$grant = $database->query("UPDATE `user_wishes` SET `wish_date`='$timestamp', `wish_status`='Granted' WHERE `wish_id`='$id'");
	}

	if( !$grant )
	{
		$error[] = "Sorry, there was an error and the wishes were not granted. ".mysqli_error($grant);
	}

	else
	{
		$success[] = "The wishes has been granted successfully!";
	}
}

// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `user_wishes` WHERE `wish_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the wishes were not delete from the database. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The wishes has been deleted successfully from the database!";
	}
}


// Show user wishes list and form
echo '<h1>User Wishes</h1>
<p>&raquo; Do you want to <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add a wish</a>?</p>

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
<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" href="#pending" data-toggle="tab" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#granted" data-toggle="tab" role="tab" aria-controls="granted" aria-selected="false">Granted</a>
	</li>
</ul>

<div class="tab-content" id="myTabContent">
	<div id="pending" class="tab-pane fade show active" role="tabpanel" aria-labelledby="pending-tab">
		<h2>Pending</h2>';
		$sql = $database->query("SELECT * FROM `user_wishes` WHERE `wish_status`='Pending' ORDER BY `wish_id` ASC");
		$num = mysqli_num_rows($sql);

		if( $num == 0 )
		{
			echo '<p align="center">There are currently no wishes under this status.</p>';
		}

		else
		{
			echo '<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">
			<table id="admin-wishespending" class="table table-bordered table-hover">
			<thead class="thead-dark"><tr>
				<th scope="col" align="center" width="5%"></th>
				<th scope="col" align="center" width="5%">ID</th>
				<th scope="col" align="center" width="15%">Player</th>
				<th scope="col" align="center" width="45%">Wish</th>
				<th scope="col" align="center" width="10%">Date</th>
				<th scope="col" align="center" width="20%">Action</th>
			</tr></thead>
			<tbody>';

			while( $row = mysqli_fetch_assoc( $sql ) )
			{
				echo '<tr>
				<td align="center"><input type="checkbox" name="id[]" value="'.$row['wish_id'].'" /></td>
				<td align="center">'.$row['wish_id'].'</td>
				<td align="center">'.$row['wish_name'].'</td>
				<td align="center">'.$row['wish_text'].'</td>
				<td align="center">'.$row['wish_date'].'</td>
				<td align="center">
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=approve&id='.$row['wish_id'].'\';" class="btn btn-default" title="Approve this wish" data-toggle="tooltip" data-placement="bottom"><i class="bi-check2" role="image"></i></button> 
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['wish_id'].'\';" class="btn btn-success" title="Edit this wish" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button> 
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['wish_id'].'\';" class="btn btn-danger" title="Delete this wish" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
				</td>
				</tr>';
			}

			echo '</tbody>

			<tfoot>
			<tr>
				<td align="center"><span class="arrow-right">↳</span></td>
				<td colspan="5">With selected: 
					<input type="submit" name="mass-grant" class="btn btn-success" value="Grant" title="Grant selected wishes" data-toggle="tooltip" data-placement="bottom" />
					<input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected wishes" data-toggle="tooltip" data-placement="bottom" />
				</td>
			</tr>
			</tfoot>
			</table>
			</form>';
		}
	echo '</div>

	<div id="granted" class="tab-pane fade" role="tabpanel" aria-labelledby="granted-tab">
		<h2>Granted</h2>';
		$sql2 = $database->query("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' ORDER BY `wish_date` DESC");
		$num2 = mysqli_num_rows($sql2);

		if( $num2 == 0 )
		{
			echo '<p align="center">There are currently no wishes under this status.</p>';
		}

		else
		{
			echo '<form method="post" action="'.$$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">
			<table id="admin-wishesgranted" class="table table-bordered table-hover">
			<thead class="thead-dark"><tr>
				<th scope="col" align="center" width="5%"></th>
				<th scope="col" align="center" width="5%">ID</th>
				<th scope="col" align="center" width="15%">Player</th>
				<th scope="col" align="center" width="45%">Wish</th>
				<th scope="col" align="center" width="10%">Date</th>
				<th scope="col" align="center" width="10%">Action</th>
			</tr></thead>
			</tbody>';

			while( $row2 = mysqli_fetch_assoc( $sql2 ) )
			{
				echo '<tr>
				<td align="center"><input type="checkbox" name="id[]" value="'.$row2['wish_id'].'" /></td>
				<td align="center">'.$row2['wish_id'].'</td>
				<td align="center">'.$row2['wish_name'].'</td>
				<td align="center">'.$row2['wish_text'].'</td>
				<td align="center">'.$row2['wish_date'].'</td>
				<td align="center">
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row2['wish_id'].'\';" class="btn btn-success" title="Edit this wish" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button> 
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row2['wish_id'].'\';" class="btn btn-danger" title="Delete this wish" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
				</td>
				</tr>';
			}

			echo '</tbody>

			<tfoot>
			<tr>
				<td align="center"><span class="arrow-right">↳</span></td>
				<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected wishes" data-toggle="tooltip" data-placement="bottom" /></td>
			</tr>
			</tfoot>
			</table>
			</form>';
		}
	echo '</div>
</div>';
?>