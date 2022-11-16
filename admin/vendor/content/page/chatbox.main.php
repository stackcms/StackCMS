<?php
/*************************************************
 * Action:			Chat Box
 * Description:		Show main page of members chat
 */


// Process mass deletion of chat box messages
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_chatbox` WHERE `chat_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the chat messages were not deleted from the database. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The chat messages has been deleted from the database!";
	}
}

$sql = $database->query("SELECT * FROM `tcg_chatbox` ORDER BY `chat_date` DESC");
echo '<h1>Chat Box</h1>
<p>Manage your members chat messages here. You can delete inapproriate messages or edit a specific message to filter out the chat box from public view.</p>

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
		echo '<div class="alert alert-danger" role="alert"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<div class="box">
	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'">
	<table width="100%" id="admin-chatbox" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="15%">From</th>
		<th scope="col" align="center" width="45%">Message</th>
		<th scope="col" align="center" width="15%">Date</th>
		<th scope="col" align="center" width="15%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		echo '<tr>
		<td align="center"><input type="checkbox" name="id[]" value="'.$row['chat_id'].'"></td>
		<td align="center">'.$row['chat_id'].'</td>
		<td align="center">'.$row['chat_name'].'</td>
		<td align="center">'.$row['chat_msg'].'</td>
		<td align="center">'.$row['chat_date'].'</td>
		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=edit&id='.$row['chat_id'].'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this entry"><i class="bi-gear" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=delete&id='.$row['chat_id'].'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this entry"><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '</tbody>

	<tfoot>
	<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" data-toggle="tooltip" data-placement="bottom" title="Delete selected entries" /></td>
	</tr>
	</tfoot>
	</table>
	</form>
</div><!-- .box -->';
?>