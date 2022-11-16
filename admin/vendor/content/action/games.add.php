<?php
/*************************************************************
 * Action:			Add Password Game
 * Description:		Show form for adding a password gate games
 */


// Process add a password gate game
if( isset( $_POST['add'] ) )
{
	$slug = $sanitize->for_db($_POST['game']);
	$pass = $sanitize->for_db($_POST['password']);
	$clue = $sanitize->for_db($_POST['clue']);
	$type = $sanitize->for_db($_POST['type']);
	$set = $sanitize->for_db($_POST['set']);
	$choice = $sanitize->for_db($_POST['choice']);
	$random = $sanitize->for_db($_POST['random']);
	$currency = $sanitize->for_db($_POST['currency']);
	$multiple = $sanitize->for_db($_POST['multiple']);
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

	$insert = $database->query("INSERT INTO `tcg_games` (`game_slug`,`game_title`,`game_set`,`game_subtitle`,`game_excerpt`,`game_desc`,`game_multiple`,`game_ques_array`,`game_pass_array`,`game_clue_array`,`game_choice_array`,`game_random_array`,`game_currency_array`,`game_type`,`game_status`,`game_current_array`) VALUES ('$slug','$title','$set','$sub','$excerpt','$blurbs','$multiple','$question','$pass','$clue','$choice','$random','$currency','$type','Inactive','0')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the game was not added. ".mysqli_error($insert)."";
	}

	else
	{
		$success[] = "The game was successfully added to the database!";
	}
}


// Show add a password gate game form
echo '<h1>Add a Password Game</h1>
<p>Use this form to add a password gate game to the database. Otherwise <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'">proceed to this page</a> to update an existing game.<br />
If your password gate game have multiple set of rewards, separate the following as instructed:
<li>Choice and random cards: separate with a comma and space (e.g. 10, 8, 6, 4, 2)</li>
<li>Multiple currencies: separate with a vertical and space first, then with a comma (e.g. 0 | 2, 0 | 4, 0 | 6)</li>
<li>Single currency: separate with a vertical slash and space (e.g. 2 | 4 | 6)</li></p>

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

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'">
<div class="row">
	<div class="col">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Title & Slug</b></span>
				</div>
				<input type="text" name="title" class="form-control" placeholder="Black Jack">
				<input type="text" name="game" class="form-control" placeholder="black-jack">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Subtitle</b></span>
				</div>
				<input type="text" name="sub" class="form-control">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Excerpt</b></span>
				</div>
				<input type="text" name="excerpt" class="form-control" placeholder="e.g. Black Jack with a twist">
			</div>

			<b>Blurbs/Mechanics:</b><br />';
			@include($tcgpath.'admin/theme/text-editor.php');
			echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to and <u>PHP is not allowed</u>.</i></small><br />
			<textarea name="entry" id="entry" rows="5" class="form-control"></textarea><br />

			<b>Questions:</b><br />
			<small><i>This content area doesn\'t support HTML tags and <u>PHP is not allowed</u>!</i></small><br />
			<textarea name="question" id="question" rows="5" class="form-control"></textarea><br />

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Passwords</b></span>
				</div>
				<input type="text" name="password" class="form-control" placeholder="chocolate, cakes, biscuits, candies, cookies">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Images/Clues</b></span>
				</div>
				<input type="text" name="clue" class="form-control" placeholder="clue01.jpg, clue02.jpg, clue03.jpg, clue04.jpg, clue05.jpg">
			</div>
			<small><i>If this is an image type, just type the filename and the extension (e.g. clue01.jpg).</i></small>
		</div><!-- .box -->
	</div><!-- .col -->

	<div class="col-4">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Game Set</b></span>
				</div>
				<select name="set" class="form-control">
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
					<option value="image">Image</option>
					<option value="text">Text</option>
					<option value="none">None</option>
				</select>
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Choice Cards</b></span>
				</div>
				<input type="text" name="choice" class="form-control" placeholder="e.g. 2, 1, 0">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Random Cards</b></span>
				</div>
				<input type="text" name="random" class="form-control" placeholder="e.g. 5, 4, 3">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Currencies</b></span>
				</div>
				<input type="text" name="currency" class="form-control" placeholder="e.g. 5 | 3 | 1, 6 | 4 | 2, 7 | 5 | 3">
			</div>
			<small><i>Amount of currencies, separate values with a vertical slash followed by a comma for multiple currencies per multiple rewards.</i></small><br /><br />

			<b>With multiple rewards?</b><br />
			<input type="radio" name="multiple" id="multiple" value="1" /> Yes &nbsp;&nbsp;&nbsp; 
			<input type="radio" name="multiple" id="multiple" value="0" /> No<br /><br />

			<div align="right" style="margin-top:20px;">
				<input type="submit" name="add" class="btn btn-success" value="Add game" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div>
		</div><!-- .box -->
	</div><!-- .col-4 -->
</div><!-- .row -->
</form>';
?>