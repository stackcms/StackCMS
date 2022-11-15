<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('lottery')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('lottery')."' AND `log_date` >= '".$range['gup_date']."'");

// Get lottery answers and rewards per active round
$round = explode(" | ", $games->gamePassArr('lottery'));
$currArray = $games->gameCurrentArr('lottery');
foreach( $round as $key => $val )
{
	$lottery = explode(", ", $round[$key]);
	if( $key == $currArray )
	{
		$subtitle = explode(", ", $games->gameSub('lottery'));
		$choice = explode(", ", $games->gameChoiceArr('lottery'));
		$random = explode(", ", $games->gameRandArr('lottery'));
		$money = explode(", ", $games->gameCurArr('lottery'));
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

		$ans = 0;
		foreach( $lottery as $value )
		{
			$ans = $ans + $_POST[$value];
		}
	}
}

$logSUBT = isset($logChk['log_subtitle']) ? $logChk['log_subtitle'] : null;
$logTEXT = isset($logChk['log_rewards']) ? $logChk['log_rewards'] : null;
$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		if( $logSUBT == "(".$subtitle[0].")" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' : Halt!</h1>
			<center><p>'.$logTEXT.'</p></center>';
		}

		else
		{
			echo '<h1>'.$games->gameTitle('lottery').' : Halt!</h1>
			<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
			$general->displayRewards('lottery');
			echo '</center>';
		}
	}

	else
	{
?>

<h1><?php echo $games->gameSet('lottery'); ?> - <?php echo $games->gameTitle('lottery'); ?></h1>
<?php echo $games->gameBlurb('lottery'); ?>
<p>You will be directed to your prize if you're lucky to get at least 1 number correctly.</p>
<center>
	<form action="<?php echo $tcgurl; ?>games.php?play=lottery&go=prize" method="post">
		<table class="table-border" width="30%" cellspacing="3">  
			<tr>
				<td width="5%" class="tableBody" align="center"><input type="checkbox" name="a" value="1"><br/>01</td> 
				<td width="5%" class="tableBody" align="center"><input type="checkbox" name="b" value="1"><br/>02</td> 
				<td width="5%" class="tableBody" align="center"><input type="checkbox" name="c" value="1"><br/>03</td> 
				<td width="5%" class="tableBody" align="center"><input type="checkbox" name="d" value="1"><br/>04</td> 
				<td width="5%" class="tableBody" align="center"><input type="checkbox" name="e" value="1"><br/>05</td>
			</tr>
			<tr>
				<td class="tableBody" align="center"><input type="checkbox" name="f" value="1"><br/>06</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="g" value="1"><br/>07</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="h" value="1"><br/>08</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="i" value="1"><br/>09</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="j" value="1"><br/>10</td>
			</tr>
			<tr>
				<td class="tableBody" align="center"><input type="checkbox" name="k" value="1"><br/>11</td>
				<td class="tableBody" align="center"><input type="checkbox" name="l" value="1"><br/>12</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="m" value="1"><br/>13</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="n" value="1"><br/>14</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="o" value="1"><br/>15</td>
			</tr>
			<!-- UNCOMMENT THE TABLE LINES BELOW IF YOU NEED MORE, COPY-PASTE TO ADD MORE
				 MAKE SURE TO CHANGE THE NAME LETTERS AND THE LOTTO NUMBERS AS WELL -->
			<!--<tr>
				<td class="tableBody" align="center"><input type="checkbox" name="k" value="1"><br/>11</td>
				<td class="tableBody" align="center"><input type="checkbox" name="l" value="1"><br/>12</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="m" value="1"><br/>13</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="n" value="1"><br/>14</td> 
				<td class="tableBody" align="center"><input type="checkbox" name="o" value="1"><br/>15</td>
			</tr>-->
			<tr>
				<td class="tableBody" colspan="5" align="center">
					<input type="submit" class="btn-success" value="Draw"> 
					<input type="reset" class="btn-danger" value="Reset">
				</td>
			</tr> 
		</table>
	</form>
</center>

<?php
	}
}

else
{
	$N = $ans;
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		if( $N == "0" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Tough Luck!</h1><center>Oh no! You didn\'t get any numbers correctly. Please try your luck again in the next round!</center><br/>';
			$today = date("Y-m-d");
			$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('lottery')."','".$games->gameTitle('lottery')."','(".$subtitle[0].")','You lost this game.','$today')");
		}

		if( $N == "1" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Prize Pickup</h1>
			<p>Congrats, you got <em>1 number</em> correctly! Claim your rewards below and don\'t forget to log them:</p><center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lottery')."'");
			if( $getWish['wish_set'] == $games->gameSet('lottery') )
			{
				$cTotal = $choice[1] * 2;
				$rTotal = $random[1] * 2;
				$currency = explode(" | ", $money[1]);
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[1].')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[1].')',$random[1],$choice[1],$money[1]);
			}
		}

		if( $N == "2" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Prize Pickup</h1>
			<p>Congrats, you got <em>2 numbers</em> correctly! Claim your rewards below and don\'t forget to log them:</p><center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lottery')."'");
			if( $getWish['wish_set'] == $games->gameSet('lottery') )
			{
				$cTotal = $choice[2] * 2;
				$rTotal = $random[2] * 2;
				$currency = explode(" | ", $money[2]);
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[2].')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[2].')',$random[2],$choice[2],$money[2]);
			}
		}

		if( $N == "3" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Prize Pickup</h1>
			<p>Congrats, you got <em>3 numbers</em> correctly! Claim your rewards below and don\'t forget to log them:</p><center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lottery')."'");
			if( $getWish['wish_set'] == $games->gameSet('lottery') )
			{
				$cTotal = $choice[3] * 2;
				$rTotal = $random[3] * 2;
				$currency = explode(" | ", $money[3]);
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[3].')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[3].')',$random[3],$choice[3],$money[3]);
			}
		}

		if( $N == "4" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Prize Pickup</h1>
			<p>Congrats, you got <em>4 numbers</em> correctly! Claim your rewards below and don\'t forget to log them:</p><center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lottery')."'");
			if( $getWish['wish_set'] == $games->gameSet('lottery') )
			{
				$cTotal = $choice[4] * 2;
				$rTotal = $random[4] * 2;
				$currency = explode(" | ", $money[4]);
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[4].')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[4].')',$random[4],$choice[4],$money[4]);
			}
		}

		if( $N == "5" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Prize Pickup</h1>
			<p>Congrats, you got <em>5 numbers</em> correctly! Claim your rewards below and don\'t forget to log them:</p><center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lottery')."'");
			if( $getWish['wish_set'] == $games->gameSet('lottery') )
			{
				$cTotal = $choice[5] * 2;
				$rTotal = $random[5] * 2;
				$currency = explode(" | ", $money[4]);
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[5].')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[5].')',$random[5],$choice[5],$money[5]);
			}
		}

		// Uncomment the lines below if you need more rewards for this game, copy-paste to add more
		// Make sure you have the same amount of rewards from the games admin panel
		// Change all the [5]s to [6], [7]... depending on how many you're going to add

		/*if( $N == "5" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Prize Pickup</h1>
			<p>Congrats, you got <em>5 numbers</em> correctly! Claim your rewards below and don\'t forget to log them:</p><center>';
			$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lottery')."'");
			if( $getWish['wish_set'] == $games->gameSet('lottery') )
			{
				$cTotal = $choice[5] * 2;
				$rTotal = $random[5] * 2;
				$currency = explode(" | ", $money[4]);
				foreach( $currency as $m ) { $mTotal[] = $m * 2; }
				$mTotal = implode(" | ", $mTotal);
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[5].')',$rTotal,$cTotal,$mTotal);
			}

			else
			{
				$general->gamePrize($games->gameSet('lottery'),$games->gameTitle('lottery'),'('.$subtitle[5].')',$random[5],$choice[5],$money[5]);
			}
		}*/

		// Change "5" if you have more than 5 lotto numbers for a jackpot
		if( $N > "5" )
		{
			echo '<h1>'.$games->gameTitle('lottery').' - Halt!</h1>
			<p>It seems like you\'ve selected more than 5 lottery numbers! Please go back and make sure to check 5 numbers.</p>';
		}
	}
}
?>