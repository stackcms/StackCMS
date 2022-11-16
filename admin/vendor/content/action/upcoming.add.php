<?php
/******************************************************
 * Action:			Add Upcoming Decks
 * Description:		Show page for adding upcoming decks
 */


// Run arrays of rewards
$random = explode(", ", $settings->getValue( 'prize_deckmaker_reg' ));
$money = explode(", ", $settings->getValue( 'prize_deckmaker_cur' ));
$array_count = count($random);
$array_count .= count($money);
for( $i = 0; $i < ($array_count -1); $i++ )
{
	isset( $random[$i] );
	isset( $money[$i] );
}


// Process add an upcoming deck form
if( isset( $_POST['add'] ) )
{
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
	$set = $_POST['set'];
	$desc = $_POST['entry'];
	$desc = nl2br($desc);

	$desc = str_replace("'","\'",$desc);
	$set = str_replace("'","\'",$set);

	$date = date("Y-m-d", strtotime("now"));

	$insert = $database->query("INSERT INTO `tcg_cards` (`card_filename`,`card_deckname`,`card_color`,`card_puzzle`,`card_desc`,`card_maker`,`card_donator`,`card_cat`,`card_set`,`card_count`,`card_worth`,`card_break`,`card_mast`,`card_masters`,`card_status`,`card_released`) VALUES ('$filename','$deckname','$color','$puzzle','$desc','$maker','$donator','$cat','$set','$cards','$worth','$break','$mast','None','Upcoming','$date')");

	// Insert acquited data if all queries are correct
	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the card deck was not added. ".mysqli_error($insert)."";
	}

	else
	{
		$activity = '<span class="fas fa-plus-circle" aria-hidden="true"></span> <a href="'.$tcgurl.'/members.php?id='.$maker.'">'.$maker.'</a> added <a href="'.$tcgurl.'/cards.php?view=upcoming&deck='.$filename.'">'.$deckname.'</a> to the upcoming list.';

		$database->query("DELETE FROM `tcg_donations` WHERE `deck_filename`='$filename'");
		$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_slug`,`act_type`,`act_date`) VALUES ('$maker','$activity','$filename','upcoming','$date')");

		// Do not send deck maker rewards if status is not Open
		if( $settings->getValue( 'tcg_status' ) == 'Open' )
		{
			// Rewards iteration based on deck categories
			$x = $cat - 1;
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('$maker','Paycheck','(Deck Making: $filename)','No','".$random[$x]."','".$money[$x]."','$date')");
		}
		else {}

		$upload->cards();
		$success[] = "The deck has been successfully added!";
	}
}


// Show add an upcoming deck form
echo '<h1>Add an Upcoming Deck</h1>
<p>Use this form to add an upcoming deck to the database. Use the <a href="'.$tcgurl.'admin/cards.php">edit</a> form to update information for existing card decks.</p>
<ul>
	<li>Please make sure to zip all the cards of the deck you\'re going to add first.</li>
	<li>DO NOT put the cards into a folder before zipping! Otherwise it will become a sub folder in the <code>images/cards/</code> directory and the cards will not be displayed properly.</li>
</ul>

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

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action='.$act.'" multipart="" enctype="multipart/form-data">
<div class="row">
	<div class="col">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-card-image" role="image"></i></span>
				</div>
				<input type="text" name="deckname" class="form-control" placeholder="deck name">
				<input type="text" name="filename" class="form-control" placeholder="filename">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-people-fill" role="image"></i></span>
				</div>
				<input type="text" name="maker" class="form-control" placeholder="deck maker">
				<input type="text" name="donator" class="form-control" placeholder="deck donator">
			</div>

			<b>Description:</b><br />';
			@include($tcgpath.'admin/theme/text-editor.php');
			echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small><br />
			<textarea name="entry" id="entry" class="form-control" rows="10" /></textarea>
		</div>	
	</div>

	<div class="col-4">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="category"><b>Category</b></span>
				</div>
				<select name="category" class="form-control" aria-label="Category" aria-describedby="category">';
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
				<input type="text" name="count" class="form-control">
				<input type="text" name="break" class="form-control">
			</div>

			<b>Deck Type:</b> <small><i>This will define your card worth.</i></small><br />';
			// Run arrays of card worth from admin settings
			$cardWorth = explode(", ", $settings->getValue( 'cards_total_worth' ));
			foreach( $cardWorth as $worth )
			{
				if( $worth == "3" ) { $type = 'Rare'; }
				elseif( $worth == "2" ) { $type = 'Special'; }
				elseif( $worth == "1") { $type = 'Regular'; }
				echo '<input type="radio" name="worth" value="'.$worth.'" /> '.$type.'<br />';
			}

			echo '<br />

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Color</b></span>
				</div>
				<input type="text" name="color" class="form-control" placeholder="e.g. DarkGoldenrod">
			</div>

			<b>Puzzle?</b> <input type="radio" name="puzzle" value="Yes" /> Yes 
			<input type="radio" value="No" name="puzzle" checked> No<br />

			<b>Masterable?</b> <input type="radio" value="Yes" name="masterable" checked> Yes 
			<input type="radio" value="No" name="masterable"> No<br /><br />

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Upload cards</b></span>
				</div>
				<div class="custom-file">
					<input type="file" name="file" class="custom-file-input" id="inputGroupFile">
					<label class="custom-file-label" for="inputGroupFile">Choose file</label>
				</div>
			</div>

			<div align="right" style="margin-top:20px;">
				<input type="submit" name="add" class="btn btn-success" value="Add Deck" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div>
		</div>
	</div>
</div><!-- .row -->
</form>';
?>