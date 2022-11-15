<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('toggler')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('toggler')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('toggler').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('toggler');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('toggler'); ?> - <?php echo $games->gameTitle('toggler'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('toggler'); ?>
<blockquote><b>Mechanics:</b> The goal of the game is to turn all the buttons from [X] to [.]. This is done by clicking buttons. When a button is clicked, its state is toggeled, but so is the state of four buttons around it, so plan carefully!</blockquote>
<center>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/toggler.js" type="text/javascript"></script>
	<table class='form' style='margin: 0 auto;'>
		<form>
		<tr>
			<td><input class="center" style="width:50px;" type="button" name="0_0" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="1_0" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="2_0" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="3_0" value="X" onclick="press(this.form, this);"></td>
		</tr>
		<tr>
			<td><input class="center" style="width:50px;" type="button" name="0_1" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="1_1" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="2_1" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="3_1" value="X" onclick="press(this.form, this);"></td>
		</tr>
		<tr>
			<td><input class="center" style="width:50px;" type="button" name="0_2" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="1_2" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="2_2" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="3_2" value="X" onclick="press(this.form, this);"></td>
		</tr>
		<tr>
			<td><input class="center" style="width:50px;" type="button" name="0_3" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="1_3" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="2_3" value="X" onclick="press(this.form, this);"></td>
			<td><input class="center" style="width:50px;" type="button" name="3_3" value="X" onclick="press(this.form, this);"></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" value="Reset" class="btn-danger" onclick="resetboard(this.form);">
			</td>
			<td colspan="2" align="center">
				<input type="button" value="Rules" class="btn-primary" onclick="showrules();">
			</td>
		</tr>
		</form>
	</table>
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
		echo '<h1>'.$games->gameTitle('toggler').' - Prize Pickup</h1>
		<center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('toggler')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'toggler' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('toggler'));
			$random = explode(", ", $games->gameRandArr('toggler'));
			$currency = explode(" | ", $games->gameCurArr('toggler'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('toggler') == '' )
			{
				$general->gamePrize($games->gameSet('toggler'),$games->gameTitle('toggler'),$games->gameSub('toggler'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('toggler'),$games->gameTitle('toggler'),'('.$games->gameSub('toggler').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('toggler');
			$rTotal = $games->gameRandArr('toggler');
			$mTotal = $games->gameCurArr('toggler');
			if( $games->gameSub('toggler') == '' )
			{
				$general->gamePrize($games->gameSet('toggler'),$games->gameTitle('toggler'),$games->gameSub('toggler'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('toggler'),$games->gameTitle('toggler'),'('.$games->gameSub('toggler').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>