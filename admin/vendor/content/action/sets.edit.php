<?php
/*********************************************************
 * Action:			Edit Sets/Series
 * Description:		Show page for editing deck sets/series
 */


// Process edit a deck set/series form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$name = $sanitize->for_db($_POST['name']);
	$update = $database->query("UPDATE `tcg_cards_set` SET `set_name`='$name' WHERE `set_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the set/series was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The set/series was successfully updated from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit a deck set/series form
else
{
	echo '<h1>Edit a Card Set/Series</h1>
	<p>Use this form to edit an existing set/series in the database.<br />
	Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add a new set/series for your card decks.</p>

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

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="col" style="width: 600px;">
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Set/Series:</b></span>
			</div>';
			$row = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='$id'");
			echo '<input type="text" name="name" value="'.$row['set_name'].'" class="form-control">
		</div>

		<input type="submit" name="update" class="btn btn-success" value="Edit Set/Series" /> 
		<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</div>
	</form>';
}
?>