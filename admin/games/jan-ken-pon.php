<?php
// Run arrays
$subtitle = explode(", ", $games->gameSub('jan-ken-pon'));
$choice = explode(", ", $games->gameChoiceArr('jan-ken-pon'));
$random = explode(", ", $games->gameRandArr('jan-ken-pon'));
$money = explode(", ", $games->gameCurArr('jan-ken-pon'));
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

$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('jan-ken-pon')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('jan-ken-pon')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;
$logSUBT = isset($logChk['log_subtitle']) ? $logChk['log_subtitle'] : null;
$logTEXT = isset($logChk['log_rewards']) ? $logChk['log_rewards'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		if( $logSUBT == "(Lost)" )
		{
			echo '<h1>'.$games->gameTitle('jan-ken-pon').' : Halt</h1>
			<center><p>'.$logTEXT.'</p></center>';
		}

		else
		{
			echo '<h1>'.$games->gameTitle('jan-ken-pon').' : Halt!</h1>
			<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
			$general->displayRewards('jan-ken-pon');
			echo '</center>';
		}
	}

	else
	{
?>

<h1><?php echo $games->gameSet('jan-ken-pon'); ?> - <?php echo $games->gameTitle('jan-ken-pon'); ?></h1>
<?php echo $games->gameBlurb('jan-ken-pon'); ?>
<center>
	<script language="javascript" src="<?php echo $tcgurl; ?>admin/games/js/jan-ken-pon.js" type="text/javascript"></script>
	<form name=game>
		<table class='form' style='margin: 0 auto;'>
			<tr><th colspan=3 class="center">Choose one</td></tr>
			<tr>
				<td width="100" align="center" valign="middle"><a href="javascript:void(0);" onClick="playGame(1);"><img src="<?php echo $tcgurl; ?>admin/games/images/rock.png" border=0></a><br /><b>Rock</b></td>
				<td width="100" align="center" valign="middle"><a href="javascript:void(0);" onClick="playGame(2);"><img src="<?php echo $tcgurl; ?>admin/games/images/paper.png"  border=0></a><br /><b>Paper</b></td>
				<td width="100" align="center" valign="middle"><a href="javascript:void(0);" onClick="playGame(3);"><img src="<?php echo $tcgurl; ?>admin/games/images/scissor.png"  border=0></a><br /><b>Scissors</b></td>
			</tr>
			<tr>
				<td colspan=3 class=center><input style="text-align:center; display: none;" type=text name=msg size=54></td>
			</tr>
		</table>
	</form>
</center>

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
		echo '<h1>'.$games->gameTitle('jan-ken-pon').' - Tough Luck!</h1>
		<center><p>Sorry, you didn\'t win! Please try your luck again next round. :D</p></center>';
		$today = date("Y-m-d", strtotime("now"));
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('jan-ken-pon')."','".$games->gameTitle('jan-ken-pon')."','(Lost)','You lost this game.','$today')");
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
		echo '<h1>'.$games->gameTitle('jan-ken-pon').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('jan-ken-pon')."'");
		if( isset( $getWish['wish_set'] ) == $games->gameSet('jan-ken-pon') )
		{
			$cTotal = $choice[0] * 2;
			$rTotal = $random[0] * 2;
			$currency = explode(" | ", $money[0]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('jan-ken-pon'),$games->gameTitle('jan-ken-pon'),'('.$subtitle[0].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('jan-ken-pon'),$games->gameTitle('jan-ken-pon'),'('.$subtitle[0].')',$random[0],$choice[0],$money[0]);
		}
	}
}

else if( $go == "won" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('jan-ken-pon').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('jan-ken-pon')."'");
		if( $getWish['wish_set'] == $games->gameSet('jan-ken-pon') )
		{
			$cTotal = $choice[1] * 2;
			$rTotal = $random[1] * 2;
			$currency = explode(" | ", $money[1]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('jan-ken-pon'),$games->gameTitle('jan-ken-pon'),'('.$subtitle[1].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('jan-ken-pon'),$games->gameTitle('jan-ken-pon'),'('.$subtitle[1].')',$random[1],$choice[1],$money[1]);
		}
	}
}
?>