<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('slots')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('slots')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( $logDATE >= $ranDATE )
{
	echo '<h1>'.$games->gameTitle('slots').' : Halt!</h1>
	<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('slots');
		echo '</center>';
}

else
{
?>

<h1><?php echo $games->gameSet('slots'); ?> - <?php echo $games->gameTitle('slots'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('slots'); ?>
<center>
<?php
// Change to your own slots image name (each must be divided by 3)
$slot = array("tiger","pigeon","turtle","dog");

$one = $slot[array_rand($slot,1)];
$two = $slot[array_rand($slot,1)];
$three = $slot[array_rand($slot,1)];

echo '<img src="'.$tcgurl.'admin/games/images/' . $one .'01.jpg">';
echo '<img src="'.$tcgurl.'admin/games/images/' . $two .'02.jpg">';
echo '<img src="'.$tcgurl.'admin/games/images/' . $three .'03.jpg">';

echo '<br><br><input type="button" value="  Fix the Photos  " onClick="window.location.reload()">';
?>
</center>

<?php
	if( $one == $two && $one == $three )
	{
		echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('slots')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'slots' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('slots'));
			$random = explode(", ", $games->gameRandArr('slots'));
			$currency = explode(" | ", $games->gameCurArr('slots'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('slots'),$games->gameTitle('slots'),'('.$games->gameSub('slots').')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('slots');
			$rTotal = $games->gameRandArr('slots');
			$mTotal = $games->gameCurArr('slots');
			$general->gamePrize($games->gameSet('slots'),$games->gameTitle('slots'),'('.$games->gameSub('slots').')',$rTotal,$cTotal,$mTotal);
		}
	}

	else
	{
		echo "<center>Your prize will appear here when you have three matching images.</center>";
	}
}
?>