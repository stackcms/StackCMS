<?php
/******************************************************
 * Action:			Add User Roles
 * Description:		Show page for adding new user roles
 */


// Process addition form
if( isset( $_POST['add'] ) )
{
	$title = $sanitize->for_db($_POST['title']);
	$insert = $database->query("INSERT INTO `user_role` (`role_title`) VALUES ('$title')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the user role was not added. ".mysqli_error($insert);
	}

	else
	{
		$success[] = "The new user role was successfully added to the database!";
	}
}

// Show add a user role form
echo '<h1>Add a User Role</h1>
<p>Use this form to add a new user role to the database.<br />
Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for an existing user role.</p>

<div class="alert alert-warning" role="alert" style="width: 700px;"><b>Notice:</b> Please take note that the ID will be the numerical value of your user roles.</div>

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
	<div class="col-4"><b>Role Title:</b></div>
	<div class="col"><input type="text" name="name" placeholder="e.g. Deck Maker" class="form-control" /></div>
</div><br />

<input type="submit" name="add" class="btn btn-success" value="Add Role" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>