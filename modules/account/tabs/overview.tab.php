<?php
/**************************************************
 * Tab:				Profile Overview
 * Description:		Display user's profile overview
 */

echo '<h2>Welcome back, '.$row['usr_name'].'!</h2>
<table width="100%">
<tr>
	<td width="20%" align="center" valign="top">';

	if( $row['usr_mcard'] == "Yes" )
	{
		echo '<img src="'.$tcgcards.'mc-'.$row['usr_name'].'.'.$tcgext.'" />';
	}

	else
	{
		echo '<img src="'.$tcgcards.'mc-filler.'.$tcgext.'" />';
	}

	echo '<br /><br />'.$arrayCell;
	echo '</td>
	<td width="2%"></td>
	<td width="78%" valign="top">
		<p>Welcome to your member panel, <strong>'.$row['usr_name'].'</strong>! From here you can submit various forms, edit your info, and play all of the games here at '.$tcgname.'!</p>';

	if( $row['usr_status'] == "Pending" )
	{
		echo '<p>It looks like you recently joined '.$tcgname.' and your account hasn\'t been activated yet. You must be approved by an adminstrator before you can fully access the TCG. Your account should be activated soon. If you joined more than 2 weeks ago and haven\'t received your activation email, please email us at <a href="mailto:'.$tcgemail.'">'.$tcgemail.'</a></p>';
	}

	else if( $row['usr_status'] == "Hiatus" )
	{
		echo '<p>It looks like you have set your status to Hiatus. In order to play games here you must reactivate your account. This is self-service, and to do so, go to <a href="'.$tcgurl.'account.php?do=edit-information">Edit Information</a> and set your status to Active.</p>';
	}

	// Check for daily login rewards
	if( $settings->getValue( 'prize_daily_en' ) == 0 ) {}
	else
	{
		date_default_timezone_set($settings->getValue('tcg_timezone'));
		$logToday = date("Y-m-d", strtotime("now"));
		$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_date`='$logToday' AND `log_title`='Daily Login'");
		if( $logChk['log_date'] == $logToday )
		{
			echo '<center><div class="box-warning">Your daily login bonus for today has been logged to your permanent activity logs!<br />Below is your copy in case you missed it:<br /><br />';
			$rewards = explode(', ',$logChk['log_rewards']);
			$curName = explode(', ', $settings->getValue('tcg_currency'));
			// Put currency names in an array
			foreach($curName as $c)
			{
				$currencyNames[] = substr($c, 0, -4);
			}

			// Declare empty strings
			$imgString = ''; 
			$txtString = ''; 
			$curString = ''; 
			$curImgString = '';

			// Display images for each reward if NOT a currency
			foreach( $rewards as $r )
			{
				if( !in_array($r, $currencyNames) )
				{
					$imgString .= '<img src="'.$tcgcards.''.$r.'.png" title="'.$r.'"> ';
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
					$curImgString .= '<img src="'.$tcgimg.''.$cn.'.png" title="'.$cn.'"> [x'.$values[$cn].'] ';

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

					$curString .= '+'.$values[$cn].' '.$vtn.', ';
				}
			}

			// Display images and text of rewards
			$curString = substr_replace($curString,"",-2);
			echo $imgString.' '.$curImgString;
			echo '<br>';
			echo '<b>'.$logChk['log_title'].' '.$logChk['log_subtitle'].':</b> '.$txtString.' '.$curString.'</div></center>';
		}


		else
		{
			echo '<center><div class="box-success">Here is your daily login bonus for today!<br /><br />';
			$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_worth`='1'");
			$min = 1; $max = mysqli_num_rows($query); $rewards = null; $rW = null;
			for($i=0; $i<$settings->getValue('prize_daily_reg'); $i++)
			{
				mysqli_data_seek($query,rand($min,$max)-1);
				$cRow = mysqli_fetch_assoc($query);
				$digits = rand(01,$cRow['card_count']);
				if( $digits < 10 )
				{
					$digit = "0$digits";
				}
				else
				{
					$digit = $digits;
				}
				$card = $cRow['card_filename'].''.$digit;
				$card2 = $cRow['card_filename'];
				echo '<img src="'.$tcgcards.''.$card.'.'.$tcgext.'" border="0" /> ';

				$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
				$rW .= $rX['card_worth'].', ';
				$rewards .= $card.", ";
			}
			$rewards = substr_replace($rewards,"",-2);
			$rW = substr_replace($rW,"",-2);
			$rArr = explode(", ", $rW);
			$rSum = 0;
			foreach( $rArr as $val ) { $rSum += $val; }

			// Explode all bombs
			$curValue = explode(' | ', $settings->getValue( 'prize_daily_cur' ));
			$curItem = explode(' | ', $general->getItem( 'itm_currency' ));
			$curName = explode(', ', $settings->getValue( 'tcg_currency' ));

			$curLog = ''; $curImg = ''; $curCln = '';
			for($i=0; $i<count($curValue); $i++)
			{
				$cn = substr_replace($curName[$i],"",-4);

				// Pluralize the currencies if more than 1
				if( $curValue[$i] > 1 )
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

				if( $curValue[$i] != 0 )
				{
					$curLog .= str_repeat(substr_replace($curName[$i],"",-4).', ', $curValue[$i]);
					$curImg .= '<img src="/images/'.$curName[$i].'"> [x'.$curValue[$i].']';
					$curCln .= '+'.$curValue[$i].' '.$vtn.', ';
					$curItem[$i] += $curValue[$i];
				}
				else {}
			}
			$total = implode(" | ", $curItem);
			$curCln = substr_replace($curCln,"",-2);
			$curLog = substr_replace($curLog,"",-2);

			echo $curImg;
			echo '<br /><b>Daily Login ('.$logToday.'):</b> '.$rewards.', '.$curCln.'</div></center>';

			$newSet = $rewards.", ".$curLog;
			$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Service','Daily Login','($logToday)','$newSet','$logToday')");
			$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$rSum' WHERE `itm_name`='$player'");
		}
	}

	echo '<h3>Player Status</h3>
	<table width="100%" cellspacing="5" border="0" class="table table-sliced table-striped">
	<tbody>
		<tr><td width="35%" align="right"><b>Status:</b></td><td width="2%"></td><td width="63%">'.$row['usr_status'].'</td></tr>
		<tr><td align="right"><b>Rank:</b></td><td></td><td>Level '.$row['usr_level'].' ('.$lvlName['lvl_name'].')</td></tr>
		<tr><td align="right"><b>Collecting:</b></td><td></td><td><a href="'.$tcgurl.'cards.php?view=released&deck='.$row['usr_deck'].'">'.$row['usr_deck'].'</a></td></tr>
		<tr><td align="right"><b>Card Worth:</b></td><td></td><td>'.$general->getItem('itm_cards').'</td></tr>
		<tr><td align="right"><b>Unique Masteries:</b></td><td></td><td>';
		if( $general->getItem( 'itm_masteries' ) == 'None' )
		{
			echo '0';
		}
		else
		{
			$exp = explode(', ', $general->getItem('itm_masteries'));
			echo count(array_unique($exp));
		}
		echo '</td></tr>
		<tr><td align="right"><b>Total Masteries:</b></td><td></td><td>';
		if( $general->getItem( 'itm_masteries' ) == 'None' )
		{
			echo '0';
		}
		else
		{
			$arr = explode(', ', $general->getItem( 'itm_masteries' ));
			echo count($arr);
		}
		echo '</td></tr>
		</tbody>
		</table>

		<h3>Trading Status</h3>
		<table width="100%" cellspacing="5" border="0" class="table table-sliced table-striped">
		<tbody>
			<tr><td width="35%" align="right"><b>Current Points:</b></td><td width="2%"></td><td width="63%">'.$trd['trd_points'].'</td></tr>
			<tr><td align="right"><b>Redeemed Points:</b></td><td></td><td>'.$trd['trd_redeems'].'</td></tr>
			<tr><td align="right"><b>Total Turnins:</b></td><td></td><td>'.$trd['trd_turnins'].'</td></tr>
		</tbody>
		</table>

		<h3>Other Information</h3>
		<table width="100%" cellspacing="5" border="0" class="table table-sliced table-striped">
		<tbody>
			<tr><td width="35%" align="right"><b>Birthday:</b></td><td width="2%"></td><td width="63%">'.date("F d", strtotime($row['usr_bday'])).'</td></tr>
			<tr><td align="right"><b>Registered:</b></td><td></td><td>'.date("F d, Y", strtotime($row['usr_reg'])).'</td></tr>
			<tr><td align="right"><b>Last Login:</b></td><td></td><td>'.date("F d, Y", strtotime($row['usr_sess'])).' at '.date("h:i A", strtotime($row['usr_sess'])).'</td></tr>
		</tbody>
		</table>

		<h2>About Me</h2>';
		if( $row['usr_level'] < 10 )
		{
			$level = '0'.$row['usr_level'];
		}
		else
		{
			$level = $row['usr_level'];
		}
		echo '<p><img src="'.$tcgimg.'badges/'.$general->getItem('itm_badge').'-'.$level.'.'.$tcgext.'" title="Level '.$row['usr_level'].'" align="left" style="margin-right: 10px;" />'.$about.'</p>
	</td>
	</tr>
</table>';
?>