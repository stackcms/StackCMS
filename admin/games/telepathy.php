<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('telepathy')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('telepathy')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('telepathy').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('telepathy');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('telepathy'); ?> - <?php echo $games->gameTitle('telepathy'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('telepathy'); ?>
<p>In the free textbox below, type in your guessed number and press the "Guess!" button. If you guessed wrong, the box on the left will display a hint. Using those clues, continue guessing until you see a message that says you won!</p>
<center>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/telepathy.js" type="text/javascript"></script>
	<form onSubmit="" NAME="guessquiz">
		<input type="text" name="prompt" value="The number I'm thinking of is 1-100!" size="50" /> <input type="text" size="5" name="guess" /><br/>
		<input type="button" value=" Guess! " onClick="process(guessme)" />
	</form>
</center>

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
		echo '<h1>'.$games->gameTitle('telepathy').' - Prize Pickup</h1>
		<center><p>Congrats, you found them! Take everything you see blow and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('telepathy')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'telepathy' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('telepathy'));
			$random = explode(", ", $games->gameRandArr('telepathy'));
			$currency = explode(" | ", $games->gameCurArr('telepathy'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('telepathy') == '' )
			{
				$general->gamePrize($games->gameSet('telepathy'),$games->gameTitle('telepathy'),$games->gameSub('telepathy'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('telepathy'),$games->gameTitle('telepathy'),'('.$games->gameSub('telepathy').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('telepathy');
			$rTotal = $games->gameRandArr('telepathy');
			$mTotal = $games->gameCurArr('telepathy');
			if( $games->gameSub('telepathy') == '' )
			{
				$general->gamePrize($games->gameSet('telepathy'),$games->gameTitle('telepathy'),$games->gameSub('telepathy'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('telepathy'),$games->gameTitle('telepathy'),'('.$games->gameSub('telepathy').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>