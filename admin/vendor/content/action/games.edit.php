<?php
/********************************************************
 * Action:			Edit Password Games
 * Description:		Show page for editing a password game
 */


// Process edit a password gate form
if( isset( $_POST['update'] ) ) {
	$id = $sanitize->for_db($_POST['id']);
	$slug = $sanitize->for_db($_POST['game']);
	$pass = $sanitize->for_db($_POST['password']);
	$clue = $sanitize->for_db($_POST['clue']);
	$type = $sanitize->for_db($_POST['type']);
	$set = $sanitize->for_db($_POST['set']);
	$choice = $sanitize->for_db($_POST['choice']);
	$random = $sanitize->for_db($_POST['random']);
	$currency = $sanitize->for_db($_POST['currency']);
	$multiple = $sanitize->for_db($_POST['multiple']);
	$date = $_POST['date'];
	$question = $_POST['question'];
	$excerpt = $_POST['excerpt'];
	$blurbs = $_POST['entry'];
	$title = $_POST['title'];
	$sub = $_POST['sub'];
	$blurbs = nl2br($blurbs);

	$question = str_replace("'","\'",$question);
	$excerpt = str_replace("'","\'",$excerpt);
	$blurbs = str_replace("'","\'",$blurbs);
	$title = str_replace("'","\'",$title);
	$sub = str_replace("'","\'",$sub);

	$update = $database->query("UPDATE `tcg_games` SET `game_slug`='$slug', `game_title`='$title', `game_set`='$set', `game_subtitle`='$sub', `game_type`='$type', `game_excerpt`='$excerpt', `game_desc`='$blurbs', `game_multiple`='$multiple', `game_ques_array`='$question', `game_pass_array`='$pass',`game_clue_array`='$clue', `game_choice_array`='$choice', `game_random_array`='$random', `game_currency_array`='$currency', `game_updated`='$date' WHERE `game_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the game was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The game was successfully updated from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit a password gate game form
else
{
	$row = $database->get_assoc("SELECT * FROM `tcg_games` WHERE `game_id`='$id'");
	echo '<h1>Edit a Password Game</h1>
	<p>Use this form to edit a password gate game in the database. Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&action=add">add</a> form to add a new password gate game.</p>

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

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col">
			<div class="box">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Title & Slug</b></span>
					</div>
					<input type="text" name="title" class="form-control" value="'.$row['game_title'].'">
					<input type="text" name="game" class="form-control" value="'.$row['game_slug'].'">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Subtitle</b></span>
					</div>
					<input type="text" name="sub" class="form-control" value="'.$row['game_subtitle'].'">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Excerpt</b></span>
					</div>
					<input type="text" name="excerpt" class="form-control" value="'.$row['game_excerpt'].'">
				</div>

				<b>Blurbs/Mechanics:</b><br />';
				@include($tcgpath.'admin/theme/text-editor.php');
				echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to and <u>PHP is not allowed</u>.</i></small><br />
				<textarea name="entry" id="entry" rows="5" class="form-control">'.$row['game_desc'].'</textarea><br />

				<b>Questions:</b><br />
				<small><i>This content area doesn\'t support HTML tags and <u>PHP is not allowed</u>!</i></small><br />
				<textarea name="question" id="question" rows="5" class="form-control">'.$row['game_ques_array'].'</textarea><br />

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Passwords</b></span>
					</div>
					<input type="text" name="password" class="form-control" value="'.$row['game_pass_array'].'">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Images/Clues</b></span>
					</div>
					<input type="text" name="clue" class="form-control" value="'.$row['game_clue_array'].'">
				</div>
				<small><i>If this is an image type, just type the filename and the extension (e.g. clue01.jpg).</i></small>
			</div><!-- .box -->
		</div><!-- .col -->

		<div class="col-4">
			<div class="box">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Game Updated</b></span>
					</div>
					<input type="date" name="date" class="form-control" value="'.$row['game_updated'].'">
				</div>

				<b>Game Status:</b><br />';
				if( $row['game_status'] == "Active" )
				{
					echo '<input type="radio" name="status" value="Active" checked /> Active &nbsp;&nbsp; 
					<input type="radio" name="status" value="Inactive" /> Inactive';
				}

				else
				{
					echo '<input type="radio" name="status" value="Active" /> Active &nbsp;&nbsp; 
					<input type="radio" name="status" value="Inactive" checked /> Inactive';
				}

				echo '<br /><br />

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Game Set</b></span>
					</div>
					<select name="set" class="form-control">
						<option value="'.$row['game_set'].'">Current: '.$row['game_set'].'</option>
						<option value="Weekly">Weekly</option>
						<option value="Set A">Bi-weekly A</option>
						<option value="Set B">Bi-weekly B</option>
						<option value="Monthly">Monthly</option>
						<option value="Special">Special</option>
					</select>
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Game Type</b></span>
					</div>
					<select name="type" class="form-control">
						<option value="'.$row['game_type'].'">Current: '.$row['game_type'].'</option>
						<option value="image">Image</option>
						<option value="text">Text</option>
						<option value="none">None</option>
					</select>
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Choice Cards</b></span>
					</div>
					<input type="text" name="choice" class="form-control" value="'.$row['game_choice_array'].'">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Random Cards</b></span>
					</div>
					<input type="text" name="random" class="form-control" value="'.$row['game_random_array'].'">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Currencies</b></span>
					</div>
					<input type="text" name="currency" class="form-control" value="'.$row['game_currency_array'].'">
				</div>
				<small><i>Amount of currencies, separate values with a vertical slash followed by a comma for multiple currencies per multiple rewards.</i></small><br /><br />

				<b>With multiple rewards?</b><br />';
				if( $row['game_multiple'] == 1 )
				{
					echo '<input type="radio" name="multiple" id="multiple" value="1" checked /> Yes &nbsp;&nbsp;&nbsp; 
					<input type="radio" name="multiple" id="multiple" value="0" /> No';
				}

				else
				{
					echo '<input type="radio" name="multiple" id="multiple" value="1" /> Yes &nbsp;&nbsp;&nbsp; 
					<input type="radio" name="multiple" id="multiple" value="0" checked /> No';
				}
				echo '<br /><br />

				<div align="right" style="margin-top:20px;">
					<input type="submit" name="update" class="btn btn-success" value="Edit password game" /> 
					<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
				</div>
			</div><!-- .box -->
		</div><!-- .col-4 -->
	</div><!-- .row -->
	</form>';
}
?>