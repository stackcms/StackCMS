<?php
/********************************************************
 * Action:			Edit User Trade Logs
 * Description:		Show page for editing user trade logs
 */


// Process edit form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$trader = $sanitize->for_db($_POST['trader']);
	$out = $sanitize->for_db($_POST['outgoing']);
	$inc = $sanitize->for_db($_POST['incoming']);
	$date = $_POST['date'];

	$update = $database->query("UPDATE `user_trades` SET `trd_trader`='$trader', `trd_out`='$out', `trd_inc`='$inc', `trd_date`='$date' WHERE `trd_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the trade log was not updated from the database. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The user trade log has been successfully updated from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) || empty( $name ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit form
else {
	$row = $database->get_assoc("SELECT * FROM `user_trades` WHERE `trd_id`='$id'");

	echo '<h1>Edit Trade Logs</h1>
	<p>Edit '.$row['trd_name'].'\'s trade logs.</p>

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
	
	<div class="box" style="width: 600px;">
	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col-4"><b>Date:</b></div>
		<div class="col"><input type="date" name="date" value="'.$row['trd_date'].'" class="form-control"></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Traded With:</b></div>
		<div class="col"><input type="text" name="trader" value="'.$row['trd_trader'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Outgoing:</b></div>
		<div class="col"><textarea name="outgoing" class="form-control" rows="3">'.$row['trd_out'].'</textarea></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Incoming:</b></div>
		<div class="col"><textarea name="incoming" class="form-control" rows="3">'.$row['trd_inc'].'</textarea></div>
	</div><br />
	
	<input type="submit" name="update" class="btn btn-success" value="Edit Trade Log" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</form>
	</div>';
}
?>