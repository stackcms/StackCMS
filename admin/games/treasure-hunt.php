<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('treasure-hunt')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('treasure-hunt')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('treasure-hunt').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('treasure-hunt');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('treasure-hunt'); ?> - <?php echo $games->gameTitle('treasure-hunt'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('treasure-hunt'); ?>

<!-- Create your own map image! -->
<center>
	<img id="map" width="400" height="350" src="<?php echo $tcgurl; ?>admin/games/images/treasuremap.png">
	<p id="distance"></p>
</center>

<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/treasure-hunt.js" type="text/javascript"></script>

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
		echo '<h1>'.$games->gameTitle('treasure-hunt').' - Prize Pickup</h1>';
		echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('treasure-hunt')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'treasure-hunt' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('treasure-hunt'));
			$random = explode(", ", $games->gameRandArr('treasure-hunt'));
			$currency = explode(" | ", $games->gameCurArr('treasure-hunt'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('treasure-hunt') == '' )
			{
				$general->gamePrize($games->gameSet('treasure-hunt'),$games->gameTitle('treasure-hunt'),$games->gameSub('treasure-hunt'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('treasure-hunt'),$games->gameTitle('treasure-hunt'),'('.$games->gameSub('treasure-hunt').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('treasure-hunt');
			$rTotal = $games->gameRandArr('treasure-hunt');
			$mTotal = $games->gameCurArr('treasure-hunt');
			if( $games->gameSub('treasure-hunt') == '' )
			{
				$general->gamePrize($games->gameSet('treasure-hunt'),$games->gameTitle('treasure-hunt'),$games->gameSub('treasure-hunt'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('treasure-hunt'),$games->gameTitle('treasure-hunt'),'('.$games->gameSub('treasure-hunt').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>