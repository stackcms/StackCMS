<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('black-jack')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('black-jack')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('black-jack').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('black-jack');
		echo '</center>';
	}

	else
	{
?>
<h1><?php echo $games->gameSet('black-jack'); ?> - <?php echo $games->gameTitle('black-jack'); ?></h1>
<?php echo $games->gameBlurb('black-jack'); ?>
<center>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/black-jack.js" type="text/javascript"></script>
	<form name="display">
		<table border="0" cellspacing="0" cellpadding="3">
		<tr>
			<td><center>Score: <input type=text name="numgames" size="3" value="0"></center></td>
			<td><center>Dealer</center></td>
			<td><center><input type=text name="dealer" size="2"></center></td>
			<td><center>Card(s):  <input type=text name="say1" size="18" value=""></center></td>
		</tr>
		<tr>
			<td><center></center></td>
			<td><center>Player</center></td>
			<td><center><input type=text name="you" size="2"></center></td>
			<td><center>Card(s):  <input type=text name="say2" size="18" value=""></center></td>
		</tr>
		<tr>
			<td><center><input type=button value="Deal" onClick="NewHand(this.form)" style="width:100px;"></center></td>
			<td colspan=3><center>
				<input type=button value="Stand" onClick="Dealer(this.form);LookAtHands(this.form);"  style="width:135px;">
				<input type=button value=" Hit " onClick="User(this.form)"  style="width:135px;"></center>
			</td>
		</tr>
		</table>
	</form>
</center>
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
		echo '<h1>'.$games->gameTitle('black-jack').' - Prize Pickup</h1>';
		echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('black-jack')."'");
		if( $getWish['wish_set'] == $games->gameSet('black-jack') )
		{
			$choice = explode(", ", $games->gameChoiceArr('black-jack'));
			$random = explode(", ", $games->gameRandArr('black-jack'));
			$currency = explode(" | ", $games->gameCurArr('black-jack'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('black-jack') == '' )
			{
				$general->gamePrize($games->gameSet('black-jack'),$games->gameTitle('black-jack'),$games->gameSub('black-jack'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('black-jack'),$games->gameTitle('black-jack'),'('.$games->gameSub('black-jack').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('black-jack');
			$rTotal = $games->gameRandArr('black-jack');
			$mTotal = $games->gameCurArr('black-jack');
			if( $games->gameSub('black-jack') == '' )
			{
				$general->gamePrize($games->gameSet('black-jack'),$games->gameTitle('black-jack'),$games->gameSub('black-jack'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('black-jack'),$games->gameTitle('black-jack'),'('.$games->gameSub('black-jack').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>