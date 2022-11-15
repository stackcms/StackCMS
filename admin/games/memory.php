<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('memory')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('memory')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('memory').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('memory');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('memory'); ?> - <?php echo $games->gameTitle('memory'); ?></h1>
<?php echo $games->gameBlurb('memory'); ?>
<center>
	<div align="center" class="m-wrap">
		<div class="m-game"></div>
	</div>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/memory.js" type="text/javascript"></script>
</center>

<?php
	}
}

else if( $go == "prize" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		/* Blurb can be changed through the class.call.php file */
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('memory').' - Prize Pickup</h1>';
		echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('memory')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'memory' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('memory'));
			$random = explode(", ", $games->gameRandArr('memory'));
			$currency = explode(" | ", $games->gameCurArr('memory'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('memory') == '' )
			{
				$general->gamePrize($games->gameSet('memory'),$games->gameTitle('memory'),$games->gameSub('memory'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('memory'),$games->gameTitle('memory'),'('.$games->gameSub('memory').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('memory');
			$rTotal = $games->gameRandArr('memory');
			$mTotal = $games->gameCurArr('memory');
			if( $games->gameSub('memory') == '' )
			{
				$general->gamePrize($games->gameSet('memory'),$games->gameTitle('memory'),$games->gameSub('memory'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('memory'),$games->gameTitle('memory'),'('.$games->gameSub('memory').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>