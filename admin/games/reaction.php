<?php
// Run arrays
$subtitle = explode(", ", $games->gameSub('reaction'));
$choice = explode(", ", $games->gameChoiceArr('reaction'));
$random = explode(", ", $games->gameRandArr('reaction'));
$money = explode(", ", $games->gameCurArr('reaction'));
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

$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('reaction')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('reaction')."' AND `log_date` >= '".$range['gup_date']."'");

$logSUBT = isset($logChk['log_subtitle']) ? $logChk['log_subtitle'] : null;
$logTEXT = isset($logChk['log_rewards']) ? $logChk['log_rewards'] : null;
$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		if( $logSUBT == '(Lost)' ) {
			echo '<h1>'.$games->gameTitle('reaction').' : Halt!</h1>
			<center><p>'.$logTEXT.'</p></center>';
		}

		else
		{
			echo '<h1>'.$games->gameTitle('reaction').' : Halt!</h1>
			<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
			$general->displayRewards('reaction');
			echo '</center>';
		}
	}

	else
	{
?>

<h1><?php echo $games->gameSet('reaction'); ?> - <?php echo $games->gameTitle('reaction'); ?></h1>
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('reaction'); ?>
<center>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/reaction.js" type="text/javascript"></script>
	<p class="center">Click the start button and then press the correct button.<br />Try to get it right 5 times in a row.</p>
	<form name="ausgabe">
		<table class="form" style="margin: 0 auto;">
			<tr>
				<td><input type="button" value="Start" name="B3" class="btn-success" onclick="tempo3()"></td>
				<td><input type="text" name="text" size="10" class="form-control" style="display: inline;" disabled></td>
				<td><input type="text" name="versuche" size="2" class="form-control" style="display: inline;" disabled></td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<input type="button" value="LEFT" class="btn-warning" name="B1" onclick="gas()" style="display: inline-block;">
					<input type="button" value="RIGHT" class="btn-default" name="B2" onclick="bremsen()" style="display: inline-block;">
				</td>
			</tr>
		</table>
	</form>
</center>

<?php
	}
}

else if( $go == "zero" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<center><b>'.$games->gameTitle('reaction').' - Tough Luck!</b>
		<p>Sorry, you didn\'t win! Please try your luck again next round. :D</p></center>';
		$today = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('reaction')."','".$games->gameTitle('reaction')."','(Lost)','You lost this game.','$today')");
	}
}

else if( $go == "one" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('reaction').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('reaction')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'reaction' ) )
		{
			$cTotal = $choice[0] * 2;
			$rTotal = $random[0] * 2;
			$currency = explode(" | ", $money[0]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[0].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[0].')',$random[0],$choice[0],$money[0]);
		}
	}
}

else if( $go == "two" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('reaction').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('reaction')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'reaction' ) )
		{
			$cTotal = $choice[1] * 2;
			$rTotal = $random[1] * 2;
			$currency = explode(" | ", $money[1]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[1].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[1].')',$random[1],$choice[1],$money[1]);
		}
	}
}

else if( $go == "three" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>Reaction - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('reaction')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'reaction' ) )
		{
			$cTotal = $choice[2] * 2;
			$rTotal = $random[2] * 2;
			$currency = explode(" | ", $money[2]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[2].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[2].')',$random[2],$cTotal[2],$money[2]);
		}
	}
}

else if( $go == "four" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>Reaction - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('reaction')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'reaction' ) )
		{
			$cTotal = $choice[3] * 2;
			$rTotal = $random[3] * 2;
			$currency = explode(" | ", $money[3]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[3].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[3].')',$random[3],$choice[3],$money[3]);
		}
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
		echo '<h1>Reaction - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('reaction')."'");
		if( $getWish['wish_set'] == $games->gameSet('reaction') )
		{
			$cTotal = $choice[4] * 2;
			$rTotal = $random[4] * 2;
			$currency = explode(" | ", $money[4]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[4].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('reaction'),$games->gameTitle('reaction'),'('.$subtitle[4].')',$random[4],$choice[4],$money[4]);
		}
	}
}
?>