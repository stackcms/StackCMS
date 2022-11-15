<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('peeptin')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('peeptin')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('peeptin').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('peeptin');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('peeptin'); ?> - <?php echo $games->gameTitle('peeptin'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('peeptin'); ?>
<blockquote><b>Mechanics:</b> The goal of the game is to arrange the blocks from 1 to 15 in their numeric order. Click a number next to the empty cell to move it into that cell. The game is won when all the numbers are sorted, and the empty square is in the lower-righthand corner.</blockquote>
<center>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/peeptin.js" type="text/javascript"></script>
	<table border="0" cellspacing="0" cellpadding="1">
		<form>
		<tr>
			<td><input style="width:40px;" type="button" name="0_0" value="1" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="1_0" value="2" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="2_0" value="3" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="3_0" value="4" onclick="press15(this.form, this);"></td>
		</tr>
		<tr>
			<td><input style="width:40px;" type="button" name="0_1" value="5" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="1_1" value="6" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="2_1" value="7" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="3_1" value="8" onclick="press15(this.form, this);"></td>
		</tr>
		<tr>
			<td><input style="width:40px;" type="button" name="0_2" value="9" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="1_2" value="10" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="2_2" value="11" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="3_2" value="12" onclick="press15(this.form, this);"></td>
		</tr>
		<tr>
			<td><input style="width:40px;" type="button" name="0_3" value="13" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="1_3" value="14" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="2_3" value="15" onclick="press15(this.form, this);"></td>
			<td><input style="width:40px;" type="button" name="3_3" value=" " onclick="press15(this.form, this);"></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input style="width:62px;" type="button" value="Reset" class="btn-danger" onclick="resetboard15(this.form);">
			</td>
			<td colspan="2" align="center">
				<input style="width:62px;" type="button" value="Rules" class="btn-primary" onclick="showrules15();">
			</td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input style="width:126px;" type="button" value="Shuffle" class="btn-success" onclick="shuffle15(this.form,150);">
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
		/* Blurb can be changed through the class.call.php file */
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('peeptin').' - Prize Pickup</h1>
		<center><p>Congrats, you won the game! Take everything you see blow and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('peeptin')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'peeptin' ) )
		{
			$choice = explode(", ", $games->gameChoiceArr('peeptin'));
			$random = explode(", ", $games->gameRandArr('peeptin'));
			$currency = explode(" | ", $games->gameCurArr('peeptin'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			if( $games->gameSub('peeptin') == '' )
			{
				$general->gamePrize($games->gameSet('peeptin'),$games->gameTitle('peeptin'),$games->gameSub('peeptin'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('peeptin'),$games->gameTitle('peeptin'),'('.$games->gameSub('peeptin').')',$rTotal,$cTotal,$mTotal);
			}
		}

		else
		{
			$cTotal = $games->gameChoiceArr('peeptin');
			$rTotal = $games->gameRandArr('peeptin');
			$mTotal = $games->gameCurArr('peeptin');
			if( $games->gameSub('peeptin') == '' )
			{
				$general->gamePrize($games->gameSet('peeptin'),$games->gameTitle('peeptin'),$games->gameSub('peeptin'),$rTotal,$cTotal,$mTotal);
			}
			else
			{
				$general->gamePrize($games->gameSet('peeptin'),$games->gameTitle('peeptin'),'('.$games->gameSub('peeptin').')',$rTotal,$cTotal,$mTotal);
			}
		}
	}
}
?>