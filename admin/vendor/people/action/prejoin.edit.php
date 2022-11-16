<?php
/*************************************************************
 * Action:			Edit Prejoin Record
 * Description:		Show page for editing user prejoin records
 */


// Process edit user prejoin record form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$cards = $sanitize->for_db($_POST['cards']);
	$items = $sanitize->for_db($_POST['items']);
	$worth = $sanitize->for_db($_POST['worth']);
	$money = $sanitize->for_db($_POST['money']);
	$update = $database->query("UPDATE `prejoin_record` SET `usr_cards`='$cards', `usr_collaterals`='$items', `usr_cardworth`='$worth', `usr_currency`='$money' WHERE `usr_name`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the user prejoin record was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The user prejoin record was updated successfully!";
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
	$row = $database->get_assoc("SELECT * FROM `prejoin_record` WHERE `usr_name`='$id'");
	echo '<h1>Edit a User Prejoin Record</h1>
	<p>Use this form to edit an existing user prejoin record in the database.<br />
	Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add a new user prejoin record.</p>

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
	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col-4"><b>Cards Donated:</b></div>
		<div class="col"><input type="text" name="cards" value="'.$row['usr_cards'].'" class="form-control" /></div>
	</div><br />
	
	<div class="row">
		<div class="col-4"><b>Items Donated:</b></div>
		<div class="col"><input type="text" name="items" value="'.$row['usr_collaterals'].'" class="form-control" /></div>
	</div><br />
	
	<div class="row">
		<div class="col-4"><b>Card Worth:</b></div>
		<div class="col"><input type="text" name="worth" value="'.$row['usr_cardworth'].'" class="form-control" /></div>
	</div><br />
	
	<div class="row">
		<div class="col-4"><b>Currency:</b></div>
		<div class="col"><input type="text" name="money" value="'.$row['usr_currency'].'" class="form-control" /></div>
	</div><br />

	<input type="submit" name="update" class="btn btn-success" value="Edit Record" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
	</form>
	</div>';
}
?>