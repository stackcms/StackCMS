<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

// Check is user is logged in
if( empty( $login ) )
{
	header("Location:account.php?do=login");
}


$query = "SELECT * FROM `tcg_cards` WHERE `card_status`='Active'";
switch( $play )
{
	case "black-jack" : include('admin/games/black-jack.php'); break;
	case "birthdays" : include('admin/games/birthdays.php'); break;
	case "card-claim" : include('admin/games/card-claim.php'); break;
	case "coin-flip" : include('admin/games/coin-flip.php'); break;
	case "freebies" : include('admin/games/freebies.php'); break;
	case "hangman-img" : include('admin/games/hangman-img.php'); break; // IMAGE TYPE
	case "hangman-txt" : include('admin/games/hangman-txt.php'); break; // TEXT TYPE
	case "higher-lower" : include('admin/games/higher-lower.php'); break;
	case "jan-ken-pon" : include('admin/games/jan-ken-pon.php'); break;
	case "lottery" : include('admin/games/lottery.php'); break;
	case "lucky-match" : include('admin/games/lucky-match.php'); break;
	case "melting-pot" : include('admin/games/melting-pot.php'); break;
	case "memory" : include('admin/games/memory.php'); break;
	case "peeptin" : include('admin/games/peeptin.php'); break;
	case "puzzle" : include('admin/games/puzzle.php'); break; // SINGLE, DIV TYPE
	case "reaction" : include('admin/games/reaction.php'); break;
	case "slots" : include('admin/games/slots.php'); break; // IMAGE TYPE
	case "slot-machine" : include('admin/games/slot-machine.php'); break; // TOKEN TYPE
	case "telepathy" : include('admin/games/telepathy.php'); break;
	case "tic-tac-toe" : include('admin/games/tic-tac-toe.php'); break;
	case "toggler" : include('admin/games/toggler.php'); break;
	case "treasure-hunt" : include('admin/games/treasure-hunt.php'); break;
	case "upcoming-vote" : include('admin/games/upcoming-vote.php'); break;
	case "vacation" : include('admin/games/vacation.php'); break;
	case "war" : include('admin/games/war.php'); break;
	case "wheels" : include('admin/games/wheels.php'); break;
	case "wishes" : include('admin/games/wishes.php'); break;
	case "motm" : include('admin/games/motm.php'); break;
	case "collaborate-img" : include('admin/games/collaborate-img.php'); break;
	case "collaborate-txt" : include('admin/games/collaborate-txt.php'); break;

	/* To add your password gate games, just copy the example case line and paste it below.
	 * Make sure to change the following according to yours:
	 * GAME-NAME - e.g. guess-the-character
	 */
	case "GAME-NAME" : include('admin/games/GAME-NAME.php'); break;

	default:
		// Change your game rules here
		echo '<h1>Interactive</h1>
		<table width="100%">
		<tr><td width="40%" valign="top" rowspan="2">
			<p>These are the sets of games available here at '.$tcgname.'. Each games has their own game plays but before proceeding, please read the guidelines first. All of the games below will be automatically logged on your permanent logs once you play and receive your rewards. Also, <u>DO NOT</u> refresh the reward pages unless stated otherwise.</p>

			<ol>
				<li>All passwords gates from the monthly set should be written in lowercase with no spaces, symbols and punctuation.</li>
				<li>Answers should be spelt the same as the decks of '.$tcgname.'.</li>
				<li>Do not type the numbers in words. (i.e. 10 instead of ten)</li>
				<li>All answers are [TCG THEME]-related, except for other games which will vary depends on the game problem.</li>
			</ol>

			<p>For some reason and the game help still doesn\'t work but you know you have the correct answer, it\'s time for you to contact us either via <a href="mailto:'.$tcgemail.'">direct mail</a> or <a href="https://discord.gg/'.$settings->getValue('tcg_discord').'" target="_blank">Discord</a>.</p>
		</td>
		<td width="1%" rowspan="2"></td>';
		$usrStat = isset( $row['usr_status'] ) ? $row['usr_status'] : null;
		if( $usrStat == "Hiatus" )
		{
			echo '<td width="59%" align="center"><h3>Access Denied</h3>';
			echo '<p>It looks like you haven\'t been active in the past two months and have been placed on the Hiatus list. In order to play games here, kindly contact '.$tcgowner.' to reactivate your account.</p></td>';
		}

		else
		{
			$w = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Weekly'");
			$b1 = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Set A'");
			$b2 = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Set B'");
			$m = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Monthly'");
			$timechk = date("Y-m", strtotime($m['gup_date']));
			$sc = $database->num_rows("SELECT COUNT(*) FROM `tcg_games_updater` WHERE `gup_set`='Special' AND YEAR(gup_date)='$timechk' AND MONTH(gup_date)='$timechk'");
			$s = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Special'");

			echo '<td width="19%" valign="top" align="center">
			<h3>Weekly ('.$w['gup_date'].')</h3>
			<div class="gameUpdate">';
			// Get list of weekly games
			$gW = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Weekly' AND `game_status`='Active' ORDER BY `game_title` ASC");
			while( $row = mysqli_fetch_assoc( $gW ) )
			{
				echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'">'.$row['game_title'].'</a>';
			}

			if( $settings->getValue( 'xtra_motm' ) == 0 || $settings->getValue( 'xtra_motm_scope' ) == "Week" || $settings->getValue( 'xtra_motm_vote' ) == 0 ) {}
			else
			{
				echo '<a href="'.$tcgurl.'games.php?play=motm-vote">'.$games->gameTitle('motm-vote').'</a>';
			}
			echo '</div>
			</td>
			<td width="1%"></td>
			<td width="19%" valign="top" align="center">';
			if( $b1['gup_date'] == $w['gup_date'] )
			{
				echo '<h3>Set A ('.$b1['gup_date'].')</h3>
				<div class="gameUpdate">';
				// Get list of bi-weekly A games
				$gSA = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Set A' AND `game_status`='Active' ORDER BY `game_title` ASC");
				while( $row = mysqli_fetch_assoc( $gSA ) )
				{
					echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'">'.$row['game_title'].'</a>';
				}
				echo '</div>';
			}

			else
			{
				echo '<h3>Set B ('.$b2['gup_date'].')</h3>
				<div class="gameUpdate">';
				// Get list of bi-weekly B games
				$gSB = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Set B' AND `game_status`='Active' ORDER BY `game_title` ASC");
				while( $row = mysqli_fetch_assoc( $gSB ) )
				{
					echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'">'.$row['game_title'].'</a>';
				}
				echo '</div>';
			}
			echo '</td>
			<td width="1%"></td>
			<td width="19%" valign="top" align="center">
				<h3>Monthly ('.$m['gup_date'].')</h3>
				<div class="gameUpdate">';
				// Get list of monthly games
				$gM = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Monthly' AND `game_status`='Active' ORDER BY `game_title` ASC");
				while( $row = mysqli_fetch_assoc( $gM ) )
				{
					echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'">'.$row['game_title'].'</a>';
				}

				if( $settings->getValue( 'xtra_motm' ) == 0 || $settings->getValue( 'xtra_motm_scope' ) == "Month" || $settings->getValue( 'xtra_motm_vote' ) == 0 ) {}
				else
				{
					echo '<a href="'.$tcgurl.'games.php?play=motm-vote">'.$games->gameTitle('motm-vote').'</a>';
				}
				echo '</div>
			</td>';
		}
		echo '</tr>
		<tr><td colspan="5" align="center">
			<h3>Special ('.$s['gup_date'].')</h3>
			<div class="gameUpdate2">';
			// Get list of special games
			$gS = $database->query("SELECT * FROM `tcg_games` WHERE `game_set`='Special' AND `game_status`='Active' ORDER BY `game_title` ASC");
			while( $row = mysqli_fetch_assoc( $gS ) )
			{
				echo '<a href="'.$tcgurl.'games.php?play='.$row['game_slug'].'">'.$row['game_title'].'</a>';
			}
			echo '</div>
		</td></tr>
		</table>';
} // end switch

@include($footer);
?>