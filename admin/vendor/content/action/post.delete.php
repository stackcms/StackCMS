<?php
/*****************************************************
 * Action:			Delete Blog Posts
 * Description:		Show page for deleting a blog post
 */


// Process delete a blog post form
if( isset( $_POST['delete'] ) )
{
	$id=$_POST['id'];
	$delete = $database->query("DELETE FROM `tcg_post` WHERE `post_id`='$id' AND `post_type`='post'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the blog post was not deleted from the database. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The blog post has been successfully deleted from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show deletion form
else
{
	echo '<h1>Delete a Blog Post</h1>
	<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<p>Are you sure you want to delete this update? <b>This action can not be undone!</b><br />
	Click on the button below to delete the update:<br />
	<input type="submit" name="delete" class="btn-cancel" value="Delete"></p>
	</form>';
}
?>