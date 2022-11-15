<?php
// Run arrays
$subtitle = explode(", ", $games->gameSub('tic-tac-toe'));
$choice = explode(", ", $games->gameChoiceArr('tic-tac-toe'));
$random = explode(", ", $games->gameRandArr('tic-tac-toe'));
$money = explode(", ", $games->gameCurArr('tic-tac-toe'));
$array_count = count($subtitle);
$array_count .= count($choice);
$array_count .= count($random);
$array_count .= count($money);
for( $i=0; $i<=($array_count -1); $i++ )
{
	isset( $subtitle[$i] );
	isset( $choice[$i] );
	isset( $random[$i] );
	isset( $money[$i] );
}

$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('tic-tac-toe')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('tic-tac-toe')."' AND `log_date` >= '".$range['gup_date']."'");

$logSUBT = isset($logChk['log_subtitle']) ? $logChk['log_subtitle'] : null;
$logTEXT = isset($logChk['log_rewards']) ? $logChk['log_rewards'] : null;
$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		if( $logSUBT == '(Lost)' )
		{
			echo '<h1>'.$games->gameTitle('tic-tac-toe').' : Halt!</h1>
			<center><p>'.$logTEXT.'</p></center>';
		}

		else
		{
			echo '<h1>'.$games->gameTitle('tic-tac-toe').' : Halt!</h1>
			<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
			$general->displayRewards('tic-tac-toe');
			echo '</center>';
		}
	}

	else
	{
?>

<h1><?php echo $games->gameSet('tic-tac-toe'); ?> - <?php echo $games->gameTitle('tic-tac-toe'); ?></h1>
<?php echo $games->gameBlurb('tic-tac-toe'); ?>
<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/tic-tac-toe.js" type="text/javascript"></script>
<center><FORM NAME="tic">
	<INPUT TYPE="button" NAME="sqr1" class="tictac" value="     " onClick="if(document.tic.sqr1.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr1.value = ' X '; sqr1T = 1; turn = 1; vari(); check();} else if(document.tic.sqr1.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr1.value = ' X '; sqr1T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr1.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr1.value = ' O '; sqr1T = 1; turn = 1; vari(); player1Check()} drawCheck()">
	<INPUT TYPE="button" NAME="sqr2" class="tictac" value="     " onClick="if(document.tic.sqr2.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr2.value = ' X '; sqr2T = 1; turn = 1; vari(); check();} else if(document.tic.sqr2.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr2.value = ' X '; sqr2T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr2.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr2.value = ' O '; sqr2T = 1; turn = 1; vari(); player1Check()} drawCheck()">
	<INPUT TYPE="button" NAME="sqr3" class="tictac" value="     " onClick="if(document.tic.sqr3.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr3.value = ' X '; sqr3T = 1; turn = 1; vari(); check();} else if(document.tic.sqr3.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr3.value = ' X '; sqr3T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr3.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr3.value = ' O '; sqr3T = 1; turn = 1; vari(); player1Check()} drawCheck()"><br />
	<INPUT TYPE="button" NAME="sqr4" class="tictac" value="     " onClick="if(document.tic.sqr4.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr4.value = ' X '; sqr4T = 1; turn = 1; vari(); check();} else if(document.tic.sqr4.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr4.value = ' X '; sqr4T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr4.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr4.value = ' O '; sqr4T = 1; turn = 1; vari(); player1Check()} drawCheck()">
	<INPUT TYPE="button" NAME="sqr5" class="tictac" value="     " onClick="if(document.tic.sqr5.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr5.value = ' X '; sqr5T = 1; turn = 1; vari(); check();} else if(document.tic.sqr5.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr5.value = ' X '; sqr5T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr5.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr5.value = ' O '; sqr5T = 1; turn = 1; vari(); player1Check()} drawCheck()">
	<INPUT TYPE="button" NAME="sqr6" class="tictac" value="     " onClick="if(document.tic.sqr6.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr6.value = ' X '; sqr6T = 1; turn = 1; vari(); check();} else if(document.tic.sqr6.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr6.value = ' X '; sqr6T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr6.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr6.value = ' O '; sqr6T = 1; turn = 1; vari(); player1Check()} drawCheck()"><br />
	<INPUT TYPE="button" NAME="sqr7" class="tictac" value="     " onClick="if(document.tic.sqr7.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr7.value = ' X '; sqr7T = 1; turn = 1; vari(); check();} else if(document.tic.sqr7.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr7.value = ' X '; sqr7T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr7.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr7.value = ' O '; sqr7T = 1; turn = 1; vari(); player1Check()} drawCheck()">
	<INPUT TYPE="button" NAME="sqr8" class="tictac" value="     " onClick="if(document.tic.sqr8.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr8.value = ' X '; sqr8T = 1; turn = 1; vari(); check();} else if(document.tic.sqr8.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr8.value = ' X '; sqr8T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr8.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr8.value = ' O '; sqr8T = 1; turn = 1; vari(); player1Check()} drawCheck()">
	<INPUT TYPE="button" NAME="sqr9" class="tictac" value="     " onClick="if(document.tic.sqr9.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr9.value = ' X '; sqr9T = 1; turn = 1; vari(); check();} else if(document.tic.sqr9.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr9.value = ' X '; sqr9T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr9.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr9.value = ' O '; sqr9T = 1; turn = 1; vari(); player1Check()} drawCheck()">
</FORM></center>

<?php
	}
}

else if( $go == "lost" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('tic-tac-toe').' : Lost</h1>
		<p>This is so heart-breaking! Isn\'t there any possibility to win against a machine? Don\'t be sad though! You can still try out your luck on the next round. Give it some payback by that time!</p>';
		$today = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('tic-tac-toe')."','".$games->gameTitle('tic-tac-toe')."','(Lost)','You lost this game.','$today')");
	}
}

else if( $go == "draw" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('tic-tac-toe').' (Draw) - Prize Pickup</h1>';
		echo '<center><p>You have a draw! Please take everything you see below:</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('tic-tac-toe')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'tic-tac-toe' ) )
		{
			$cTotal = $choice[0] * 2;
			$rTotal = $random[0] * 2;
			$currency = explode(" | ", $money[0]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('tic-tac-toe'),$games->gameTitle('tic-tac-toe'),'('.$subtitle[0].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('tic-tac-toe'),$games->gameTitle('tic-tac-toe'),'('.$subtitle[0].')',$random[0],$choice[0],$money[0]);
		}
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
		echo '<h1>'.$games->gameTitle('tic-tac-toe').' (Won) - Prize Pickup</h1>';
		echo '<center><p>Congratulations, you won the game! Please take everything you see below:</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('tic-tac-toe')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'tic-tac-toe' ) )
		{
			$cTotal = $choice[1] * 2;
			$rTotal = $random[1] * 2;
			$currency = explode(" | ", $money[0]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('tic-tac-toe'),$games->gameTitle('tic-tac-toe'),'('.$subtitle[1].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('tic-tac-toe'),$games->gameTitle('tic-tac-toe'),'('.$subtitle[1].')',$random[1],$choice[1],$money[1]);
		}
	}
}
?>