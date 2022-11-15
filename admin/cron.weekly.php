<?php
@include('class.lib.php');

$database = new Database;
$sanitize = new Sanitize;
$settings = new Settings;
$games = new Games;

date_default_timezone_set($settings->getValue('tcg_timezone'));

$upDay = $settings->getValue( 'update_scope' );
$today = date("Y-m-d", strtotime("now"));
$dayoftheweek = date("l");

// Update weekly and bi-weekly date first
if( $dayoftheweek == $upDay )
{
	$database->query("UPDATE `tcg_games_updater` SET `gup_date`='$today' WHERE `gup_set`='Weekly'");
	$database->query("UPDATE `tcg_games_updater` SET `gup_date`='$today' WHERE `gup_set`='Special'");
	$database->query("UPDATE `tcg_games` SET `game_updated`='$today' WHERE `game_set`='Weekly'");
	$database->query("UPDATE `tcg_games` SET `game_updated`='$today' WHERE `game_set`='Special'");

	$getA = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Set A'");
	$getB = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Set B'");
	$weekly = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Weekly'");

	$last = date("Y-m-d", strtotime("-1 week last $upDay"));

	if( $getA['gup_date'] == $last )
	{
		$database->query("UPDATE `tcg_games_updater` SET `gup_date`='$today' WHERE `gup_set`='Set A'");
		$database->query("UPDATE `tcg_games` SET `game_updated`='$today' WHERE `game_set`='Set A'");

		// Update Set A password gate games if there's any
		$allPass = $database->get_assoc("SELECT `game_pass_array` FROM `tcg_games` WHERE `game_set`='Set A' AND `game_type` IN ('image', 'text')");
		$allQues = $database->get_assoc("SELECT `game_ques_array` FROM `tcg_games` WHERE `game_set`='Set A' AND `game_type` IN ('image', 'text')");
		$allClue = $database->get_assoc("SELECT `game_clue_array` FROM `tcg_games` WHERE `game_set`='Set A' AND `game_type` IN ('image', 'text')");
		$curArray = $database->get_assoc("SELECT `game_current_array` FROM `tcg_games` WHERE `game_set`='Set A' AND `game_type` IN ('image', 'text')");

		if( $curArray <= (count($allPass) -1) && $curArray <= (count($allClue) -1) && $curArray <= (count($allPass) -1) )
		{
			$database->query("UPDATE `tcg_games` SET `game_current_array`=$curArray+1 WHERE `game_set`='Set A' AND `game_type` IN ('image', 'text')");
		}

		else
		{
			$database->query("UPDATE `tcg_games` SET `game_current_array`=0 WHERE `game_set`='Set A' AND `game_type` IN ('image', 'text')");
		}
	}

	else
	{
		$database->query("UPDATE `tcg_games_updater` SET `gup_date`='$today' WHERE `gup_set`='Set B'");
		$database->query("UPDATE `tcg_games` SET `game_updated`='$today' WHERE `game_set`='Set B'");

		// Update Set B password gate games if there's any
		$allPass = $database->get_assoc("SELECT `game_pass_array` FROM `tcg_games` WHERE `game_set`='Set B' AND `game_type` IN ('image', 'text')");
		$allQues = $database->get_assoc("SELECT `game_ques_array` FROM `tcg_games` WHERE `game_set`='Set B' AND `game_type` IN ('image', 'text')");
		$allClue = $database->get_assoc("SELECT `game_clue_array` FROM `tcg_games` WHERE `game_set`='Set B' AND `game_type` IN ('image', 'text')");
		$curArray = $database->get_assoc("SELECT `game_current_array` FROM `tcg_games` WHERE `game_set`='Set B' AND `game_type` IN ('image', 'text')");

		if( $curArray <= (count($allPass) -1) && $curArray <= (count($allClue) -1) && $curArray <= (count($allPass) -1) )
		{
			$database->query("UPDATE `tcg_games` SET `game_current_array`=$curArray+1 WHERE `game_set`='Set B' AND `game_type` IN ('image', 'text')");
		}
		
		else
		{
			$database->query("UPDATE `tcg_games` SET `game_current_array`=0 WHERE `game_set`='Set B' AND `game_type` IN ('image', 'text')");
		}
	}
}

// Grant wishes
$wLimit = $settings->getValue('xtra_wishes');
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Weekly'");
$wish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Pending' AND `wish_date` <= '".$range['gup_date']."'");
if( $wish['wish_date'] <= $range['gup_date'] )
{
	$database->query("UPDATE `user_wishes` SET `wish_status`='Granted', `wish_date`='$today' WHERE `wish_status`='Pending' AND `wish_date` <= '".$range['gup_date']."' ORDER BY `wish_id` ASC LIMIT $wLimit");
}

// Release decks
$eWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='$today' AND `wish_type`='5' LIMIT 1");
if( $eWish['wish_type'] == "5" )
{
	$dLimit = $settings->getValue('xtra_decks') * 2;
	$database->query("UPDATE `tcg_cards` SET `card_status`='Active', `card_released`='$today', `card_votes`='0' WHERE `card_status`='Upcoming' AND `card_votes` >= 2 ORDER BY `card_votes` DESC LIMIT $dLimit");
}

else
{
	$dLimit = $settings->getValue('xtra_decks');
	$database->query("UPDATE `tcg_cards` SET `card_status`='Active', `card_released`='$today', `card_votes`='0' WHERE `card_status`='Upcoming' AND `card_votes` >= 2 ORDER BY `card_votes` DESC LIMIT $dLimit");
}

// START CREATING THE AUTOMATIC WEEKLY UPDATE
$count = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_released`='$today'");

// Get released decks
$getDecks = $database->query("SELECT card_released, 
	GROUP_CONCAT(card_filename ORDER BY card_filename ASC SEPARATOR ', ')
	FROM tcg_cards
	WHERE card_released='$today'
	GROUP BY card_released");
$row = mysqli_fetch_array( $getDecks );
$decks = $row['1'];

// Get new members
$dateToday = date("Y-m-d", strtotime($range['gup_date']));
$weekAgo = date("Y-m-d", strtotime("-1 week"));
$getUsers = $database->query("SELECT usr_name,
	GROUP_CONCAT(usr_name ORDER BY usr_name ASC SEPARATOR ', ')
	FROM user_list
	WHERE usr_status='Active' AND usr_reg BETWEEN '$weekAgo' AND '$dateToday'
	ORDER BY usr_reg ASC");
$row = mysqli_fetch_array( $getUsers );
if( !empty( $row['1'] ) )
{
	$users = $row['1'];
}
else
{
	$users = "None";
}

// Get referrals
$getRefers = $database->query("SELECT GROUP_CONCAT(usr_refer, ' (x', count SEPARATOR '), ') string
	FROM (SELECT usr_refer,
		COUNT(usr_refer) COUNT
		FROM user_list
		WHERE usr_status='Active' AND usr_reg BETWEEN '$weekAgo' AND '$today'
	GROUP BY usr_refer ASC) x");
$row = mysqli_fetch_array( $getRefers );

if( !empty( $row['string'] ) )
{
	$refers = $row['string'].')';
}
else
{
	$refers = "None";
}

// Get masteries
$getMasters = $database->query("SELECT act_name,
	GROUP_CONCAT(act_slug ORDER BY act_slug ASC SEPARATOR ', ') string
	FROM tcg_activities
	WHERE act_type='master' AND act_date BETWEEN '$weekAgo' AND '$today'
	GROUP BY act_name ASC");
if( !$getMasters )
{
	echo 'There was an error pulling up the results';
}
else
{
	$mas = '';
	while( $row = mysqli_fetch_assoc( $getMasters ) )
	{
		$mas .= $row['act_name'].' ('.$row['string'].'); ';
	}
	$mas = substr($mas, 0, -2);

	if( !empty( $mas ) )
	{
		$masters = $mas;
	}
	else
	{
		$masters = "None";
	}
}

// Get level ups
$getLevels = $database->query("SELECT act_slug,
	GROUP_CONCAT(act_name ORDER BY act_name ASC SEPARATOR ', ') string
	FROM tcg_activities
	WHERE act_type='level' AND act_date BETWEEN '$weekAgo' AND '$today'
	GROUP BY act_slug ORDER BY act_name ASC");
if( !$getLevels )
{
	echo 'There was an error pulling up the results';
}
else
{
	$lvl = '';
	while( $row = mysqli_fetch_assoc( $getLevels ) )
	{
		$lvl .= $row['string'].' ('.$row['act_slug'].'); ';
	}
	$lvl = substr($lvl, 0, -2);

	if( !empty( $lvl ) )
	{
		$levels = $lvl;
	}
	else
	{
		$levels = "None";
	}
}

// Get affiliates
$getAff = $database->query("SELECT aff_subject, aff_url
	FROM tcg_affiliates
	WHERE aff_date BETWEEN '$weekAgo' AND '$today'
	ORDER BY aff_subject ASC");
if( !$getAff )
{
	echo 'There was an error pulling up the results';
}
else
{
	$aff = '';
	while( $row = mysqli_fetch_assoc( $getAff ) )
	{
		$aff .= '<a href="'.$row['aff_url'].'" target="_blank">'.$row['aff_subject'].'</a>, ';
	}
	$aff = substr($aff, 0, -2);

	if( !empty( $aff ) )
	{
		$affiliates = $aff;
	}

	else
	{
		$affiliates = "None";
	}
}

// Get games
$getGames = $database->query("SELECT `gup_set` FROM `tcg_games_updater` WHERE `gup_date`='$today' ORDER BY `gup_set` DESC");
if( !$getGames )
{
	echo 'There was an error pulling up the results';
}
else
{
	$games = '';
	while( $row = mysqli_fetch_assoc( $getGames ) )
	{
		$games .= $row['gup_set'].', ';
	}
	$games = substr($games, 0, -2);

	if( !empty($games) )
	{
		$game = $games;
	}
	else { $game = "None"; }
}

/**
 * Change your update placeholder that will show up
 * if you cannot make a decent update at midnight.
 */
$entry = $settings->getValue( 'update_text' );
$entry = nl2br($entry);
$entry = str_replace("'","\'",$entry);
$title = $settings->getValue( 'update_title' );
$hashed = htmlentities($sanitize->for_db($title));
$auth = $settings->getValue( 'tcg_owner' );

$chkWish = $database->num_rows("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."'");
if( $chkWish == 0 )
{
	$wishVar = 'None';
}
else
{
	$wishVar = 'Yes';
}

if( $settings->getValue( 'update_status' ) == "Published" )
{
	$postStat = "Published";
}
else {
	$postStat = "Draft";
}

$database->query("INSERT INTO `tcg_blog` (`post_auth`,`post_icon`,`post_title`,`post_date`,`post_member`,`post_master`,`post_level`,`post_affiliate`,`post_referral`,`post_game`,`post_deck`,`post_amount`,`post_wish`,`post_entry`,`post_status`) VALUES ('$auth','icon00.jpg','$title','$today','$users','$masters','$levels','$affiliates','$refers','$game','$decks','$count','$wishVar','$entry','$postStat')");

/* Update deck votes of upcoming decks back to 0
 */
$database->query("UPDATE `tcg_cards` SET `card_votes`='0' WHERE `card_status`='Upcoming'");




######################################
###### GAME UPDATES AND REWARDS ######
######################################
// Get new round for Higher or Lower
$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active'");
$min = 1; $max = mysqli_num_rows($query);
for( $i = 0; $i < 1; $i++ )
{
	mysqli_data_seek($query,rand($min,$max)-1);
	$row = mysqli_fetch_assoc($query);
	$digits = rand(01,$row['card_count']);
	if( $digits < 10 )
	{
		$digit = "0$digits";
	}
	else
	{
		$digit = $digits;
	}
	$file = $row['card_filename'];
	$num = $digit;
}
$database->query("INSERT INTO `game_hol_cards` (`hol_filename`,`hol_number`,`hol_date`) VALUES ('$file','$num','$today')");

// Pass rewards for Higher or Lower
$money = explode(" | ", $games->gameCurArr('higher-lower'));
$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='$today' AND `wish_set`='".$games->gameSet('higher-lower')."'");
$get1 = $database->query("SELECT * FROM `game_hol_logs` WHERE `hol_guess`='higher' AND `hol_date` BETWEEN '$weekAgo' AND '$today'");
$get2 = $database->query("SELECT * FROM `game_hol_logs` WHERE `hol_guess`='lower' AND `hol_date` BETWEEN '$weekAgo' AND '$today'");
$from = $database->get_assoc("SELECT * FROM `game_hol_cards` WHERE `hol_date`='$weekAgo'");
$curr = $database->get_assoc("SELECT * FROM `game_hol_cards` WHERE `hol_date`='$today'");
if( $curr['hol_number'] > $from['hol_number'] )
{
	while( $row = mysqli_fetch_assoc( $get1 ) ){
		if( $getWish['wish_set'] == $games->gameSet('higher-lower') )
		{
			$rTotal = $games->gameRandArr('higher-lower') * 2;
			foreach( $money as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('".$row['hol_name']."','Games','(Higher or Lower)','No','$rTotal','$mTotal','$today')");
		}

		else
		{
			$rTotal = $games->gameRandArr('higher-lower');
			$mTotal = $games->gameCurArr('higher-lower');
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('".$row['hol_name']."','Games','(Higher or Lower)','No','$rTotal','$mTotal','$today')");
		}
	}
}

else
{
	while( $row = mysqli_fetch_assoc( $get2 ) )
	{
		if( $getWish['wish_set'] == $games->gameSet('higher-lower') )
		{
			$rTotal = $games->gameRandArr('higher-lower') * 2;
			foreach( $money as $m ) { $mTotal[] = $m * 2; }
			$mTotal = implode(" | ", $mTotal);
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('".$row['hol_name']."','Games','(Higher or Lower)','No','$rTotal','$mTotal','$today')");
		}

		else
		{
			$rTotal = $games->gameRandArr('higher-lower');
			$mTotal = $games->gameCurArr('higher-lower');
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('".$row['hol_name']."','Games','(Higher or Lower)','No','$rTotal','$mTotal','$today')");
		}
	}
}
// Delete Higher or Lower logs from two weeks ago
$database->query("DELETE FROM `game_hol_logs` WHERE `hol_date` < DATE_SUB(NOW(), INTERVAL 14 DAY)");



// Get new round for Card Claim
$database->query("DELETE FROM `game_cclaim_cards`");
$cclaim = $database->query("SELECT * FROM `tcg_cards` WHERE `cards_status`='Active'");
$min = 1; $max = mysqli_num_rows($cclaim); $claims = null;
for( $i = 0; $i < $settings->getValue('xtra_cclaim'); $i++ )
{
	mysqli_data_seek($cclaim,rand($min,$max)-1);
	$row = mysqli_fetch_assoc($cclaim);
	$digits = rand(01,$row['card_count']);
	if($digits < 10)
	{
		$digit = "0$digits";
	}
	else
	{
		$digit = $digits;
	}
	$card = $row['card_filename'].''.$digit;
	$claims .= "('".$card."'),";
}
$claims = substr_replace($claims,"",-1);
$database->query("INSERT INTO `game_cclaim_cards` (`cclaim_cards`) VALUES $claims");

// Delete Card Claim logs from two weeks ago
$database->query("DELETE FROM `game_cclaim_logs` WHERE `cclaim_date` < DATE_SUB(NOW(), INTERVAL 14 DAY)");



// Get new round for Melting Pot
$database->query("DELETE FROM `game_mpot_cards`");
$mpot = $database->query("SELECT * FROM `tcg_cards` WHERE `cards_status`='Active'");
$min = 1; $max = mysqli_num_rows($mpot); $pots = null;
for( $i = 0; $i < $settings->getValue('xtra_mpot'); $i++ )
{
	mysqli_data_seek($mpot,rand($min,$max)-1);
	$row = mysqli_fetch_assoc($mpot);
	$digits = rand(01,$row['card_count']);
	if($digits < 10)
	{
		$digit = "0$digits";
	}
	else
	{
		$digit = $digits;
	}
	$card = $row['card_filename'].''.$digit;
	$pots .= "('".$card."'),";
}
$pots = substr_replace($pots,"",-1);
$database->query("INSERT INTO `game_mpot_cards` (`mpot_card`) VALUES $pots");

// Delete Melting Pot logs from two weeks ago
$database->query("DELETE FROM `game_mpot_logs` WHERE `mpot_date` < DATE_SUB(NOW(), INTERVAL 14 DAY)");




################################
###### OTHER AUTO UPDATES ######
################################
// Generate new member of the week
if( $settings->getValue( 'xtra_motm' ) == 0 ) {}
else
{
	// Check if MOTM scope is weekly
	if( $settings->getValue( 'xtra_motm_scope' ) == "Week" )
	{
		$getMOTM = $database->get_assoc("SELECT * FROM `game_motm_list` WHERE `motm_vote` >= '2' ORDER BY `motm_vote` DESC LIMIT 1");
		$database->query("INSERT INTO `game_motm_logs` (`motm_name`,`motm_date`) VALUES ('".$getMOTM['motm_name']."','$today')");
		// Reset votes to 0 after inclusion
		$database->query("UPDATE `game_motm_list` SET `motm_vote`='0'");
	}
}
?>