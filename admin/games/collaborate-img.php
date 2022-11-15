<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('collaborate-img')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('collaborate-img')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

// Process new themed deck creation
if( isset( $_POST['new-themed'] ) )
{
	$deck = $sanitize->for_db($_POST['deck']);
	$count = intval($_POST['count']);
	$break = intval($_POST['break']);
	$deadline = $_POST['date'];
	if( $deck === '' || $deck == 'deck' )
	{
		$error[] = 'Deck name must be defined.';
	}

	else if( $count === '' || $deck == 'count' )
	{
		$error[] = 'Card count must be defined.';
	}

	else if( $break === '' || $deck == 'break' )
	{
		$error[] = 'Break field must be defined. Set it to 0 if you don\'t want line breaks.';
	}

	else
	{
		if( !isset( $error ) )
		{
			$result = $database->query("INSERT INTO `tcg_cards_themed` (`thm_deck`,`thm_count`,`thm_break`,`thm_deadline`) VALUE ('$deck','$count','$break','$deadline')");
			if( !$result )
			{
				$error[] = "Failed to add the themed deck.";
			}

			else
			{
				$success[] = "The new themed deck has been added.";
			}
		}
	}
}

// Process rewards after submitting an image
if( isset( $_POST['submit'] ) )
{
	$catid = intval($_POST['id']);
	$card = $sanitize->for_db($_POST['card']);
	$donator = $sanitize->for_db($_POST['donator']);
	$image = $sanitize->for_db($_POST['image']);
	$feat = $sanitize->for_db($_POST['feature']);
	$series = $sanitize->for_db($_POST['series']);

	$deckinfo = $database->get_assoc("SELECT * FROM `tcg_cards_themed` WHERE `thm_id`='$catid'");
	$deck = $deckinfo['deck'];

	$date = date("Y-m-d", strtotime("now"));

	if( $card !== '' )
	{
		$card = explode(',', $card);
		$donator = explode(',', $donator);
		$image = explode(',', $image);
		$feat = explode(',', $feat);
		$series = explode(',', $series);
		function adddeck( &$value, $key )
		{
			$value = trim($value);
			$value = ''.$value.'';
		}
		array_walk($card,'adddeck');

		if( empty( $deckinfo['thm_cards'] ) && empty( $deckinfo['thm_donator'] ) && empty( $deckinfo['thm_image'] ) && empty( $deckinfo['thm_feature'] ) && empty( $deckinfo['thm_series'] ) )
		{
			$c = implode(', ',$card);
			$d = implode(', ',$donator);
			$i = implode(', ',$image);
			$f = implode(', ',$feat);
			$s = implode(', ',$series);
		}

		else
		{
			$card = implode(', ',$card);
			$donator = implode(', ',$donator);
			$image = implode(', ',$image);
			$feat = implode(', ',$feat);
			$series = implode(', ',$series);
			$c = $deckinfo['thm_cards'].', '.$card;
			$d = $deckinfo['thm_donator'].', '.$donator;
			$i = $deckinfo['thm_image'].', '.$image;
			$f = $deckinfo['thm_feature'].', '.$feature;
			$s = $deckinfo['thm_series'].', '.$series;
		}
	}

	$result = $database->query("UPDATE `tcg_cards_themed` SET `thm_cards`='$c',`thm_donator`='$d',`thm_image`='$i',`thm_feature`='$f',`thm_series`='$s' WHERE `thm_id`='$catid' LIMIT 1");
	if( !$result )
	{
		$error[] = "Failed to update the deck. ".mysqli_error($result)."";
	}

	else
	{
		if( !isset( $_SERVER['HTTP_REFERER'] ) )
		{
			/* Blurb can be changed through the class.call.php file */
			echo $ForbiddenAccess;
		}

		else
		{
			echo '<h1>'.$games->gameTitle('collaborate-img').' - Prize Pickup</h1>';
			echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('collaborate-img')."'");
			if( $getWish['wish_set'] == $games->gameSet( 'collaborate-img' ) )
			{
				$choice = explode(", ", $games->gameChoiceArr('collaborate-img'));
				$random = explode(", ", $games->gameRandArr('collaborate-img'));
				$currency = explode(" | ", $games->gameCurArr('collaborate-img'));
				foreach( $choice as $c ) { $cTotal = $c * 2; }
				foreach( $random as $r ) { $rTotal = $r * 2; }
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('collaborate-img'),$games->gameTitle('collaborate-img'),'('.$deckinfo['thm_deck'].')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$cTotal = $games->gameChoiceArr('collaborate-img');
				$rTotal = $games->gameRandArr('collaborate-img');
				$mTotal = $games->gameCurArr('collaborate-img');
				$general->gamePrize($games->gameSet('collaborate-img'),$games->gameTitle('collaborate-img'),'('.$deckinfo['thm_deck'].')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}

// Process themed deck deletion
if( $act == "delete" && isset( $_GET['cat'] ) )
{
	$catid = intval($_GET['cat']);	
	$exists = $database->num_rows("SELECT * FROM `tcg_cards_themed` WHERE `thm_id`='$catid'");

	if( $exists === 1 )
	{
		$result  = $database->query("DELETE FROM `tcg_cards_themed` WHERE `thm_id` = '$catid' LIMIT 1");
		if( !$result )
		{
			$error[] = "There was an error while attempting to remove the themed deck. ".mysqli_error($result)."";
		}

		else
		{
			$success[] = "The themed deck and containing cards have been removed.";
		}
	}

	else
	{
		$error[] = "The set no longer exists.";
	}
}



// Show themed deck page
echo '<h1>'.$games->gameSet('collaborate-img').' - '.$games->gameTitle('collaborate-img').'</h1>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="49%" valign="top">';
		echo $games->gameBlurb('collaborate-img');
		echo '<br /><br /><center>';

		// Show add new collaboration deck form
		if( $memrole == "1" )
		{
			echo '<button id="s1" class="btn-success">Add New Themed Deck</button><br /><br />
			<span id="l1" class="slideable">
			<form action="'.$tcgurl.'games.php?play=collaborate-img" method="post">
				<table width="100%" cellspacing="3" class="border">
				<tr>
					<td width="10%" class="headLine">Deck Name:</td>
					<td width="90%" class="tableBody" colspan="5"><input name="deck" id="deck" type="text" style="width:98%;" /></td>
				</tr>
				<tr>
					<td width="10%" class="headLine">Count:</td>
					<td width="12%" class="tableBody"><input name="count" id="count" type="number" value="20" style="width:97%;"></td>
					<td width="10%" class="headLine">Break:</td>
					<td width="12%" class="tableBody"><input name="break" id="break" type="number" value="5" style="width:97%;"></td>
				</tr>
				<tr>
					<td class="headLine">Deadline:</td>
					<td colspan="3" class="tableBody">
						<input type="date" name="date"> 
						<input type="submit" name="new-themed" id="new-themed" class="btn-success" value="Add Deck" /> 
						<input type="reset" name="reset" id="reset" class="btn-danger" value="Reset" />
					</td>
				</tr>
				</table>
			</form>
			</span>';
		}

		// Show deck preview and submission form
		if( isset( $error ) )
		{
			foreach( $error as $msg )
			{
				echo '<div class="box-error"><b>Error!</b> '.$msg.'</div>';
			}
		}

		if( isset( $success ) )
		{
			foreach( $success as $msg )
			{
				echo '<div class="box-success"><b>Success!</b> '.$msg.'</div>';
			}
		}
		echo '</center>
	</td>

	<td width="2%"></td>

	<td width="49%" valign="top">';
		function trim_value(&$value) { $value = trim($value); }
		$res = $database->query("SELECT * FROM `tcg_cards_themed` WHERE `thm_completed`='0' ORDER BY `thm_deck`");
		while( $col = mysqli_fetch_assoc( $res ) )
		{
			$data = array();
			$cards = array();

			if( $col['thm_cards'] != '' )
			{
				$cards = explode(',', $col['thm_cards']);
				array_walk($cards, 'trim_value');
				$count = count($cards);

				$donator = explode(',', $col['thm_donator']);
				$image = explode(',', $col['thm_image']);
				$feat = explode(',', $col['thm_feature']);
				$series = explode(',', $col['thm_series']);
				foreach( $cards as $key => $card )
				{
					$data[$card] = array(
						'user' => trim($donator[$key]),
						'img' => trim($image[$key]),
						'feat' => trim($feat[$key]),
						'series' => trim($series[$key])
					);
				}
			}

			echo '<h2>'.$col['thm_deck'].' (';
			if( empty( $col['thm_cards'] ) )
			{
				echo '0';
			}

			else
			{
				echo $count;
			}
			echo ' / '.$col['thm_count'].')</h2>
			<p>Donation period will end on <b>'.date("F d, Y", strtotime($col['thm_deadline'])).'</b> at <b>11:59PM '.date("T", strtotime( $settings->getValue( 'tcg_timezone' ) )).'</b>! ';

			if( $memrole == "1" )
			{
				echo '<button class="btn-danger" onclick="location.href=\''.$tcgurl.'games.php?play=collaborate-img&action=delete&cat='.$col['thm_id'].'\'">Delete this deck</button>';
			}

			echo '</p>

			<center>
			<table width="'.$settings->getValue( 'cards_size_width' ) * $col['thm_break'].'" cellspacing="0" cellpadding="0" border="0">
			<tr>';
			$height = $settings->getValue( 'cards_size_height' ) + 4;
			for( $i = 1; $i <= $col['thm_count']; $i++ )
			{
				if( !in_array($i, $cards) )
				{
					if( $i < 10 ) { $digit = '0'.$i; }
					else { $digit = $i; }
					echo '<td width="'.$settings->getValue( 'cards_size_width' ).'" align="center" height="'.$height.'" background="'.$tcgcards.'filler'.$digit.'.png"></td>';
				}

				else
				{
					if( $i < 10 ) { $digit = '0'.$i; }
					else { $digit = $i; }
					echo '<td width="'.$settings->getValue( 'cards_size_width' ).'" align="center" height="'.$height.'" background="'.$tcgcards.'pending'.$digit.'.png"><a href="'.$data[$i]['img'].'" target="_blank" title="'.$data[$i]['feat'].' of '.$data[$i]['series'].'">'.$data[$i]['user'].'</a></td>';
				}

				if( $col['thm_break'] !== '0' && $i % $col['thm_break'] == 0 )
					echo '</tr>';
			}
			echo '</table></center>
			<br />';

			// Check if user already donated an image
			if( $logDATE >= $ranDATE )
			{
				echo '<h3>'.$games->gameTitle('collaborate-img').' : Halt!</h3>
				<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
				$general->displayRewards('collaborate-img');
				echo '</center>';
			}

			// Show form to add image donation
			else
			{
				echo '<center>
				<form action="'.$tcgurl.'games.php?play=collaborate-img" method="post">
				<input name="id" type="hidden" value="'.$col['thm_id'].'">
				<input type="hidden" name="donator" id="donator" value="'.$player.'" />
				<table width="100%" cellspacing="3" class="border">
				<tr>
					<td width="20%" class="headLine">Card #:</td>
					<td width="80%" class="tableBody"><input name="card" id="card" type="number" placeholder="1, not 01" style="width:98%;"></td>
				</tr>
				<tr>
					<td class="headLine">Image URL:</td>
					<td class="tableBody"><input name="image" id="image" type="text" placeholder="http://" style="width:98%;"></td>
				</tr>
				<tr>
					<td class="headLine">Feature:</td>
					<td class="tableBody"><input name="feature" id="feature" type="text" placeholder="e.g. Mamoru Chiba" style="width:98%;"></td>
				</tr>
				<tr>
					<td class="headLine">Series:</td>
					<td class="tableBody"><input name="series" id="series" type="text" placeholder="e.g. Bishoujo Senshi Sailor Moon" style="width:98%;"></td>
				</tr>
				<tr>
					<td width="10%" class="tableBody" colspan="4" align="center">
						<input type="submit" name="submit" id="submit" class="btn-success" value="Donate" />';
					echo '</td>
				</tr>
				</table>
				</form></center>';
			}
		}
	echo '</td>
</tr>
</table>';
?>