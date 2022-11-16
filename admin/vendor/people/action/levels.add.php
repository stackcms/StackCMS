<?php
/*******************************************************
 * Action:			Add User Levels
 * Description:		Show page for adding new user levels
 */


// Process add a level form
if( isset( $_POST['add'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$card = $sanitize->for_db($_POST['cards']);
	$int = $sanitize->for_db($_POST['interval']);

	$insert = $database->query("INSERT INTO `tcg_levels` (`lvl_name`,`lvl_cards`,`lvl_interval`) VALUES ('$name','$card','$int')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the level set was not added. ".mysqli_error($insert);
	}

	else
	{
		$success[] = "The new level set was successfully added to the database!";
	}
}


// Show add a user level form
echo '<h1>Add a User Level</h1>
<p>Use this form to add a new level set to the database.<br />
Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for an existing level set.</p>

<div class="alert alert-warning" role="alert" style="width: 700px;"><b>Notice:</b> Please take note that the ID will be the numerical value of your user levels.</div>

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
	<div class="col-4"><b>Level Name:</b></div>
	<div class="col"><input type="text" name="name" placeholder="e.g. Seedling" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Card Worth/Count:</b></div>
	<div class="col"><input type="text" name="cards" placeholder="amount of cards to gain" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Card Difference:</b></div>
	<div class="col"><input type="text" name="interval" placeholder="card interval per level" class="form-control" /></div>
</div><br />

<input type="submit" name="add" class="btn btn-success" value="Add Level" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>