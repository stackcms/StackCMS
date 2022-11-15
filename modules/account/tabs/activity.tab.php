<?php
/********************************************
 * Tab:				User Activity
 * Description:		Display user's activities
 */

$activity = isset($_GET['ld']) ? $_GET['ld'] : null;
$logs = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$player' GROUP BY YEAR(log_date), MONTH(log_date) ORDER BY `log_date` DESC");
if( empty( $activity ) )
{
	echo '<h2>Activity Logs</h2>
	<p>Welcome to your permanent logs page! This log contains all of your activity during your sessions at '.$tcgname.'. <b>This may not be 100% accurate!</b> If you log out, all of these logs will still be here, but please do not rely solely on this tool to log your cards.</p>

	<div align="center" class="logLink">';
	while( $row = mysqli_fetch_assoc( $logs ) )
	{
		echo '<a href="'.$tcgurl.'account.php?ld='.date("Y-m", strtotime($row['log_date'])).'">'.date("F Y", strtotime($row['log_date'])).'</a>';
	} // end activity while
	echo '</div><br />';

	$file = $tcgpath.'modules/members/activity/'.$player.'.txt';
	if( file_exists( $file ) )
	{
		echo '<div align="right"><button onClick="window.location.href=\''.$tcgurl.'modules/members/activity/'.$player.'.txt\';" class="btn-default" title="View Archived Activity Logs">View Archived Activity Logs</button></div>';
	}
	else {}
}

else
{
	$show1 = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_date` LIKE '$activity-%' ORDER BY `log_date` DESC");
	echo '<h2>'.date("F Y", strtotime($activity)).'</h2>';

	//Put currency names in an array
	$currency = explode(', ', $settings->getValue('tcg_currency'));
	foreach( $currency as $c )
	{
		$currencyNames[] = substr($c, 0, -4);
	}

	$timestamp = '';
	while( $row = mysqli_fetch_assoc( $show1 ) )
	{
		$rewards = explode(', ', $row['log_rewards']);

		// Declare empty strings
		$txtString = ''; 
		$curString = ''; 

		// Display cards for each reward if NOT a currency
		foreach( $rewards as $r )
		{
			if( !empty($currencyNames) && !in_array($r, $currencyNames, FALSE) )
			{
				$txtString .= $r.', ';
			}
		}

		// Get count of how many of each reward is present
		$values = array_count_values($rewards);

		// Display currencies that are in rewards and quantity only if exists in rewards
		foreach( $currencyNames as $cn )
		{
			if( array_key_exists($cn, $values) )
			{
				// Pluralize the currencies if more than 1
				if( $values[$cn] > 1 )
				{
					$var = substr($cn, -1);
					if( $var == "y" )
					{
						$vtn = substr_replace($cn,"ies",-1);
					}
					else if( $var == "o" )
					{
						$vtn = substr_replace($cn,"oes",-1);
					}
					else
					{
						$vtn = $cn.'s';
					}
				}
				else
				{
					$vtn = $cn;
				}
				$curString .= ', +'.$values[$cn].' '.$vtn;
			}
		}

		// Display text of rewarded cards
		$txtString = substr_replace($txtString,"",-2);

		if( $row['log_date'] != $timestamp )
		{
			echo '<br /><b>'.date('F d, Y', strtotime($row['log_date'])).' -----</b><br/>';
			$timestamp = $row['log_date'];
		}
		echo '<li class="spacer">- <i><b>'.$row['log_title'];
		if( empty( $row['log_subtitle'] ) ) {}
		else{
			echo ' '.$row['log_subtitle'];
		}
		echo ':</b></i> '.$txtString.''.$curString.'</li>';
	} // end while
}
?>