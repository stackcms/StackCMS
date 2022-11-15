<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('puzzle')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('puzzle')."' AND `log_date` >= '".$range['gup_date']."'");

/* Create at least 10 puzzle images to randomize and name them as puzzle01, puzzle02... */
$array = array("01","02","03","04","05","06","07","08","09","10");
$rand = array_rand($array);
$Puzzle = $array[$rand];

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('puzzle').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('puzzle');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('puzzle'); ?> - <?php echo $games->gameTitle('puzzle'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('puzzle'); ?>

<p align="center">
<!-- CHANGE PUZZLE JS ACCORDING TO YOUR LAYOUT -->
<script><?php include($tcgpath.'admin/games/js/puzzle-div.js'); ?></script>
<canvas id="canvas"></canvas>
</p>

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
		echo '<h1>'.$games->gameTitle('puzzle').' - Prize Pickup</h1><center>
		<p>The puzzle has been fixed! Thank you and please take your rewards below:</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('puzzle')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'puzzle' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('puzzle'));
			$random = explode(", ", $games->gameRandArr('puzzle'));
			$currency = explode(" | ", $games->gameCurArr('puzzle'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('puzzle') == '' )
			{
				$general->gamePrize($games->gameSet('puzzle'),$games->gameTitle('puzzle'),$games->gameSub('puzzle'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('puzzle'),$games->gameTitle('puzzle'),'('.$games->gameSub('puzzle').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('puzzle');
			$rTotal = $games->gameRandArr('puzzle');
			$mTotal = $games->gameCurArr('puzzle');
			if( $games->gameSub('puzzle') == '' )
			{
				$general->gamePrize($games->gameSet('puzzle'),$games->gameTitle('puzzle'),$games->gameSub('puzzle'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('puzzle'),$games->gameTitle('puzzle'),'('.$games->gameSub('puzzle').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>