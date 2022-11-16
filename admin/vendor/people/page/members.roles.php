<?php
/************************************************************
 * Page:			User Roles
 * Description:		Show main page of user roles setting list
 */


// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `user_role` WHERE `role_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the user roles were not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The user roles were deleted successfully!";
	}
}


// Show user role deletion form
echo '<h1>User Roles</h1>
<p>Below is the list of current user roles for your TCG. Feel free to edit or delete the roles that suits your own TCG setup.<br />
If you need to add a new user role, <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">use this form</a>.</p>

<div class="alert alert-warning" role="alert"><b>Notice:</b> Please take note that the ID will be the numerical value of your user roles. So make sure to just edit the pre-existing roles below before adding a new one.</div>

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
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">
<table id="admin-membersroles" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="10%">ID</th>
	<th scope="col" align="center" width="65%">Role Title</th>
	<th scope="col" align="center" width="20%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `user_role` ORDER BY `role_id` ASC");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['role_id'].'" /></td>
	<td align="center">'.$row['role_id'].'</td>
	<td>'.$row['role_title'].'</td>
	<td align="center">
	<button type="button" onclick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['role_id'].'\';" class="btn btn-success" title="Edit this role" data-toggle="tooltip" data-placement="bottom" /><i class="bi-gear" role="image"></i></button> 
	<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['role_id'].'\';" class="btn btn-danger" title="Delete this role" data-toggle="tooltip" data-placement="bottom" /><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected roles" data-toggle="tooltip" data-placement="bottom" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';
?>