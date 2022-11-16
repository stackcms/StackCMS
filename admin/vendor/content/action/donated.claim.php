<?php
/********************************************************
 * Action:			Claim Donated Decks
 * Description:		Show page for claiming a donated deck
 */


// Process claim a deck form
if( isset( $_POST['claim'] ) )
{
	$id = $_POST['id'];
	$maker = $_POST['maker'];
	$update = $database->query("UPDATE `tcg_donations` SET `deck_maker`='$maker' WHERE `deck_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the deck was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "You have claimed to make the deck!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show claim a deck form
else
{
	$sql = $database->get_assoc("SELECT * FROM `tcg_donations` WHERE `deck_id`='$id'");
	echo '<p>If you are a deck maker, use this form to claim a card deck to make from the database.</p>

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
	<input type="hidden" name="maker" value="'.$player.'" />
	<p>Are you sure you want to claim the <b>'.$sql['deck_name'].'</b> deck? <b>This action can not be undone!</b><br />
	Click on the button below to claim the card deck:<br />
	<input type="submit" name="claim" class="btn btn-success" value="Yes, I\'m making this deck!" /></p>
	</form>';
}
?>