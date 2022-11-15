<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('war')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('war')."' AND `log_date` >= '".$range['gup_date']."'");
$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active'");

$logSUBT = isset($logChk['log_subtitle']) ? $logChk['log_subtitle'] : null;
$logTEXT = isset($logChk['log_rewards']) ? $logChk['log_rewards'] : null;
$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( $logDATE >= $ranDATE )
{
	if( $logSUBT == "(Lost)" )
	{
		echo '<h1>'.$games->gameTitle('war').' : Halt!</h1>
		<center><p>'.$logTEXT.'</p></center>';
	}

	else
	{
		echo '<h1>'.$games->gameTitle('war').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('war');
		echo '</center>';
	}
}

else
{
?>

<h1><?php echo $games->gameSet('war'); ?> - <?php echo $games->gameTitle('war'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('war'); ?>
<?php 
	$min = 1; $max = mysqli_num_rows($query);
	for( $i=0; $i<1; $i++ )
	{
		mysqli_data_seek($query,rand($min,$max)-1);
		$row = mysqli_fetch_assoc($query);
		$digits = rand(01,$row['card_count']);
		if( $digits < 10 )
		{
			$digit = "0$digits";
		}

		else
		{
			$digit = $digits;
		}
		$computer = $row['card_filename'].''.$digit;
	}

	$min = 1; $max = mysqli_num_rows($query);
	for( $i=0; $i<1; $i++ )
	{
		mysqli_data_seek($query,rand($min,$max)-1);
		$row2 = mysqli_fetch_assoc($query);
		$digits2 = rand(01,$row2['card_count']);
		if( $digits2 < 10 )
		{
			$digit2 = "0$digits2";
		}

		else
		{
			$digit2 = $digits2;
		}
		$you = $row2['card_filename'].''.$digit2;
	}
	echo '<center>
	<table width="40%" class="border" cellspacing="3">
	<tr><td width="20%" align="center"><img src="/admin/games/images/computer.gif"></td><td width="20%" align="center"><img src="/admin/games/images/player.gif"></td></tr>
	<tr><td class="headLine">Computer</td><td class="headLine">You</td></tr>
	<tr>
		<td class="tableGame" align="center"><img src="'.$tcgcards.''.$computer.'.png" border="0" /><br /><b>'.$digits.'</b></td>
		<td class="tableGame" align="center"><img src="'.$tcgcards.''.$you.'.png" border="0" /><br /><b>'.$digits2.'</b></td>
	</tr>
	</table></center><br />';
	if( $digits2 <= $digits )
	{
		echo '<center><b>'.$games->gameTitle('war').' - Tough Luck!</b>
		<p>Sorry, you didn\'t win! Please try your luck again next week. :D</p></center>';
		$today = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('war')."','".$games->gameTitle('war')."','(Lost)','You lost this game.','$today')");
	}

	if( $digits2 > $digits )
	{
		echo '<center><h3>'.$games->gameTitle('war').' - Prize Pickup</h3>
		<p>Congratulations, you won the game! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('war')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'war' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('war'));
			$random = explode(", ", $games->gameRandArr('war'));
			$currency = explode(" | ", $games->gameCurArr('war'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('war') == '' )
			{
				$general->gamePrize($games->gameSet('war'),$games->gameTitle('war'),$games->gameSub('war'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('war'),$games->gameTitle('war'),'('.$games->gameSub('war').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('war');
			$rTotal = $games->gameRandArr('war');
			$mTotal = $games->gameCurArr('war');
			if( $games->gameSub('war') == '' )
			{
				$general->gamePrize($games->gameSet('war'),$games->gameTitle('war'),$games->gameSub('war'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('war'),$games->gameTitle('war'),'('.$games->gameSub('war').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>