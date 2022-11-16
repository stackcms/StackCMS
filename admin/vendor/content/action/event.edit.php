<?php
/****************************************************
 * Action:			Edit Event Cards
 * Description:		Show page for editing event cards
 */


// Process edit an event card form
if( isset( $_POST['update'] ) )
{
	$check->Value();
	$id = $sanitize->for_db($_POST['id']);
	$filename = $sanitize->for_db($_POST['filename']);
	$title = $sanitize->for_db($_POST['title']);
	$group = $sanitize->for_db($_POST['group']);
	$released = $_POST['date'];

	$update = $database->query("UPDATE `tcg_cards_event` SET `event_title`='$title', `event_filename`='$filename', `event_group`='$group', `event_date`='$released' WHERE `event_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the event card was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The event card has been updated successfully!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit an event card form
else
{
	$row = $database->get_assoc("SELECT * FROM `tcg_cards_event` WHERE `event_id`='$id'");
	echo '<h1>Edit an Event Card</h1>
	<p>Use this form to edit an event card in the database.<br />
	Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add a new event card.</p>

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

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="col" style="width: 600px;">
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Title:</b></span>
			</div>
			<input type="text" name="title" value="'.$row['event_title'].'" class="form-control" />
		</div>

		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>File Name:</b></span>
			</div>
			<input type="text" name="filename" value="'.$row['event_filename'].'" class="form-control" />
		</div>

		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Group:</b></span>
			</div>
			<select name="group" class="form-control" />
				<option value="'.$row['event_group'].'">Current: '.$row['event_group'].'</option>
				<option value="Events">Events</option>
				<option value="Holidays">Holidays</option>
				<option value="Layouts">Layouts</option>
				<option value="Milestones">Milestones</option>
				<option value="Monthly">Monthly</option>
				<option value="Seasons">Seasons</option>
			</select>
		</div>

		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Release Date:</b></span>
			</div>
			<input type="date" name="date" value="'.$row['event_date'].'" class="form-control" />
		</div>

		<input type="submit" name="update" class="btn btn-success" value="Edit event card" /> 
		<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</div>
	</form>';
}
?>