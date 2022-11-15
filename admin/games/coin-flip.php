<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('coin-flip')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('coin-flip')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('coin-flip').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('coin-flip');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('coin-flip'); ?> - <?php echo $games->gameTitle('coin-flip'); ?></h1>
<?php echo $games->gameBlurb('coin-flip'); ?>
<center>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/coin-flip.js" type="text/javascript"></script>
	<form name="game">
		<table border="0">
		<tr>
			<td width="100" valign="middle" align="center"><a href="javascript:void(0);" onClick="playGame(1);"><img src="<?php echo $tcgurl; ?>admin/games/images/heads.png" title="heads" border=0></a><br /><b>Heads</b></td>
			<td width="50" valign="middle" align="center">OR</td>
			<td width="100" valign="middle" align="center"><a href="javascript:void(0);" onClick="playGame(2);"><img src="<?php echo $tcgurl; ?>admin/games/images/tails.png" title="tails" border=0></a><br /><b>Tails</b></td>
		</tr>
		<tr>
			<td colspan="3" align="center"><input type="text" name="msg"></td>
		</tr>
		</table>
	</form>
</center>

<?php
	}
}

else if( $go == "lost" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<center><b>'.$games->gameTitle('coin-flip').' - Tough Luck!</b>
		<p>Sorry, you didn\'t win! Please try your luck again next round. :D</p></center>';
		$today = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('coin-flip')."','".$games->gameTitle('coin-flip')."','You lost this game.','$today')");
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
		echo '<h1>'.$games->gameTitle('coin-flip').' - Prize Pickup</h1><center>
		<p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('coin-flip')."'");
		if( $getWish['wish_set'] == $games->gameSet('coin-flip') )
		{
			$choice = explode(", ", $games->gameChoiceArr('coin-flip'));
			$random = explode(", ", $games->gameRandArr('coin-flip'));
			$currency = explode(" | ", $games->gameCurArr('coin-flip'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('coin-flip') == '' )
			{
				$general->gamePrize($games->gameSet('coin-flip'),$games->gameTitle('coin-flip'),$games->gameSub('coin-flip'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('coin-flip'),$games->gameTitle('coin-flip'),'('.$games->gameSub('coin-flip').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('coin-flip');
			$rTotal = $games->gameRandArr('coin-flip');
			$mTotal = $games->gameCurArr('coin-flip');
			if( $games->gameSub('coin-flip') == '' )
			{
				$general->gamePrize($games->gameSet('coin-flip'),$games->gameTitle('coin-flip'),$games->gameSub('coin-flip'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('coin-flip'),$games->gameTitle('coin-flip'),'('.$games->gameSub('coin-flip').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>