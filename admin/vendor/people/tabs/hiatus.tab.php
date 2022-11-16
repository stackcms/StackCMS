<?php
/*******************************************************
 * Tab:				Hiatus Members
 * Description:		Show main tab of hiatus members list
 */


// Process mass reactivation form of hiatus members
if( isset( $_POST['mass-reactivate'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$reactivate = $database->query("UPDATE `user_list` SET `usr_status`='Active' WHERE `usr_id`='$id' AND `usr_status`='Hiatus'");
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

// Process mass inactivation form of hiatus members
if( isset( $_POST['mass-inactivate'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$inactivate = $database->query("UPDATE `user_list` SET `usr_status`='Inactive' WHERE `usr_id`='$id' AND `usr_status`='Hiatus'");
	}

	if( !$inactivate ) 
	{
		$error[] = "Sorry, there was an error and the members were not inactivated. ".mysqli_error($inactivate);
	}

	else
	{
		$success[] = "The members were inactivated successfully.";
	}
}

// Process mass deletion of hiatus members
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

// Show list and form of hiatus members
echo '<h2>Hiatus</h2>
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

$admin->members('Hiatus');
?>