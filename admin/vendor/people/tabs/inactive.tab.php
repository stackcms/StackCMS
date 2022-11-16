<?php
/*********************************************************
 * Tab:				Inactive Members
 * Description:		Show main tab of inactive members list
 */


// Process mass reactivation of inactive members
if( isset( $_POST['mass-reactivate'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$reactivate = $database->query("UPDATE `user_list` SET `usr_status`='Active' WHERE `usr_id`='$id' AND `usr_status`='Inactive'");
	}

	if( !$reactivate )
	{
		$error[] = "Sorry, there was an error and the members were not reactivated. ".mysqli_error($reactivate);
	}

	else
	{
		$success[] = "The members were reactivated successfully.";
	}
}

// Process mass hiatus of inactive members
if( isset( $_POST['mass-hiatus'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$hiatus = $database->query("UPDATE `user_list` SET `usr_status`='Hiatus' WHERE `usr_id`='$id' AND `usr_status`='Inactive'");
	}

	if( !$hiatus )
	{
		$error[] = "Sorry, there was an error and the members were not put into hiatus. ".mysqli_error($reactivate);
	}

	else
	{
		$success[] = "The members were put into hiatus successfully.";
	}
}

// Process mass deletion of inactive members
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `user_list` WHERE `usr_id`='$id'");
	}

	if( !$delete ) 
	{
		$error[] = "Sorry, there was an error and the members were not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The members were successfully deleted.";
	}
}

// Show list and form of inactive members
echo '<h2>Inactive</h2>
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
echo '</center>';

$admin->members('Inactive');
?>