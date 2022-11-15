<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('hangman-txt')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('hangman-txt')."' AND `log_subtitle`='(".$games->gameSub('hangman-txt').")' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('hangman-txt').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('hangman-txt');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('hangman-txt'); ?> - <?php echo $games->gameTitle('hangman-txt'); ?></h1>
<?php echo $games->gameBlurb('hangman-txt'); ?>
<script type="text/javascript" src="<?php echo $tcgurl; ?>admin/games/js/hangman-txt.js"></script>
<center>
	<form name="f">
		<table class="table table-bordered table-striped" border="0">
		<tbody>
		<tr>
			<td align="RIGHT">
				Score : <input type="TEXT" name="score" value="0" onfocus="score.blur();" size="2"><br>
				Fails (6): <input type="TEXT" name="lives" value="0" onfocus="lives.blur();" size="2">  
			</td>
			<td align="CENTER">
				<input type="TEXT" name="word" value="----- Hangman -----" onfocus="word.blur();" size="25"> <br>
				<input type="TEXT" name="tried" value="Click GO to get a word." onfocus="tried.blur();" size="25">  
			</td>
			<td align="CENTER"><input type="BUTTON" onclick="new_word(this.form);" value=" GO ">   </td>
		</tr>
		<tr>
			<td colspan="3" align="CENTER">
				<input type="BUTTON" value=" A " onclick="seek('A');">
				<input type="BUTTON" value=" B " onclick="seek('B');">
				<input type="BUTTON" value=" C " onclick="seek('C');">
				<input type="BUTTON" value=" D " onclick="seek('D');">
				<input type="BUTTON" value=" E " onclick="seek('E');">
				<input type="BUTTON" value=" F " onclick="seek('F');">
				<input type="BUTTON" value=" G " onclick="seek('G');">
				<input type="BUTTON" value=" H " onclick="seek('H');">
				<input type="BUTTON" value=" I " onclick="seek('I');">
				<input type="BUTTON" value=" J " onclick="seek('J');">
				<input type="BUTTON" value=" K " onclick="seek('K');">
				<input type="BUTTON" value=" L " onclick="seek('L');">
				<input type="BUTTON" value=" M " onclick="seek('M');">
			</td>
		</tr>
		<tr>
			<td colspan="3" align="CENTER">
				<input type="BUTTON" value=" N " onclick="seek('N');">
				<input type="BUTTON" value=" O " onclick="seek('O');">
				<input type="BUTTON" value=" P " onclick="seek('P');">
				<input type="BUTTON" value=" Q " onclick="seek('Q');">
				<input type="BUTTON" value=" R " onclick="seek('R');">
				<input type="BUTTON" value=" S " onclick="seek('S');">
				<input type="BUTTON" value=" T " onclick="seek('T');">
				<input type="BUTTON" value=" U " onclick="seek('U');">
				<input type="BUTTON" value=" V " onclick="seek('V');">
				<input type="BUTTON" value=" W " onclick="seek('W');">
				<input type="BUTTON" value=" X " onclick="seek('X');">
				<input type="BUTTON" value=" Y " onclick="seek('Y');">
				<input type="BUTTON" value=" Z " onclick="seek('Z');">
			</td>
		</tr>
		</tbody>
		</table>
	</form>
</center>

<?php
	}
}

else if( $go == "nicesave" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('hangman-txt').' - Prize Pickup</h1>';
		echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('hangman-txt')."'");
		if( $getWish['wish_set'] == $games->gameSet('hangman-txt') )
		{
			$choice = explode(", ", $games->gameChoiceArr('hangman-txt'));
			$random = explode(", ", $games->gameRandArr('hangman-txt'));
			$currency = explode(" | ", $games->gameCurArr('hangman-txt'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('hangman-txt'),$games->gameTitle('hangman-txt'),'('.$games->gameSub('hangman-txt').')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('hangman-txt');
			$rTotal = $games->gameRandArr('hangman-txt');
			$mTotal = $games->gameCurArr('hangman-txt');
			$general->gamePrize($games->gameSet('hangman-txt'),$games->gameTitle('hangman-txt'),'('.$games->gameSub('hangman-txt').')',$rTotal,$cTotal,$mTotal);
		}
	}
}
?>