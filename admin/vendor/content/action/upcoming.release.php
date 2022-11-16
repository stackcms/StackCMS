<?php
/*************************************************************
 * Action:			Release Upcoming Decks
 * Description:		Process form of releasing an upcoming deck
 */


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

else
{
	// Process release an upcoming deck form
	if( isset( $_POST['release'] ) )
	{
		$id = $_POST['id'];
		$released = $_POST['date'];

		$update = $database->query("UPDATE `tcg_cards` SET `card_status`='Active', `card_released`='$released', `card_votes`='0' WHERE `card_id`='$id'");
		$row = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_id`='$id'");

		if( !$update )
		{
			$error[] = "Sorry, there was an error and the card deck was not released. ".mysqli_error($update)."";
		}

		else
		{
			$activity = '<span class="fas fa-paper-plane" aria-hidden="true"></span> <a href="'.$tcgurl.'/members.php?id='.$player.'">'.$player.'</a> released the <a href="'.$tcgurl.'/cards.php?view=released&deck='.$row['card_filename'].'">'.$row['card_deckname'].'</a> deck.';

			$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_slug`,`act_type`,`act_date`) VALUES ('$player','$activity','".$row['card_filename']."','released','$released')");

			$success[] = 'The card deck was successfully added to the released decks and was deleted from the upcoming list.<br />
			Want to <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">release</a> more decks?';
		}
	}

	// Show release an upcoming deck form
	$row = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_status`='Upcoming' AND `card_id`='$id'");
	echo '<h1>Release a Deck</h1>
	<p>Please specify the release date of the '.$row['card_deckname'].' deck.</p>

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
				<span class="input-group-text"><b>Release Date:</b></span>
			</div>
			<input type="date" name="date" class="form-control">
			<div class="input-group-append">
				<input type="submit" name="release" class="btn btn-success" value="Release deck" />
			</div>
		</div>
	</div>
	</form>';
}
?>