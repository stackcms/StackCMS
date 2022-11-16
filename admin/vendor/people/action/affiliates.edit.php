<?php
/*****************************************************
 * Action:			Edit Affiliates
 * Description:		Show page for editing an affiliate
 */


// Process editing affiliates form
if( isset( $_POST['edit'] ) )
{
	$id = $_POST['id'];
	$name = $sanitize->for_db($_POST['owner']);
	$email = $sanitize->for_db($_POST['email']);
	$tcg = $sanitize->for_db($_POST['subject']);
	$url = $sanitize->for_db($_POST['url']);
	$status = $sanitize->for_db($_POST['status']);

	$update = $database->query("UPDATE `tcg_affiliates` SET `aff_owner`='$name', `aff_email`='$email', `aff_subject`='$tcg', `aff_url`='$url', `aff_status`='$status' WHERE `aff_id`='$id'");

	// Process form if queries are correct
	if( !$update )
	{
		$error[] = "Sorry, there was an error and the affiliate was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The affiliate was successfully updated!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit form
else
{
	$row = $database->get_assoc("SELECT * FROM `tcg_affiliates` WHERE `aff_id`='$id'");
	echo '<h1>Edit an Affiliate</h1>
	<p>Use this form to edit an affiliate in the database.<br />
	Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&action=add">add</a> form to add new affiliates.</p>

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
	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col-4"><b>TCG Owner:</b></div>
		<div class="col"><input type="text" name="owner" value="'.$row['aff_owner'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>TCG Name:</b></div>
		<div class="col"><input type="text" name="subject" value="'.$row['aff_subject'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>TCG Email:</b></div>
		<div class="col"><input type="text" name="email" value="'.$row['aff_email'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>TCG Website:</b></div>
		<div class="col"><input type="text" name="url" value="'.$row['aff_url'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Status:</b></div>
		<div class="col">
			<select name="status" class="form-control">
				<option value="'.$row['aff_status'].'">Current: '.$row['aff_status'].'</option>
				<option>----- Select a status -----</option>
				<option value="Pending">Pending</option>
				<option value="Active">Active</option>
				<option value="Hiatus">Hiatus</option>
			</select>
		</div>
	</div><br />

	<input type="submit" name="edit" class="btn btn-success" value="Edit Affiliate" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</form>
	</div>';
}
?>