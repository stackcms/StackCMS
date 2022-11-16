<?php
/***************************************************
 * Action:			Add Blog Posts
 * Description:		Show page for adding a blog post
 */


// Process add a blog post form
if( isset( $_POST['add'] ) )
{
	$mem = $sanitize->for_db($_POST['members']);
	$lvl = $sanitize->for_db($_POST['levels']);
	$mas = $sanitize->for_db($_POST['masters']);
	$decks = $sanitize->for_db($_POST['decks']);
	$wish = $sanitize->for_db($_POST['wish']);
	$card = $sanitize->for_db($_POST['amount']);
	$stat = $sanitize->for_db($_POST['status']);
	$auth = $sanitize->for_db($_POST['author']);
	$icon = $sanitize->for_db($_POST['icon']);
	$game = $sanitize->for_db($_POST['games']);
	$refer = $sanitize->for_db($_POST['referrals']);
	$aff = $_POST['affiliates'];
	$title = $_POST['title'];
	$entry = $_POST['entry'];

	$entry = str_replace("'","\'",$entry);
	$title = str_replace("'","\'",$title);

	$timestamp = $_POST['date'];
	$entry = nl2br($entry);

	$result = $database->query("INSERT INTO `tcg_post` (`post_date`,`post_title`,`post_auth`,`post_icon`,`post_member`,`post_master`,`post_level`,`post_affiliate`,`post_game`,`post_referral`,`post_deck`,`post_status`,`post_wish`,`post_amount`,`post_content`,`post_type`) VALUES ('$timestamp','$title','$auth','$icon','$mem','$mas','$lvl','$aff','$game','$refer','$decks','$stat','$wish','$card','$entry','post')") or print("Can't insert into table tcg_post.<br />" . $result . "<br />Error:" . mysqli_connect_error());

	if( !$result )
	{
		$error[] = "Sorry, there was an error and your blog entry was not added. ".mysqli_error($result)."";
	}

	else
	{
		// Update games date according to game set array
		$games = explode(", ", $game);
		foreach( $games as $gameSet )
		{
			$gameUpdate = $database->query("UPDATE `tcg_games` SET `game_updated`='$timestamp' WHERE `game_set`='$gameSet'");
		}

		// Fill melting pot with new cards
		$database->query("DELETE FROM `game_mpot_cards`");
		$mpot = $database->query("SELECT * FROM `tcg_cards` WHERE `cards_status`='Active'");
		$min = 1; $max = mysqli_num_rows($mpot); $pots = null;
		for( $i = 0; $i < $settings->getValue('xtra_mpot'); $i++ )
		{
			mysqli_data_seek($mpot,rand($min,$max)-1);
			$row = mysqli_fetch_assoc($mpot);
			$digits = rand(01,$row['card_count']);
			if($digits < 10)
			{
				$digit = "0$digits";
			}
			else
			{
				$digit = $digits;
			}
			$card = $row['card_filename'].''.$digit;
			$pots .= "('".$card."'),";
		}
		$pots = substr_replace($pots,"",-1);
		$meltingPot = $database->query("INSERT INTO `game_mpot_cards` (`mpot_card`) VALUES $pots");

		// Fill card claim with new cards
		$database->query("DELETE FROM `game_cclaim_cards`");
		$cclaim = $database->query("SELECT * FROM `tcg_cards` WHERE `cards_status`='Active'");
		$min = 1; $max = mysqli_num_rows($cclaim); $claims = null;
		for( $i = 0; $i < $settings->getValue('xtra_cclaim'); $i++ )
		{
			mysqli_data_seek($cclaim,rand($min,$max)-1);
			$row = mysqli_fetch_assoc($cclaim);
			$digits = rand(01,$row['card_count']);
			if($digits < 10)
			{
				$digit = "0$digits";
			}
			else
			{
				$digit = $digits;
			}
			$card = $row['card_filename'].''.$digit;
			$claims .= "('".$card."'),";
		}
		$claims = substr_replace($claims,"",-1);
		$cardClaim = $database->query("INSERT INTO `game_cclaim_cards` (`cclaim_cards`) VALUES $claims");

		if( !$gameUpdate )
		{
			$error[] = "It seems like there\'s a problem updating the game\'s date.".mysqli_error($gameUpdate)."";
		}

		elseif( !$meltingPot )
		{
			$error[] = "It seems like there\'s a problem filling the melting pot with new cards.".mysqli_error($meltingPot)."";
		}

		elseif( !$cardClaim )
		{
			$error[] = "It seems like there\'s a problem filling the card claim with new cards.".mysqli_error($cardClaim)."";
		}

		else
		{
			$success[] = "Your blog entry has successfully been entered into the database.";
		}
	}
}

echo '<h1>Add a Blog Post</h1>
<p>Use the form below to create a new blog post for your TCG\'s weekly update.<br />
If you want to update the information for an existing blog post, kindly use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'">edit form</a> instead.</p>

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
<input type="hidden" name="author" value="'.$player.'" />
<div class="row">
	<div class="col">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Title</b></span>
				</div>
				<input type="text" name="title" class="form-control" placeholder="This week\'s update!">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Icon</b></span>
				</div>
				<input type="text" name="icon" class="form-control" placeholder="image file (e.g. icon.png)">
				<div class="input-group-append">
					<span class="input-group-text"><i>(Optional)</i></span>
				</div>
			</div>

			<b>Content:</b><br />';
			@include($tcgpath.'admin/theme/text-editor.php');
			echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small><br />
			<textarea name="entry" id="entry" class="form-control" rows="10" /></textarea><br />

			<b>Masteries:</b><br />
			<textarea name="masters" class="form-control" rows="4" /></textarea><br />

			<b>Level Ups:</b><br />
			<textarea name="levels" class="form-control" rows="4" /></textarea><br />
			
			<b>Affiliates:</b><br />
			<small><i>Use HTML links to display the linked affiliated TCG on the update. Otherwise type <code>None</code>.</i></small><br />
			<textarea name="affiliates" class="form-control" rows="4" /></textarea>
		</div><!-- box -->
	</div><!-- col -->

	<div class="col-4">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Publish</b></span>
				</div>
				<input type="date" name="date" class="form-control">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Status</b></span>
				</div>
				<select name="status" class="form-control">
					<option value="">----- Select status -----</option>
					<option value="Draft">Draft</option>
					<option value="Published">Published</option>
					<option value="Scheduled">Scheduled</option>
				</select>
			</div>

			<b>New Decks:</b><br />
			<textarea name="decks" class="form-control" rows="2" /></textarea><br />
			
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Amount</b></span>
				</div>
				<input type="text" name="amount" placeholder="Amount of released decks" class="form-control" />
			</div>

			<small><i>Type <code>None</code> on the fields if there are no new members or referrals.</i></small><br />
			<b>New Members:</b><br />
			<input type="text" name="members" placeholder="Player01, Player02" class="form-control" /><br />

			<b>Referrals:</b><br />
			<input type="text" name="referrals" placeholder="Player10, Player08" class="form-control" /><br />

			<b>Games:</b><br />
			<input type="text" name="games" placeholder="Weekly, Set A, Monthly" class="form-control" /><br />

			<b>Wishes:</b><br />
			<small><i>Select the appropriate choice if there are granted wishes or none for this update.</i></small><br />
			<input type="radio" name="wish" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; 
			<input type="radio" name="wish" value="None" /> None

			<div align="right" style="margin-top:20px;">
				<input type="submit" name="add" class="btn btn-success" value="Add Blog" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div>
		</div><!-- box -->
	</div><!-- col-4 -->
</div><!-- row -->
</form>';
?>