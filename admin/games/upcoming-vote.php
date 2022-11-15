<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('upcoming-vote')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('upcoming-vote')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('upcoming-vote').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('upcoming-vote');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('upcoming-vote'); ?> - <?php echo $games->gameTitle('upcoming-vote'); ?></h1>
<?php echo $games->gameBlurb('upcoming-vote'); ?>
<form method="post" action="<?php echo $tcgurl; ?>games.php?play=upcoming-vote&go=prize">
<input type="hidden" name="id" value="<?php echo $row['usr_id']; ?>" />
	<center><table width="70%" cellpadding="0" class="table table-sliced table-striped">
	<tbody>
	<?php
	$c = $database->num_rows("SELECT * FROM `tcg_cards_set`");
	for( $i=1; $i<=$c; $i++ )
	{
		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='$i'");
		$counts = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_set`='".$cat['set_id']."' AND `card_status`='Upcoming'");
		$select = $database->query("SELECT * FROM `tcg_cards` WHERE `card_set`='".$cat['set_id']."' AND `card_status`='Upcoming' ORDER BY `card_set` ASC, `card_filename` ASC");
		if( $counts == "0" ) { }
		else
		{
			echo '<tr><td width="30%" align="right"><b>'.$cat['set_name'].':</b></td>
				<td width="70%">
					<select name="vote'.$i.'" style="width:90%;">
						<option value="">---</option>';
						while( $row = mysqli_fetch_assoc( $select ) )
						{
							echo '<option value="'.$row['card_filename'].'">'.$row['card_deckname'].'</option>';
						}
					echo '</select>
				</td>
			</tr>';
		}
	}
	?>
	</tbody></table>
	<input type="submit" name="submit" class="btn-success" value="Send Votes"> 
	<input type="reset" name="reset" class="btn-danger" value="Reset Votes">
	</center>
</form>

<?php
	}
}

else
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		echo '<p>You did not press the submit button; this page should not be accessed directly.</p>';
	}

	else
	{
		$c = $database->num_rows("SELECT * FROM `tcg_cards_cat`");
		for( $i=1; $i<=$c; $i++ )
		{
			$card = "vote$i";
			$id = $sanitize->for_db($_POST['id']);
			$vote = $sanitize->for_db($_POST[$card]);
			$update = $database->query("UPDATE `tcg_cards` SET card_votes=card_votes+'1' WHERE card_filename='$vote'");
		}

		if( $update == TRUE )
		{
			echo '<h1>'.$games->gameTitle('upcoming-vote').' : Prize Pickup</h1>';
			echo '<p>Thank you for voting decks for the future releases! This will help us decide which deck to release next week! Please take everything you see below:</p>
			<center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('upcoming-vote')."'");
			if( $getWish['wish_set'] == $games->gameSet( 'upcoming-vote' ) )
			{
				$choice = explode(", ", $games->gameChoiceArr('upcoming-vote'));
				$random = explode(", ", $games->gameRandArr('upcoming-vote'));
				$currency = explode(" | ", $games->gameCurArr('upcoming-vote'));
				foreach( $choice as $c ) { $cTotal = $c * 2; }
				foreach( $random as $r ) { $rTotal = $r * 2; }
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				if( $games->gameSub('upcoming-vote') == '' )
				{
					$general->gamePrize($games->gameSet('upcoming-vote'),$games->gameTitle('upcoming-vote'),$games->gameSub('upcoming-vote'),$rTotal,$cTotal,$mTotal);
				}
				else
				{
					$general->gamePrize($games->gameSet('upcoming-vote'),$games->gameTitle('upcoming-vote'),'('.$games->gameSub('upcoming-vote').')',$rTotal,$cTotal,$mTotal);
				}
			}

			else
			{
				$cTotal = $games->gameChoiceArr('upcoming-vote');
				$rTotal = $games->gameRandArr('upcoming-vote');
				$mTotal = $games->gameCurArr('upcoming-vote');
				if( $games->gameSub('upcoming-vote') == '' )
				{
					$general->gamePrize($games->gameSet('upcoming-vote'),$games->gameTitle('upcoming-vote'),$games->gameSub('upcoming-vote'),$rTotal,$cTotal,$mTotal);
				}
				else
				{
					$general->gamePrize($games->gameSet('upcoming-vote'),$games->gameTitle('upcoming-vote'),'('.$games->gameSub('upcoming-vote').')',$rTotal,$cTotal,$mTotal);
				}
			}
		}

		else
		{
			echo '<h1>'.$games->gameTitle('upcoming-vote').' : Error!</h1>
			<p>There was an error while processing your votes, please send us an email at <a href="mailto:'.$tcgemail.'">'.$tcgemail.'</a> as soon as possible. We apologize for the inconvenience!</p>';
		}
	}
}
?>