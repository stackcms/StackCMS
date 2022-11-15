<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('freebies')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('freebies')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('freebies').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('freebies');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('freebies'); ?> - <?php echo $games->gameTitle('freebies'); ?></h1>
<?php echo $games->gameBlurb('freebies'); ?>
<p align="center">
	<a href="<?php echo $tcgurl; ?>games.php?play=freebies&go=prize"><img src="<?php echo $tcgurl; ?>admin/games/images/gift.gif" border="0"></a>
</p>

<?php
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
		echo '<h1>'.$games->gameTitle('freebies').' - Prize Pickup</h1><center>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('freebies')."'");
		if( $getWish['wish_set'] == $games->gameSet('freebies') )
		{
			$choice = explode(", ", $games->gameChoiceArr('freebies'));
			$random = explode(", ", $games->gameRandArr('freebies'));
			$currency = explode(" | ", $games->gameCurArr('freebies'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('freebies') == '' )
			{
				$general->gamePrize($games->gameSet('freebies'),$games->gameTitle('freebies'),$games->gameSub('freebies'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('freebies'),$games->gameTitle('freebies'),'('.$games->gameSub('freebies').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('freebies');
			$rTotal = $games->gameRandArr('freebies');
			$mTotal = $games->gameCurArr('freebies');
			if( $games->gameSub('freebies') == '' )
			{
				$general->gamePrize($games->gameSet('freebies'),$games->gameTitle('freebies'),$games->gameSub('freebies'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('freebies'),$games->gameTitle('freebies'),'('.$games->gameSub('freebies').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>