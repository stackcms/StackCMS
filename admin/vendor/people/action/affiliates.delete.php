<?php
/******************************************************
 * Action:			Delete Affiliates
 * Description:		Show page for deleting an affiliate
 */


// Process affiliates deletion
if( isset( $_POST['delete'] ) )
{
	$id = $sanitize->for_db($_POST['id']);
	$delete = $database->query("DELETE FROM `tcg_affiliates` WHERE `aff_id`='$id'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the affiliate hasn't been deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The affiliate was successfully deleted.";
	}
}


// Check if page is being accessed directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show deletion form
else
{
	echo '<center>';
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

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<p>Are you sure you want to delete this affiliate? <b>This action can not be undone!</b><br />
	Click on the button below to delete the affiliate:<br />
	<input type="submit" name="delete" class="btn btn-danger" value="Delete this affiliate"></p>
	</form>';
}
?>