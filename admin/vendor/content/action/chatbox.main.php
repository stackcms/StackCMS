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
		echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
	}
}

if( isset( $success ) )
{
	foreach( $success as $msg )
	{
		echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'">
<table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered table-striped">
<thead><tr>
	<td width="5%"></td>
	<td width="5%">ID</td>
	<td width="15%">From</td>
	<td width="45%">Message</td>
	<td width="15%">Date</td>
	<td width="15%">Action</td>
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
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=edit&id='.$row['chat_id'].'\';" class="btn-success">Edit</button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=delete&id='.$row['chat_id'].'\';" class="btn-cancel">Delete</button>
	</td>
	</tr>';
}

echo '<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn-cancel" value="Delete" /></td>
<tr>
</tbody>
</table>
</form>';
?>