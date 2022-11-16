<?php
/*****************************************************
 * Action:			Add TCG Items
 * Description:		Show page for adding new TCG items
 */


// Process addition form
if( isset( $_POST['add'] ) )
{
	$group = $sanitize->for_db($_POST['group']);
	$title = $sanitize->for_db($_POST['title']);
	$limit = $sanitize->for_db($_POST['limit']);
	$cards = $sanitize->for_db($_POST['cards']);
	$currency = $sanitize->for_db($_POST['currency']);
	$insert = $database->query("INSERT INTO `tcg_collateral` (`collateral_group`,`collateral_name`,`collateral_limit`,`collateral_cards`,`collateral_currency`) VALUES ('$group','$title','$limit','$cards','$currency')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the TCG item was not added. ".mysqli_error($insert);
	}

	else
	{
		$success[] = "The new TCG item was successfully added to the database!";
	}
}

// Show add a TCG item form
echo '<h1>Add a TCG Item</h1>
<p>Use this form to add a new TCG item to the database.<br />
Use the <a href="'.$tcgurl.'admin/settings.php?mod='.$mod.'">edit</a> form to update information for an existing TCG item.</p>

<div class="alert alert-warning" role="alert" style="width: 700px;"><b>Notice:</b> Please take note that the ID will be the numerical value of your TCG item.</div>

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
<form method="post" action="'.$tcgurl.'admin/prejoin.php?mod='.$mod.'&action='.$act.'">
<div class="row">
	<div class="col">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Group:</b> (slug)</span>
			</div>
			<input type="text" name="group" placeholder="e.g. badges" class="form-control" />
		</div>
	</div>

	<div class="col">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Name:</b> (title)</span>
			</div>
			<input type="text" name="title" placeholder="e.g. Level Badges" class="form-control" />
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
			<input type="text" name="limit" placeholder="e.g. 3" class="form-control" />
		</div>
	</div>

	<div class="col">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Cards to reward</b></span>
			</div>
			<input type="text" name="cards" placeholder="e.g. 1" class="form-control" />
		</div>
	</div>

	<div class="col">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Currency to reward</b></span>
			</div>
			<input type="text" name="currency" placeholder="e.g. 0 | 2" class="form-control" />
		</div>
	</div>
</div><!-- .row -->
<br />

<input type="submit" name="add" class="btn btn-success" value="Add TCG Item" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>