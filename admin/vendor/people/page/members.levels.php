<?php
/*****************************************************
 * Page:			User Levels Main
 * Description:		Show main page of user levels list
 */


// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_levels` WHERE `lvl_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the levels were not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The levels were deleted successfully!";
	}
}


// Show user levels list and form
echo '<h1>User Levels</h1>
<p>Below is the list of current levels for your TCG. Feel free to edit or delete the items that suits your own TCG setup.<br />
If you need to add a new user level, <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">use this form</a>.</p>

<div class="alert alert-warning" role="alert"><b>Notice:</b> Please take note that the ID will be the numerical value of your user levels. So make sure to just edit the pre-existing levels below before adding a new one.</div>

<center>';
if( isset( $error ) )
{
	foreach( $error as $msg )
	{
		echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
	}
}

if( isset($success) )
{
	foreach( $success as $msg )
	{
		echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<div class="box">
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">
<table id="admin-memberslevels" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="10%">ID</th>
	<th scope="col" align="center" width="30%">Level Name</th>
	<th scope="col" align="center" width="15%"># of Cards</th>
	<th scope="col" align="center" width="20%">Difference</th>
	<th scope="col" align="center" width="20%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_levels` ORDER BY `lvl_id` ASC");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['lvl_id'].'" /></td>
	<td align="center">'.$row['lvl_id'].'</td>
	<td>'.$row['lvl_name'].'</td>
	<td align="center">'.$row['lvl_cards'].'</td>
	<td align="center">'.$row['lvl_interval'].'</td>
	<td align="center">
		<button type="button" onclick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['lvl_id'].'\';" class="btn btn-success" title="Edit this level" data-toggle="tooltip" data-placement="bottom" /><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['lvl_id'].'\';" class="btn btn-danger" title="Delete this level" data-toggle="tooltip" data-placement="bottom" /><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected levels" data-toggle="tooltip" data-placement="bottom" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';