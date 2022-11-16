<?php
/***************************************************************
 * Action:			Add User Prejoin Record
 * Description:		Show page for adding new user prejoin record
 */


// Process addition form
if( isset( $_POST['add'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$cards = $sanitize->for_db($_POST['cards']);
	$items = $sanitize->for_db($_POST['items']);
	$worth = $sanitize->for_db($_POST['worth']);
	$money = $sanitize->for_db($_POST['money']);
	$insert = $database->query("INSERT INTO `prejoin_record` (`usr_name`,`usr_cards`,`usr_collaterals`,`usr_cardworth`,`usr_currency`) VALUES ('$name','$cards','$items','$worth','$money')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the user prejoin record was not added. ".mysqli_error($insert);
	}

	else
	{
		$success[] = "The new user prejoin record was successfully added to the database!";
	}
}

// Show add a user role form
echo '<h1>Add a User Prejoin Record</h1>
<p>Use this form to add a new user prejoin record to the database.<br />
Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for an existing user prejoin record.</p>

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
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'">
<div class="row">
	<div class="col-4"><b>Player Name:</b></div>
	<div class="col"><input type="text" name="name" placeholder="e.g. Aki" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Cards Donated:</b></div>
	<div class="col"><input type="text" name="cards" placeholder="e.g. 10" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Items Donated:</b></div>
	<div class="col"><input type="text" name="items" placeholder="e.g. 5" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Card Worth:</b></div>
	<div class="col"><input type="text" name="worth" placeholder="e.g. 15" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Currency:</b></div>
	<div class="col"><input type="text" name="name" placeholder="e.g. 0 | 30" class="form-control" /></div>
</div><br />

<input type="submit" name="add" class="btn btn-success" value="Add Record" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>