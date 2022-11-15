<?php
/***********************************************
 * Tab:				Activity Log
 * Description:		Display user's activity logs
 */


echo '<h3>Activity Logs</h3>
<div style="text-align: justify; padding-right: 20px; margin-top: 20px; line-height: 20px; font-size: 14px; overflow: auto; height: 300px;">';

//Put currency names in an array
$currencies = explode(', ',$settings->getValue('tcg_currency'));
foreach( $currencies as $c )
{
	$currencyNames[] = substr($c, 0, -4);
}

$timestamp = '';
while( $row = mysqli_fetch_assoc( $log1 ) )
{
	$rewards = explode(', ',$row['log_rewards']);

	// Declare empty strings
	$txtString = ''; 
	$curString = ''; 

	// Display cards for each reward if NOT a currency
	foreach( $rewards as $r )
	{
		if( !in_array($r, $currencyNames) )
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
	echo '<li class="spacer">- <b>'.$row['log_title'];

	if( empty($row['log_subtitle']) ) {}
	else{
		echo ' '.$row['log_subtitle'];
	}
	echo ':</b> '.$txtString.''.$curString.'</li>';
} // end activity logs

echo '</div><br />';

$file = $tcgpath.'modules/members/activity/'.$player.'.txt';
if( file_exists( $file ) )
{
	echo '<div align="right"><button onClick="window.location.href=\''.$tcgurl.'modules/members/activity/'.$player.'.txt\';" class="btn-default" title="View Archived Activity Logs">View Archived Activity Logs</button></div><br /><br />';
}
else {}
?>