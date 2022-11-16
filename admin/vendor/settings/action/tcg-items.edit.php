<?php
/**************************************************
 * Action:			Edit TCG Items
 * Description:		Show page for editing TCG items
 */


// Process edit TCG item form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$group = $sanitize->for_db($_POST['group']);
	$title = $sanitize->for_db($_POST['title']);
	$limit = $sanitize->for_db($_POST['limit']);
	$cards = $sanitize->for_db($_POST['cards']);
	$currency = $sanitize->for_db($_POST['currency']);
	$update = $database->query("UPDATE `tcg_collateral` SET `collateral_group`='$group', `collateral_name`='$title', `collateral_limit`='$limit', `collateral_cards`='$cards', `collateral_currency`='$currency' WHERE `collateral_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the TCG item was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The TCG item was updated successfully!";
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
	$row = $database->get_assoc("SELECT * FROM `tcg_collateral` WHERE `collateral_id`='$id'");
	echo '<h1>Edit a TCG Item</h1>
	<p>Use this form to edit an existing TCG item in the database.<br />
	Use the <a href="'.$tcgurl.'admin/settings.php?mod='.$mod.'&action=add">add</a> form to add a new TCG item.</p>

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

	<div class="box" style="width: 80rem;">
	<form method="post" action="'.$tcgurl.'admin/settings.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Group:</b> (slug)</span>
				</div>
				<input type="text" name="group" value="'.$row['collateral_group'].'" class="form-control" />
			</div>
		</div>

		<div class="col">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Name:</b> (title)</span>
				</div>
				<input type="text" name="title" value="'.$row['collateral_name'].'" class="form-control" />
			</div>
		</div>
	</div><!-- .row -->

	<hr>

	<div class="row">
		<div class="col">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Limit per set</b></span>
				</div>
				<input type="text" name="limit" value="'.$row['collateral_limit'].'" class="form-control" />
			</div>
		</div>

		<div class="col">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Cards to reward</b></span>
				</div>
				<input type="text" name="cards" value="'.$row['collateral_cards'].'" class="form-control" />
			</div>
		</div>

		<div class="col">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Currency to reward</b></span>
				</div>
				<input type="text" name="currency" value="'.$row['collateral_currency'].'" class="form-control" />
			</div>
		</div>
	</div><!-- .row -->
	<br />

	<input type="submit" name="update" class="btn btn-success" value="Edit TCG Item" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
	</form>
	</div>';
}
?>