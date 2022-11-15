<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('wishes')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('wishes')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( $logDATE >= $ranDATE )
{
	echo '<h1>'.$games->gameTitle('wishes').' : Halt!</h1>
	<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
	$general->displayRewards('wishes');
	echo '</center>';
}

else
{
	// SPELL WORD WISH
	if( isset( $_POST['spell'] ) )
	{
		$check->Value();
		$type = $sanitize->for_db($_POST['type']);
		$name = $sanitize->for_db($_POST['name']);
		$word = $sanitize->for_db($_POST['word']);
		$today = date("Y-m-d", strtotime("now"));

		$wish = "I wish for choice cards spelling ".$word."!";
		$sql = $database->query("INSERT INTO `user_wishes` (`wish_name`,`wish_type`,`wish_word`,`wish_text`,`wish_status`,`wish_date`) VALUES ('$name','$type','$word','$wish','Pending','$today')");
		if( !$sql )
		{
			$error[] = "Sorry, there was an error and your wish was not submitted. ".mysqli_error($sql)."";
		}

		else
		{
			$success[] = "Your wish has been received! Wait for it to be granted~";
		}

		echo '<h1>'.$games->gameTitle('wishes').' - Prize Pickup</h1>';
		echo '<center><p>Thank you for making a wish! Take everything you see below and don\'t forget to log it!</p>';
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

		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('wishes')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'wishes' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('wishes'));
			$random = explode(", ", $games->gameRandArr('wishes'));
			$currency = explode(" | ", $games->gameCurArr('wishes'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Spell Word)',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('wishes');
			$rTotal = $games->gameRandArr('wishes');
			$mTotal = $games->gameCurArr('wishes');
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Spell Word)',$rTotal,$cTotal,$mTotal);
		}
	}


	// CHOICE PACK WISH
	if( isset( $_POST['choice'] ) )
	{
		$check->Value();
		$type = $sanitize->for_db($_POST['type']);
		$name = $sanitize->for_db($_POST['name']);
		$amnt = $sanitize->for_db($_POST['amount']);
		$today = date("Y-m-d", strtotime("now"));

		$wish = "I wish for a pack of ".$amnt." choice cards!";
		$sql = $database->query("INSERT INTO `user_wishes` (`wish_name`,`wish_type`,`wish_amount`,`wish_text`,`wish_status`,`wish_date`) VALUES ('$name','$type','$amnt','$wish','Pending','$today')");
		if( !$sql )
		{
			$error[] = "Sorry, there was an error and your wish was not submitted. ".mysqli_error($sql)."";
		}

		else
		{
			$success[] = "Your wish has been received! Wait for it to be granted~";
		}

		echo '<h1>'.$games->gameTitle('wishes').' - Prize Pickup</h1>';
		echo '<center><p>Thank you for making a wish! Take everything you see below and don\'t forget to log it!</p>';
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

		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('wishes')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'wishes' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('wishes'));
			$random = explode(", ", $games->gameRandArr('wishes'));
			$currency = explode(" | ", $games->gameCurArr('wishes'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Choice Pack)',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('wishes');
			$rTotal = $games->gameRandArr('wishes');
			$mTotal = $games->gameCurArr('wishes');
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Choice Pack)',$rTotal,$cTotal,$mTotal);
		}
	}


	// RANDOM PACK WISH
	if( isset( $_POST['random'] ) )
	{
		$check->Value();
		$type = $sanitize->for_db($_POST['type']);
		$name = $sanitize->for_db($_POST['name']);
		$amnt = $sanitize->for_db($_POST['amount']);
		$today = date("Y-m-d", strtotime("now"));

		$wish = "I wish for a pack of ".$amnt." random cards!";
		$sql = $database->query("INSERT INTO `user_wishes` (`wish_name`,`wish_type`,`wish_amount`,`wish_text`,`wish_status`,`wish_date`) VALUES ('$name','$type','$amnt','$wish','Pending','$today')");
		if( !$sql )
		{
			$error[] = "Sorry, there was an error and your wish was not submitted. ".mysqli_error($sql)."";
		}

		else
		{
			$success[] = "Your wish has been received! Wait for it to be granted~";
		}

		echo '<h1>'.$games->gameTitle('wishes').' - Prize Pickup</h1>';
		echo '<center><p>Thank you for making a wish! Take everything you see below and don\'t forget to log it!</p>';
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

		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('wishes')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'wishes' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('wishes'));
			$random = explode(", ", $games->gameRandArr('wishes'));
			$currency = explode(" | ", $games->gameCurArr('wishes'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Random Pack)',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('wishes');
			$rTotal = $games->gameRandArr('wishes');
			$mTotal = $games->gameCurArr('wishes');
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Random Pack)',$rTotal,$cTotal,$mTotal);
		}
	}


	// DECK COLOR WISH
	if( isset( $_POST['color'] ) )
	{
		$check->Value();
		$type = $sanitize->for_db($_POST['type']);
		$name = $sanitize->for_db($_POST['name']);
		$colr = $sanitize->for_db($_POST['colr']);
		$today = date("Y-m-d", strtotime("now"));

		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='$colr'");
		$wish = "I wish for choice cards from any ".$cat['cat_name']."-colored decks!";

		$sql = $database->query("INSERT INTO `user_wishes` (`wish_name`,`wish_type`,`wish_cat`,`wish_text`,`wish_status`,`wish_date`) VALUES ('$name','$type','$colr','$wish','Pending','$today')");
		if( !$sql )
		{
			$error[] = "Sorry, there was an error and your wish was not submitted. ".mysqli_error($sql)."";
		}

		else
		{
			$success[] = "Your wish has been received! Wait for it to be granted~";
		}

		echo '<h1>'.$games->gameTitle('wishes').' - Prize Pickup</h1>';
		echo '<center><p>Thank you for making a wish! Take everything you see below and don\'t forget to log it!</p>';
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

		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('wishes')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'wishes' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('wishes'));
			$random = explode(", ", $games->gameRandArr('wishes'));
			$currency = explode(" | ", $games->gameCurArr('wishes'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Deck Color)',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('wishes');
			$rTotal = $games->gameRandArr('wishes');
			$mTotal = $games->gameCurArr('wishes');
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Deck Color)',$rTotal,$cTotal,$mTotal);
		}
	}


	// DECK AMOUNT WISH
	if( isset( $_POST['release'] ) )
	{
		$check->Value();
		$type = $sanitize->for_db($_POST['type']);
		$name = $sanitize->for_db($_POST['name']);
		$amnt = $sanitize->for_db($_POST['amount']);
		$today = date("Y-m-d", strtotime("now"));

		$wish = "I wish for a double deck release!";
		$sql = $database->query("INSERT INTO `user_wishes` (`wish_name`,`wish_type`,`wish_amount`,`wish_text`,`wish_status`,`wish_date`) VALUES ('$name','$type','$amnt','$wish','Pending','$today')");
		if( !$sql )
		{
			$error[] = "Sorry, there was an error and your wish was not submitted. ".mysqli_error($sql)."";
		}

		else
		{
			$success[] = "Your wish has been received! Wait for it to be granted~";
		}

		echo '<h1>'.$games->gameTitle('wishes').' - Prize Pickup</h1>';
		echo '<center><p>Thank you for making a wish! Take everything you see below and don\'t forget to log it!</p>';
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

		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('wishes')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'wishes' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('wishes'));
			$random = explode(", ", $games->gameRandArr('wishes'));
			$currency = explode(" | ", $games->gameCurArr('wishes'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Double Deck Release)',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('wishes');
			$rTotal = $games->gameRandArr('wishes');
			$mTotal = $games->gameCurArr('wishes');
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Double Deck Release)',$rTotal,$cTotal,$mTotal);
		}
	}


	// DOUBLE GAME REWARDS WISH
	if( isset( $_POST['rewards'] ) )
	{
		$check->Value();
		$type = $sanitize->for_db($_POST['type']);
		$name = $sanitize->for_db($_POST['name']);
		$set = $sanitize->for_db($_POST['set']);
		$today = date("Y-m-d", strtotime("now"));

		$wish = "I wish for double rewards for the ".$set." set!";
		$sql = $database->query("INSERT INTO `user_wishes` (`wish_name`,`wish_type`,`wish_set`,`wish_text`,`wish_status`,`wish_date`) VALUES ('$name','$type','$set','$wish','Pending','$today')");
		if( !$sql )
		{
			$error[] = "Sorry, there was an error and your wish was not submitted. ".mysqli_error($sql)."";
		}

		else
		{
			$success[] = "Your wish has been received! Wait for it to be granted~";
		}

		echo '<h1>'.$games->gameTitle('wishes').' - Prize Pickup</h1>';
		echo '<center><p>Thank you for making a wish! Take everything you see below and don\'t forget to log it!</p>';
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

		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('wishes')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'wishes' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('wishes'));
			$random = explode(", ", $games->gameRandArr('wishes'));
			$currency = explode(" | ", $games->gameCurArr('wishes'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Double Game Rewards)',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('wishes');
			$rTotal = $games->gameRandArr('wishes');
			$mTotal = $games->gameCurArr('wishes');
			$general->gamePrize($games->gameSet('wishes'),$games->gameTitle('wishes'),'(Double Game Rewards)',$rTotal,$cTotal,$mTotal);
		}
	}
?>

<h1><?php echo $games->gameSet('wishes'); ?> - <?php echo $games->gameTitle('wishes'); ?></h1>
<?php echo $games->gameBlurb('wishes'); ?>
<p>If you're running out of ideas what to wish for, take these for your reference:<br />
- Cards to be spell out as a title case word (e.g. SUMMER2020).<br />
- Choice cards based on deck colors (e.g. Decks from R/G/B colors).<br />
- Decks you want to be released next.<br />
- Double rewards for any game sets.<br />
- Random packs from any decks.</p>
<p>Choose the type of wish you want to submit via the tabs below:</p>
<ul class="tabs" data-persist="true">
	<li><a href="#spell">Spell</a></li>
	<li><a href="#choice">Choice</a></li>
	<li><a href="#random">Random</a></li>
	<li><a href="#color">Color</a></li>
	<li><a href="#release">Release</a></li>
	<li><a href="#rewards">Game Rewards</a></li>
</ul>

<div class="tabcontents">
	<div id="spell" align="center">
		<h2>Spell a Word</h2><p>Use this form to wish for spell choice cards!<br />Type only your <u>desired word</u>, nothing else.</p>
		<form method="post" action="<?php echo $tcgurl; ?>games.php?play=wishes">
		<input type="hidden" name="type" value="1" />
		<input type="hidden" name="name" value="<?php echo $player; ?>" />
		<input type="text" name="word" placeholder="Capital letters only (e.g. PREJOIN)" style="width:50%;" /> <input type="submit" name="spell" value="Make a Wish!" class="btn-success" />
		</form>
	</div>

	<div id="choice" align="center">
		<h2>Choice Pack</h2><p>Use this form to wish for a certain amount of choice card packs!<br />You can only wish for a <u>minimum of 5 cards</u> and a <u>maximum of 10 cards</u>.</p>
		<form method="post" action="<?php echo $tcgurl; ?>games.php?play=wishes">
		<input type="hidden" name="type" value="2" />
		<input type="hidden" name="name" value="<?php echo $player; ?>" />
		<input type="number" name="amount" min="5" max="10" style="width:50%;" /> <input type="submit" name="choice" value="Make a Wish!" class="btn-success" />
		</form>
	</div>

	<div id="random" align="center">
		<h2>Random Pack</h2><p>Use this form to wish for a certain amount of random card packs!<br />You can only wish for a <u>minimum of 5 cards</u> and a <u>maximum of 10 cards</u>.</p>
		<form method="post" action="<?php echo $tcgurl; ?>games.php?play=wishes">
		<input type="hidden" name="type" value="3" />
		<input type="hidden" name="name" value="<?php echo $player; ?>" />
		<input type="number" name="amount" min="5" max="10" style="width:50%;" /> <input type="submit" name="random" value="Make a Wish!" class="btn-success" />
		</form>
	</div>

	<div id="color" align="center">
		<h2>Choice Color</h2><p>Use this form to wish for cards according to its deck color!<br />You can only wish for <u>one color</u> at a time for <u>3 cards</u>.</p>
		<form method="post" action="<?php echo $tcgurl; ?>games.php?play=wishes">
		<input type="hidden" name="type" value="4" />
		<input type="hidden" name="name" value="<?php echo $player; ?>" />
		<select name="colr" style="width:50%;">
		<?php
		$c = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_name` ASC");
		while( $cat = mysqli_fetch_assoc( $c ) )
		{
			echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_name'].'</option>';
		}
		?>
		</select> <input type="submit" name="color" value="Make a Wish!" class="btn-success" />
		</form>
	</div>

	<div id="release" align="center">
		<h2>Double Release</h2><p>Click the button to wish for a <u>double</u> deck release!<br />We can only grant twice the amount of regular release for now.</p>
		<form method="post" action="<?php echo $tcgurl; ?>games.php?play=wishes">
		<input type="hidden" name="type" value="5" />
		<input type="hidden" name="amount" value="2" />
		<input type="hidden" name="name" value="<?php echo $player; ?>" />
		<input type="submit" name="release" value="Make a Wish!" class="btn-success" />
		</form>
	</div>

	<div id="rewards" align="center">
		<h2>Game Rewards</h2><p>Choose a game set from the dropdown for <u>double rewards</u>!<br />We can only grant twice the amount of game rewards for now.</p>
		<form method="post" action="<?php echo $tcgurl; ?>games.php?play=wishes">
		<input type="hidden" name="type" value="6" />
		<input type="hidden" name="name" value="<?php echo $player; ?>" />
		<select name="set" style="width:50%;">
			<option value="Weekly">Weekly</option>
			<option value="Set A">Bi-weekly A</option>
			<option value="Set B">Bi-weekly B</option>
			<option value="Monthly">Monthly</option>
		</select> <input type="submit" name="rewards" value="Make a Wish!" class="btn-success" />
		</form>
	</div>
</div>
<?php
}
?>