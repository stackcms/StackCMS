<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('lucky-match')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('lucky-match')."' AND `log_date` >= '".$range['gup_date']."'");

// Get lucky-match answers and rewards per active round
$round = explode(" | ", $games->gamePassArr('lucky-match'));
foreach( $round as $key => $val )
{
	$match = explode(", ", $round[$key]);
	if( $key == $games->gameCurrentArr( 'lucky-match' ) )
	{
		$subtitle = explode(", ", $games->gameSub('lucky-match'));
		$choice = explode(", ", $games->gameChoiceArr('lucky-match'));
		$random = explode(", ", $games->gameRandArr('lucky-match'));
		$money = explode(", ", $games->gameCurArr('lucky-match'));
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

		foreach( $match as $key => $val )
		{
			$clue[] = $match[$key];
		}
	}
}
$clue = implode(", ", $clue);

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('lucky-match').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('lucky-match');
		echo '</center>';
	}

	else
	{
?>

<h1><?php echo $games->gameSet('lucky-match'); ?> - <?php echo $games->gameTitle('lucky-match'); ?></h1>
<?php echo $games->gameBlurb('lucky-match'); ?>
<p align="center">

<?php
	echo '<img src="'.$tcgcards.''.str_replace(", ", ".".$tcgext."\" title=\"\"> <img src=\"".$tcgcards."", $clue).'.'.$tcgext.'"><br />
	('.$clue.')';
?>

</p>

<p align="center"><b>How many matches did you find?</b></p>
<center>
	<button onclick="window.location.href='/games.php?play=lucky-match&go=zero'" class="btn-success">0</button>
	<button onclick="window.location.href='/games.php?play=lucky-match&go=one'" class="btn-success">1</button>
	<button onclick="window.location.href='/games.php?play=lucky-match&go=two'" class="btn-success">2</button>
	<button onclick="window.location.href='/games.php?play=lucky-match&go=three'" class="btn-success">3</button>
	<button onclick="window.location.href='/games.php?play=lucky-match&go=four'" class="btn-success">4</button>
	<button onclick="window.location.href='/games.php?play=lucky-match&go=five'" class="btn-success">5</button>
	<!-- UNCOMMENT THE LINES BELOW IF YOU NEED MORE FOR THIS GAME, COPY-PASTE TO ADD MORE
		 MAKE SURE YOU HAVE THE SAME AMOUNT OF MATCHES AND REWARDS FROM THE GAMES ADMIN PANEL
		 CHANGE THE VALUE OF &go=five TO THE NEXT VALUE (e.g. six, seven...)
		 CHANGE THE VALUE OF 5 TO THE NEXT VALUE AS WELL -->
	<!--<button onclick="window.location.href='/games.php?play=lucky-match&go=five'" class="btn-success">5</button>-->
</center>

<?php
	}
}

else if( $go == "zero" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		/* Blurb can be changed through the class.call.php file */
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('lucky-match').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lucky-match')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'lucky-match' ) )
		{
			$cTotal = $choice[0] * 2;
			$rTotal = $random[0] * 2;
			$currency = explode(" | ", $money[0]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[0].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[0].')',$random[0],$choice[0],$money[0]);
		}
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
		echo '<h1>'.$games->gameTitle('lucky-match').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lucky-match')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'lucky-match' ) )
		{
			$cTotal = $choice[1] * 2;
			$rTotal = $random[1] * 2;
			$currency = explode(" | ", $money[1]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[1].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[1].')',$random[1],$choice[1],$money[1]);
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
		echo '<h1>'.$games->gameTitle('lucky-match').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lucky-match')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'lucky-match' ) )
		{
			$cTotal = $choice[2] * 2;
			$rTotal = $random[2] * 2;
			$currency = explode(" | ", $money[2]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[2].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[2].')',$random[2],$choice[2],$money[2]);
		}
	}
}

else if( $go == "three" )
{
	if( !isset($_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('lucky-match').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lucky-match')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'lucky-match' ) )
		{
			$cTotal = $choice[3] * 2;
			$rTotal = $random[3] * 2;
			$currency = explode(" | ", $money[3]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[3].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[3].')',$random[3],$choice[3],$money[3]);
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
		echo '<h1>'.$games->gameTitle('lucky-match').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lucky-match')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'lucky-match' ) )
		{
			$cTotal = $choice[4] * 2;
			$rTotal = $random[4] * 2;
			$currency = explode(" | ", $money[4]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[4].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[4].')',$random[4],$choice[4],$money[4]);
		}
	}
}

else if( $go == "five" )
{
	if( !isset( $_SERVER['HTTP_REFERER'] ) )
	{
		echo $ForbiddenAccess;
	}

	else
	{
		echo '<h1>'.$games->gameTitle('lucky-match').' - Prize Pickup</h1><center><p>Take everything you see below and don\'t forget to log it.</p>';
		$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('lucky-match')."'");
		if( $getWish['wish_set'] == $games->gameSet( 'lucky-match' ) )
		{
			$cTotal = $choice[5] * 2;
			$rTotal = $random[5] * 2;
			$currency = explode(" | ", $money[5]);
			foreach( $currency as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[5].')',$rTotal,$cTotal,$mTotal);
		}

		else
		{
			$general->gamePrize($games->gameSet('lucky-match'),$games->gameTitle('lucky-match'),'('.$subtitle[5].')',$random[5],$choice[5],$money[5]);
		}
	}
}
?>