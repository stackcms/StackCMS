<?php
/************************************************************
 * Action:			Edit Released Deck
 * Description:		Show page for editing released card decks
 */


// Process edit decks form
if( isset( $_POST['edit'] ) )
{
	$id = $_POST['id'];
	$filename = $sanitize->for_db($_POST['filename']);
	$deckname = $sanitize->for_db($_POST['deckname']);
	$donator = $sanitize->for_db($_POST['donator']);
	$maker = $sanitize->for_db($_POST['maker']);
	$color = $sanitize->for_db($_POST['color']);
	$puzzle = $sanitize->for_db($_POST['puzzle']);
	$cat = $sanitize->for_db($_POST['category']);
	$cards = $sanitize->for_db($_POST['count']);
	$worth = $sanitize->for_db($_POST['worth']);
	$break = $sanitize->for_db($_POST['break']);
	$mast = $sanitize->for_db($_POST['masterable']);
	$masters = $sanitize->for_db($_POST['masters']);
	$status = $sanitize->for_db($_POST['status']);
	$date = $_POST['date'];
	$set = $_POST['set'];
	$desc = $_POST['entry'];
	$desc = nl2br($desc);

	$desc = str_replace("'","\'",$desc);
	$set = str_replace("'","\'",$set);

	$update = $database->query("UPDATE `tcg_cards` SET `card_filename`='$filename', `card_deckname`='$deckname', `card_donator`='$donator', `card_maker`='$maker', `card_color`='$color', `card_puzzle`='$puzzle', `card_desc`='$desc', `card_set`='$set', `card_cat`='$cat', `card_count`='$cards', `card_worth`='$worth', `card_break`='$break', `card_mast`='$mast', `card_masters`='$masters', `card_status`='$status', `card_released`='$date' WHERE `card_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the card deck was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The card deck was successfully updated in the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit deck form
else
{
	$row = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_id`='$id' AND `card_status`='Active'");
	$description = str_replace("<br />", " ", nl2br($row['card_desc']));

	if( $row['card_status'] != "Active" )
	{
		echo '<h1>Halt!</h1>
		<p>It seems like the deck that you are trying to edit is not an active deck. Please make sure that the deck associated with an ID of '.$row['card_id'].' is an active deck.</p>';
	}

	else
	{
		echo '<h1>Edit a Deck</h1>
		<p>Use this form to edit a card deck in the database.<br />
		Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page=upcoming&action=add">add</a> form to add new card decks.</p>

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
		<div class="row">
			<div class="col">
				<div class="box">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="bi-card-image" role="image"></i></span>
						</div>
						<input type="text" name="deckname" value="'.$row['card_deckname'].'" class="form-control">
						<input type="text" name="filename" value="'.$row['card_filename'].'" class="form-control">
					</div>

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="bi-people-fill" role="image"></i></span>
						</div>
						<input type="text" name="maker" value="'.$row['card_maker'].'"  class="form-control">
						<input type="text" name="donator" value="'.$row['card_donator'].'" class="form-control">
					</div>

					<b>Description:</b><br />';
					@include($tcgpath.'admin/theme/text-editor.php');
					echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small>
					<textarea name="entry" id="entry" class="form-control" rows="10" />'.$description.'</textarea><br />

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><b>Masters</b></span>
						</div>
						<textarea name="masters" class="form-control" rows="2" />'.$row['card_masters'].'</textarea>
					</div>
				</div><!-- .box -->
			</div><!-- .col -->

			<div class="col-4">
				<div class="box">
					<b>Status:</b> ';
					if( $row['card_status'] == "Active" )
					{
						echo '<input type="radio" value="Upcoming" name="status"> Upcoming &nbsp;&nbsp;&nbsp; 
						<input type="radio" value="Active" name="status" checked> Active';
					}

					else
					{
						echo '<input type="radio" value="Upcoming" name="status" checked> Upcoming &nbsp;&nbsp;&nbsp; 
						<input type="radio" value="Active" name="status"> Active';
					}

					echo '<br /><br />

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><b>Released Date</b></span>
						</div>
						<input type="date" name="date" value="'.$row['card_released'].'" class="form-control">
					</div>

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="category"><b>Category</b></span>
						</div>
						<select name="category" class="form-control" aria-label="Category" aria-describedby="category">';
						$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['card_cat']."'");
						echo '<option value="'.$row['card_cat'].'">Current: '.$cat['cat_name'].'</option>';
						$c = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_name` ASC");
						while( $cat = mysqli_fetch_assoc( $c ) )
						{
							echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_name']."</option>\n";
						}
						echo '</select>
					</div>

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="set"><b>Set/Series</b></span>
						</div>
						<select name="set" class="form-control" aria-label="Set/Series" aria-describedby="set">';
						$set = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['card_set']."'");
						echo '<option value="'.$set['set_id'].'">Current: '.$set['set_name'].'</option>';
						$s = $database->query("SELECT * FROM `tcg_cards_set` ORDER BY `set_name` ASC");
						while( $set = mysqli_fetch_assoc( $s ) )
						{
							echo '<option value="'.$set['set_id'].'">'.$set['set_name']."</option>\n";
						}
						echo '</select>
					</div>

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><b>Count and break</b></span>
						</div>
						<input type="text" name="count" value="'.$row['card_count'].'" class="form-control">
						<input type="text" name="break" value="'.$row['card_break'].'" class="form-control">
					</div>

					<b>Deck Type:</b> <small><i>This will define your card worth.</i></small><br />';
					// Check worth
					if( $row['card_worth'] == "3" ) {
                        echo '<input type="radio" name="worth" value="1" /> Regular<br />
                        <input type="radio" name="worth" value="2" /> Special<br />
                        <input type="radio" name="worth" value="3" checked /> Rare<br />';
					}
					elseif( $row['card_worth'] == "2" ) {
                        echo '<input type="radio" name="worth" value="1" /> Regular<br />
                        <input type="radio" name="worth" value="2" checked /> Special<br />
                        <input type="radio" name="worth" value="3" /> Rare<br />';
					}
					else {
                        echo '<input type="radio" name="worth" value="1" checked /> Regular<br />
                        <input type="radio" name="worth" value="2" /> Special<br />
                        <input type="radio" name="worth" value="3" /> Rare<br />';
					}

					echo '<br />

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><b>Color</b></span>
						</div>
						<input type="text" name="color" class="form-control" value="'.$row['card_color'].'">
					</div>';

					if( $row['card_puzzle'] == "Yes" )
					{
						echo '<b>Puzzle?</b> <input type="radio" value="Yes" name="puzzle" checked> Yes 
						<input type="radio" value="No" name="puzzle"> No';
					}

					else
					{
						echo '<b>Puzzle?</b> <input type="radio" value="Yes" name="puzzle"> Yes 
						<input type="radio" value="No" name="puzzle" checked> No';
					}

					echo '<br />';

					if( $row['card_mast'] == "Yes" )
					{
						echo '<b>Masterable?</b> <input type="radio" value="Yes" name="masterable" checked> Yes 
						<input type="radio" value="No" name="masterable"> No';
					}

					else
					{
						echo '<b>Masterable?</b> <input type="radio" value="Yes" name="masterable"> Yes 
						<input type="radio" value="No" name="masterable" checked> No';
					}

					echo '<div align="right" style="margin-top:20px;">
						<input type="submit" name="edit" class="btn btn-success" value="Edit Deck" /> 
						<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
					</div>
				</div><!-- .box -->
			</div><!-- .col-4 -->
        </div><!-- .row -->
		</form>';
	}
}
?>