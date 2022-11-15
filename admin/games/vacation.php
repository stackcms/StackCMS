<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('vacation')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('vacation')."' AND `log_date` >= '".$range['gup_date']."'");

$logSUBT = isset($logChk['log_subtitle']) ? $logChk['log_subtitle'] : null;
$logTEXT = isset($logChk['log_rewards']) ? $logChk['log_rewards'] : null;
$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		if( $logSUBT == "(Lost)" )
		{
			echo '<h1>'.$games->gameTitle('vacation').' : Halt!</h1>
			<center><p>'.$logTEXT.'</p></center>';
		}

		else
		{
			echo '<h1>'.$games->gameTitle('vacation').' : Halt!</h1>
			<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
			$general->displayRewards('vacation');
			echo '</center>';
		}
	}

	else
	{
?>

<h1><?php echo $games->gameSet('vacation'); ?> - <?php echo $games->gameTitle('vacation'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('vacation'); ?>
<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/vacation.js" type="text/javascript"></script>
<center><form name="vacation">
<table width="60%" class="table table-sliced table-striped">
<tbody><tr><td><input type="text" name="hint" placeholder="Enter your guess below and click Guess!" readonly style="width:93%;" /></td></tr>
<tr><td><input type="text" name="answer" title="Enter your guess here." style="width:93%;" /></td></tr>
<tr><td align="center">
	<input type="button" value=" Guess! " onClick="guessit()" class="btn-success" title="Click here to get a hint or check your guess." />
	<input type="button" value=" Clear " onClick="clearBox()" class="btn-danger" title="Click here to clear the text box." />
</td></tr>
</tbody></table>
</form></center>

<?php
	}
}

else if( $go == "prize" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('vacation').' - Prize Pickup</h1>';
		echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('vacation')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'vacation' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('vacation'));
			$random = explode(", ", $games->gameRandArr('vacation'));
			$currency = explode(" | ", $games->gameCurArr('vacation'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('vacation') == '' )
			{
				$general->gamePrize($games->gameSet('vacation'),$games->gameTitle('vacation'),$games->gameSub('vacation'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('vacation'),$games->gameTitle('vacation'),'('.$games->gameSub('vacation').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('vacation');
			$rTotal = $games->gameRandArr('vacation');
			$mTotal = $games->gameCurArr('vacation');
			if( $games->gameSub('vacation') == '' )
			{
				$general->gamePrize($games->gameSet('vacation'),$games->gameTitle('vacation'),$games->gameSub('vacation'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('vacation'),$games->gameTitle('vacation'),'('.$games->gameSub('vacation').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}

else
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('vacation').' - Tough Luck!</h1>';
		echo '<center><p>Shoot, you guessed it wrong! Try again on the next round.</p></center>';
		$today = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('vacation')."','".$games->gameTitle('vacation')."','(Lost)','You lost this game.','$today')");
	}
}
?>