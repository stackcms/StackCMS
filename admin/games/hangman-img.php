<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('hangman-img')."' ORDER BY `gup_id` DESC");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('hangman-img')."' AND `log_subtitle`='(".$games->gameSub('hangman-img').")' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('hangman-img').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('hangman-img');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('hangman-img'); ?> - <?php echo $games->gameTitle('hangman-img'); ?></h1>
<?php echo $games->gameBlurb('hangman-img'); ?>
<script type="text/javascript" src="<?php echo $tcgurl; ?>admin/games/js/hangman-img.js"></script>
<center>
	<img src="<?php echo $tcgurl; ?>admin/games/images/hmstart.png" height="170" width="170" name="hm"><br><br>
	<form name="game">
		<table width="300">
		<tr><td width="100" align="right"><strong>Word</strong></td><td width="200"><input type="text" name="displayWord"></td></tr>
		<tr><td width="100" align="right"><strong>Used</strong></td><td width="200"><input type="text" name="usedLetters"></td></tr>
		</table>
	</form>

	<input type="button" onClick="javascript:selectLetter('1');" value="1">
	<input type="button" onClick="javascript:selectLetter('2');" value="2">
	<input type="button" onClick="javascript:selectLetter('3');" value="3">
	<input type="button" onClick="javascript:selectLetter('4');" value="4">
	<input type="button" onClick="javascript:selectLetter('5');" value="5">
	<input type="button" onClick="javascript:selectLetter('6');" value="6">
	<input type="button" onClick="javascript:selectLetter('7');" value="7">
	<input type="button" onClick="javascript:selectLetter('8');" value="8">
	<input type="button" onClick="javascript:selectLetter('9');" value="9">
	<input type="button" onClick="javascript:selectLetter('0');" value="0"><br />

	<input type="button" onClick="javascript:selectLetter('A');" value="A">
	<input type="button" onClick="javascript:selectLetter('B');" value="B">
	<input type="button" onClick="javascript:selectLetter('C');" value="C">
	<input type="button" onClick="javascript:selectLetter('D');" value="D">
	<input type="button" onClick="javascript:selectLetter('E');" value="E">
	<input type="button" onClick="javascript:selectLetter('F');" value="F">
	<input type="button" onClick="javascript:selectLetter('G');" value="G">
	<input type="button" onClick="javascript:selectLetter('H');" value="H">
	<input type="button" onClick="javascript:selectLetter('I');" value="I">
	<input type="button" onClick="javascript:selectLetter('J');" value="J">
	<input type="button" onClick="javascript:selectLetter('K');" value="K">
	<input type="button" onClick="javascript:selectLetter('L');" value="L">
	<input type="button" onClick="javascript:selectLetter('M');" value="M"><br />

	<input type="button" onClick="javascript:selectLetter('N');" value="N">
	<input type="button" onClick="javascript:selectLetter('O');" value="O">
	<input type="button" onClick="javascript:selectLetter('P');" value="P">
	<input type="button" onClick="javascript:selectLetter('Q');" value="Q">
	<input type="button" onClick="javascript:selectLetter('R');" value="R">
	<input type="button" onClick="javascript:selectLetter('S');" value="S">
	<input type="button" onClick="javascript:selectLetter('T');" value="T">
	<input type="button" onClick="javascript:selectLetter('U');" value="U">
	<input type="button" onClick="javascript:selectLetter('V');" value="V">
	<input type="button" onClick="javascript:selectLetter('W');" value="W">
	<input type="button" onClick="javascript:selectLetter('X');" value="X">
	<input type="button" onClick="javascript:selectLetter('Y');" value="Y">
	<input type="button" onClick="javascript:selectLetter('Z');" value="Z"><br />

	<input type="button" onClick="javascript:selectLetter(' ');" value="SPACE"> <input type="button" onClick="javascript:reset();" value="RESET GAME">
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
		echo '<h1>'.$games->gameTitle('hangman-img').' - Prize Pickup</h1>';
		echo '<center><p>Good work! Take everything you see below and don\'t forget to log it!</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('hangman-img')."'");
		if( $getWish['wish_set'] == $games->gameSet('hangman-img') )
		{
			$choice = explode(", ", $games->gameChoiceArr('hangman-img'));
			$random = explode(", ", $games->gameRandArr('hangman-img'));
			$currency = explode(" | ", $games->gameCurArr('hangman-img'));
			foreach( $choice as $c ) { $cTotal = $c * 2; }
			foreach( $random as $r ) { $rTotal = $r * 2; }
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('hangman-img'),$games->gameTitle('hangman-img'),'('.$games->gameSub('hangman-img').')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$cTotal = $games->gameChoiceArr('hangman-img');
			$rTotal = $games->gameRandArr('hangman-img');
			$mTotal = $games->gameCurArr('hangman-img');
			$general->gamePrize($games->gameSet('hangman-img'),$games->gameTitle('hangman-img'),'('.$games->gameSub('hangman-img').')',$rTotal,$cTotal,$mTotal);
		}
	}
}
?>