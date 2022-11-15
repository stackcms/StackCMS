<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('slot-machine')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('slot-machine')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('slot-machine').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('slot-machine');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('slot-machine'); ?> - <?php echo $games->gameTitle('slot-machine'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('slot-machine'); ?>
<p>Place your bet on the box below the tokens then hit "Spin". You already have 25 tokens to start up the game. Continue to spin the slot machine until you gain more than <b>30 tokens</b> then hit "Redeem" to claim your winnings. Do keep in mind that your starter tokens are not included to the required 30 winning tokens!</p>
<center>
	<form name=slots onsubmit="rollem(); return false;">
	<table width="100%" cellspacing="3" border="0">
		<tr>
			<td width="40%" valign="top" align="center">
				<table border="0" cellpadding="0" cellspacing="5" width="350" class="border">
					<tr><td class="headLine">Tokens:</td><td align="center" class="tableBody"><input type=box size="5" name=token READONLY value="25"></td></tr>
					<tr><td class="headLine">Your bet:</td><td align="center" class="tableBody"><input type=box size="5" name=bet></td></tr>
					<tr><td colspan="2" align="center" class="tableBody"><input type="submit" value="Spin" class="btn-success"> <input type="button" value="Redeem" class="btn-primary" onclick="stopplay();"></td></tr>
					<tr><td colspan="2" align="center" class="tableBody">
						<p><img src="<?php echo $tcgurl; ?>admin/games/images/sm01.png" name="slot1">
							<img src="<?php echo $tcgurl; ?>admin/games/images/sm02.png" name="slot2">
							<img src="<?php echo $tcgurl; ?>admin/games/images/sm03.png" name="slot3"><br />
							<input type=text readonly size="33" name=banner></p>
					</td></tr>
				</table>
			</td>
			<td width="50%" valign="top" align="center">
				<table width="400" border="0" class="border">
					<tr><td colspan="3" class="headLine">Payouts</td></tr>
					<tr>
						<td class="headLine">3 of a kind</td>
						<td class="tableBody" align="center"><img src="<?php echo $tcgurl; ?>admin/games/images/sm02.png" width="50"><img src="/admin/games/images/sm02.png" width="50"><img src="/admin/games/images/sm02.png" width="50"></td>
						<td class="tableBody" align="center">10x your bet</td>
					</tr>
					<tr>
						<td class="headLine">A pair</td>
						<td class="tableBody" align="center"><img src="<?php echo $tcgurl; ?>admin/games/images/sm05.png" width="50"><img src="/admin/games/images/sm05.png" width="50"><img src="/admin/games/images/sm01.png" width="50"></td>
						<td class="tableBody" align="center">2x your bet</td>
					</tr>
					<tr>
						<td class="headLine">No match</td>
						<td class="tableBody" align="center"><img src="<?php echo $tcgurl; ?>admin/games/images/sm03.png" width="50"><img src="/admin/games/images/sm04.png" width="50"><img src="/admin/games/images/sm06.png" width="50"></td>
						<td class="tableBody" align="center">You lose bet</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</form>
</center>
<script><?php include($tcgpath.'/admin/games/js/slot-machine.js'); ?></script>

<?php
	}
}

else if( $go == 0 || $go <= 29 )
{
	echo '<h1>'.$games->gameTitle('slot-machine').' - No Enough Tokens</h1>
	<p>It seems that you didn\'t gain any tokens or you haven\'t played the slot machine, please go back and earn some tokens.</p>';
}

else if( $go >= 30 )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('slot-machine').' - Prize Pickup</h1>';
		echo '<center><p>Congrats, claim your reward for winning more than 30 tokens! Take everything you see below:</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('slot-machine')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'slot-machine' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('slot-machine'));
			$random = explode(", ", $games->gameRandArr('slot-machine'));
			$currency = explode(" | ", $games->gameCurArr('slot-machine'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('slot-machine'),$games->gameTitle('slot-machine'),'('.$games->gameSub('slot-machine').')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('slot-machine');
			$rTotal = $games->gameRandArr('slot-machine');
			$mTotal = $games->gameCurArr('slot-machine');
			$general->gamePrize($games->gameSet('slot-machine'),$games->gameTitle('slot-machine'),'('.$games->gameSub('slot-machine').')',$rTotal,$cTotal,$mTotal);
		}
	}
}

else
{
	echo '<h1>'.$games->gameTitle('slot-machine').' - Try Again</h1>
	<p>Sorry, you didn\'t gain enough tokens to receive a reward. Please go back and try again.</p>';
}
?>