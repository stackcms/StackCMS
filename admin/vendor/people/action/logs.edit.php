<?php
/**************************************************
 * Action:			Edit User Logs
 * Description:		Show page for editing user logs
 */


// Process edit logs form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$type = $sanitize->for_db($_POST['type']);
	$title = $sanitize->for_db($_POST['title']);
	$subtitle = $sanitize->for_db($_POST['subtitle']);
	$reward = $sanitize->for_db($_POST['rewards']);
	$date = $_POST['date'];

	$update = $database->query("UPDATE `user_logs` SET `log_type`='$type', `log_title`='$title', `log_subtitle`='$subtitle', `log_rewards`='$reward', `log_date`='$date' WHERE `log_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the log was not updated from the database. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The user log has been successfully updated from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) || empty( $name ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit logs form
else
{
	$row = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_id`='$id'");

	echo '<h1>Edit a User Log</h1>
	<p>Edit this set of log from '.$row['log_name'].'\'s records.</p>

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
		<div class="col"><input type="date" name="date" value="'.$row['log_date'].'" class="form-control"></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Log Type:</b></div>
		<div class="col">
			<select name="type" class="form-control">
				<option value="'.$row['log_type'].'">Current: '.$row['log_type'].'</option>
				<option>----- Select log type -----</option>
				<option value="Rewards">Rewards</option>
				<option value="Pulls">Pulls</option>
				<option value="Releases">Releases</option>
				<option value="Exchanges">Exchanges</option>
				<option value="Purchases">Purchases</option>
				<option value="Service">Service</option>
				<option value="Monthly">Monthly</option>
				<option value="Weekly">Weekly</option>
				<option value="Set A">Set A</option>
				<option value="Set B">Set B</option>
				<option value="Special">Special</option>
			</select>
		</div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Title:</b></div>
		<div class="col"><input type="text" name="title" value="'.$row['log_title'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Subtitle:</b></div>
		<div class="col"><input type="text" name="subtitle" value="'.$row['log_subtitle'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Rewards:</b></div>
		<div class="col"><textarea name="rewards" class="form-control" rows="3">'.$row['log_rewards'].'</textarea></div>
	</div><br />

	<input type="submit" name="update" class="btn btn-success" value="Edit User Log" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</form>
	</div>';
}
?>