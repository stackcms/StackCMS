<?php
/**********************************************************
 * Page:			Member Deck Tasks Main
 * Description:		Show main page of member deck task list
 */


// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `user_decks` WHERE `task_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the member deck tasks were not deleted from the database. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The member deck tasks has been deleted from the database!";
	}
}


// Show deletion form
$select = $database->query("SELECT * FROM `user_decks` ORDER BY `task_card`");

echo '<h1>Member Deck Tasks</h1>
<p>Below is the list of your current member deck tasks. Feel free to edit or delete the items that suits your own TCG setup.<br />
If you want to add a new task, kindly use this <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">form</a>.</p>

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
<table id="admin-memberstasks" class="table table-bordered table-hover">
<thead class="thead-dark">
<tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="35%">Task</th>
	<th scope="col" align="center" width="10%">Card #</th>
	<th scope="col" align="center" width="35%">Proof</th>
	<th scope="col" align="center" width="10%">Action</th>
</tr>
</thead>
<tbody>';

while( $row = mysqli_fetch_assoc( $select ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['task_id'].'" /></td>
	<td align="center">'.$row['task_id'].'</td>
	<td align="center">'.$row['task_name'].'</td>
	<td align="center">'.$row['task_card'].'</td>
	<td align="center">'.$row['task_proof'].'</td>
	<td align="center">
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['task_id'].'\';" class="btn btn-success" title="Edit this task" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['task_id'].'\';" class="btn btn-danger" title="Delete this task" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="6">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected tasks" data-toggle="tooltip" data-placement="bottom" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';
?>