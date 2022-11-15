<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);
@include($tcgpath.'themes/headers/acct-header.php');

// Check if user is logged in
if( empty( $login ) )
{
	header("Location: account.php?do=login");
	exit;
}

// Begin processing rewards
$id = isset($_GET['id']) ? $_GET['id'] : null;
$rwdCnt = $database->num_rows("SELECT * FROM `user_rewards` WHERE `rwd_name`='$player'");

if( empty( $id ) )
{
	echo '<h1>My Rewards</h1>
	<p>Hey, '.$player.'! You currently have <b>'.$rwdCnt.'</b> unclaimed rewards right now. Click on the rewards link to get them!</p>';

	$sql = $database->query("SELECT * FROM `user_rewards` WHERE `rwd_name`='$player' ORDER BY `rwd_id`");
	while( $get = mysqli_fetch_assoc( $sql ) )
	{
		echo '<li><a href="'.$tcgurl.'rewards.php?name='.$name.'&id='.$get['rwd_id'].'"><b>'.$get['rwd_type'].'</b> ';
		if( !empty( $get['rwd_subtitle'] ) )
		{
			echo $get['rwd_subtitle'];
		}
		else {}
		echo '</a> - received on '.date("F d, Y", strtotime($get['rwd_date'])).'</li>';
	}
}

else
{
	$sql = $database->get_assoc("SELECT * FROM `user_rewards` WHERE `rwd_name`='$player'");
	echo '<h1>Rewards : '.$sql['rwd_type'].'</h1>
	<p>Claim your '.$sql['rwd_type'].' rewards below and don\'t forget to log them on your trade post.</p>
	<center>';

	$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_worth`='1'");
	$min = 1; $max = mysqli_num_rows($query); $cards = $sql['rwd_cards'];

	// Declare empty strings
	$rewards = '';
	$rW = '';

	for( $i=0; $i<$cards; $i++ )
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
		$card = $row['card_filename'].''.$digit;
		$card2 = $row['card_filename'];
		echo '<img src="'.$tcgcards.''.$card.'.'.$tcgext.'" border="0" /> ';
			
		$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
		$rW .= isset($rX['card_worth']).', ';
		$rewards .= $card.", ";
	}

	if( $sql['rwd_mcard'] == "No" || $sql['rwd_mcard'] == "" ) {}
	else
	{
		echo '<img src="'.$tcgcards.'mc-'.$sql['rwd_name'].'.'.$tcgext.'" /> ';
	}

	if( $sql['rwd_mstone'] == "" || $sql['rwd_mstone'] == "None" ) {}
	else
	{
		echo '<img src="'.$tcgcards.''.str_replace(", ", ".".$tcgext."\" title=\"\"> <img src=\"$tcgcards", $sql['rwd_mstone']).'.'.$tcgext.'">';
	}
	$rewards = substr_replace($rewards,"",-2);

	// Count card worth of random cards
	$rW = substr_replace($rW,"",-2);
	$rArr = explode(", ", $rW);
	$rSum = 0;
	foreach( $rArr as $val ) { $rSum += (int)$val; }
		
	// Explode all bombs
	$curValue = explode(' | ', $sql['rwd_currency']);
	$curItem = explode(' | ', $general->getItem( 'itm_currency' ));
	$curName = explode(', ', $settings->getValue( 'tcg_currency' ));

	$curLog = ''; $curImg = ''; $curCln = '';
	for( $i=0; $i<count($curValue); $i++ )
	{
		$tn = substr_replace($curName[$i],"",-4);
		if( $curValue[$i] > 1 )
		{
			$var = substr($tn, -1);
			if( $var == "y" )
			{
				$tn = substr_replace($tn,"ies",-1);
			}
			else if( $var == "o" )
			{
				$tn = substr_replace($tn,"oes",-1);
			}
			else
			{
				$tn = $tn.'s';
			}
		}

		else
		{
			$tn = $tn;
		}

		if( $curValue[$i] != 0 )
		{
			//$curLog .= str_repeat(substr_replace(', '.$curName[$i],"",-4), $curValue[$i]);
			$curLog .= str_repeat(substr_replace(', '.$curName[$i],"",-4), $curValue[$i]);
			$curImg .= '<img src="'.$tcgimg.''.$curName[$i].'"> [x'.$curValue[$i].']';
			$curCln .= ', +'.$curValue[$i].' '.$tn;
			(int)$curItem[$i] += (int)$curValue[$i];
		}
		else {}
	}
	$total = implode(" | ", $curItem);

	// Show list of currency images if available
	echo $curImg;

	// Rewards log without MC/MS
	if( $sql['rwd_mcard'] == "No" || $sql['rwd_mcard'] == "No" && empty($sql['rwd_mstone']) || $sql['rwd_mstone'] == "None" )
	{
		echo '<p><strong>'.$sql['rwd_type'];
		if( !empty( $sql['rwd_subtitle'] ) )
		{
			echo ' '.$sql['rwd_subtitle'];
		}
		echo ':</strong> '.$rewards.''.$curCln.'</p></center>';
		$newSet = $rewards.''.$curLog;
	}
		
	// Rewards log with currencies and MC
	else if( $sql['rwd_mcard'] == "Yes" )
	{
		echo '<p><strong>'.$sql['rwd_type'];
		if( !empty( $sql['rwd_subtitle'] ) )
		{
			echo ' '.$sql['rwd_subtitle'];
		}
		echo ':</strong> mc-'.$sql['rwd_name'].', '.$rewards.''.$curCln.'</p></center>';
		$newSet = 'mc-'.$sql['rwd_name'].', '.$rewards.''.$curLog;
	}
		
	// Rewards log for milestone only
	else if( !empty( $sql['rwd_mstone'] ) )
	{
		echo '<p><strong>'.$sql['rwd_type'];
		if( !empty( $sql['rwd_subtitle'] ) )
		{
			echo ' '.$sql['rwd_subtitle'];
		}
		echo ':</strong> '.$sql['rwd_mstone'].'</p></center>';
		$newSet = $sql['rwd_mstone'];
	}
		
	// Rewards log for all
	else if( $sql['rwd_mcard'] == "Yes" && !empty( $sql['rwd_mstone'] ) )
	{
		echo '<p><strong>'.$sql['rwd_type'];
		if( !empty( $sql['rwd_subtitle'] ) )
		{
			echo ' '.$sql['rwd_subtitle'];
		}
		echo ':</strong> mc-'.$sql['rwd_name'].', '.$sql['rwd_mstone'].', '.$rewards.''.$curCln.'</p></center>';
		$newSet = 'mc-'.$sql['rwd_name'].', '.$sql['rwd_mstone'].', '.$rewards.''.$curLog;
	}

	// Insert acquired data
	$today = date("Y-m-d", strtotime("now"));
	$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('".$sql['rwd_name']."','Rewards','".$sql['rwd_type']."','".$sql['rwd_subtitle']."','$newSet','$today')");
	$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$rSum' WHERE `itm_name`='".$sql['rwd_name']."'");
	$database->query("DELETE FROM `user_rewards` WHERE `rwd_id`='".$sql['rwd_id']."'");
}

@include($tcgpath.'themes/headers/acct-footer.php');
@include($footer);
?>