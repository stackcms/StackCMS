<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('motm-vote')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('motm-vote')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('motm-vote').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('motm-vote');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('motm-vote'); ?> - <?php echo $games->gameTitle('motm-vote'); ?></h1>
<?php echo $games->gameBlurb('motm-vote'); ?>
<form method="post" action="<?php echo $tcgurl; ?>games.php?play=motm&go=prize">
<input type="hidden" name="id" value="<?php echo $row['usr_id']; ?>" />
	<center><table width="70%" cellpadding="0" class="table table-sliced table-striped">
	<tbody>
	<?php
	$select = $database->query("SELECT * FROM `user_list` WHERE `usr_name` != '$player' AND `usr_status`='Active' ORDER BY `usr_name` ASC");
	echo '<tr><td width="30%" align="right"><b>Member:</b></td>
	<td width="70%">
		<select name="vote" style="width:90%;">
			<option value="">---</option>';
			while( $row = mysqli_fetch_assoc( $select ) )
			{
				echo '<option value="'.$row['usr_name'].'">'.$row['usr_name'].'</option>';
			}
		echo '</select>
	</td>
	</tr>';
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
		$id = $sanitize->for_db($_POST['id']);
		$vote = $sanitize->for_db($_POST['vote']);
		$month = date("F", strtotime("now"));
		$user = $database->num_rows("SELECT * FROM `game_motm_list` WHERE `motm_name`='$vote'");
		if( $user != 0 )
		{
			$update = $database->query("UPDATE `game_motm_list` SET motm_vote=motm_vote+'1' WHERE motm_name='$vote'");
		}

		else
		{
			$insert = $database->query("INSERT INTO `game_motm_list` (`motm_name`,`motm_vote`) VALUES ('$vote','1')");
		}

		if( $update == TRUE || $insert == TRUE )
		{
			echo '<h1>'.$games->gameTitle('motm-vote').' : Prize Pickup</h1>';
			echo '<p>Thank you for voting an active member for the next month! Please take everything you see below:</p>
			<center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('motm-vote')."'");
			if( $getWish['wish_set'] == $games->gameSet( 'motm-vote' ) )
			{
				$choice = explode(", ", $games->gameChoiceArr('motm-vote'));
				$random = explode(", ", $games->gameRandArr('motm-vote'));
				$currency = explode(" | ", $games->gameCurArr('motm-vote'));
				foreach( $choice as $c ) { $cTotal = $c * 2; }
				foreach( $random as $r ) { $rTotal = $r * 2; }
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('motm-vote'),$games->gameTitle('motm-vote'),'('.$month.')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$cTotal = $games->gameChoiceArr('motm-vote');
				$rTotal = $games->gameRandArr('motm-vote');
				$mTotal = $games->gameCurArr('motm-vote');
				$general->gamePrize($games->gameSet('motm-vote'),$games->gameTitle('motm-vote'),'('.$month.')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			echo '<h1>'.$games->gameTitle('motm-vote').' : Error!</h1>
			<p>There was an error while processing your votes, please send us an email at <a href="mailto:'.$tcgemail.'">'.$tcgemail.'</a> as soon as possible. We apologize for the inconvenience!</p>';
		}
	}
}
?>