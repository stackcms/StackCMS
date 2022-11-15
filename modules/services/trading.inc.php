<?php
/**********************************************
 * Module:			Trading Rewards
 * Description:		Process user trading rewards
 */


// Add trade logs form
if( $act == "add-trades" )
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit( "<p>You did not press the submit button; this page should not be accessed directly.</p>" );
	}

	else
	{
		$name = $sanitize->for_db($_POST['name']);
		$out = htmlspecialchars(strip_tags($_POST['out']));
		$inc = htmlspecialchars(strip_tags($_POST['inc']));
		$to = htmlspecialchars(strip_tags($_POST['to']));
		$date = $_POST['date'];

		$total = explode(",", $out);
		$total = count($total);

		$result = $database->query("INSERT INTO `user_trades` (`trd_name`,`trd_trader`,`trd_out`,`trd_inc`,`trd_date`) VALUES ('$name','$to','$out','$inc','$date')") or print("Can't insert into table trades_$name.<br />" . $result . "<br />Error:" . mysqli_connect_error($result));

		// Insert acquired data
		if( $result === TRUE )
		{
			$database->query("UPDATE `user_trades_rec` SET `trd_points`=trd_points+'$total', `trd_date`='$date' WHERE `trd_name`='$name'");
			echo '<h1>Trade Logs Added</h1>
			<p>Your external trading logs has been added to the database!</p>';
		}
        
        else
		{
			echo '<h1>Trade Logs Error</h1>
			<p>It seems that there was a problem processing your trade logs form. Kindly send your information to <a href="mailto:'.$tcgemail.'">'.$tcgemail.'</a> or through our Discord server. Thank you and we apologize for the inconvenience.</p>';
		}
	}
} // end add trade logs form process



// Redeem trade rewards
else if ( $act == "redeem" )
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit( "<p>You did not press the submit button; this page should not be accessed directly.</p>" );
	}

	else
	{
		$sets = $sanitize->for_db($_POST['sets']);
		$name = $sanitize->for_db($_POST['name']);
		$diff = 25*$sets;

		$update = $database->query("UPDATE `user_trades_rec` SET `trd_points`=trd_points-'$diff', `trd_turnins`=trd_turnins+'$sets', `trd_redeems`=trd_redeems+'$diff' WHERE `trd_name`='$name'") or print("Can't insert into table user_trades.<br />" . $update . "<br />Error:" . mysqli_connect_error($update));

		// Process form if queries are correct
		if( $update === TRUE )
		{
			echo '<h1>Redeem Rewards</h1>
			<p>Get your redeemed rewards for '.$sets.' stamp cards below!</p><center>';
			$min = 1;
			$max = mysqli_num_rows($result);
			$total = $settings->getValue( 'prize_trade_reg' ) * $sets;

			// Declare empty strings
			$rewards = null;
			$rW = null;

			// Explode all bombs
			$curValue = explode(' | ', $settings->getValue( 'prize_trade_cur' ));
			$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
			$curOld = explode(' | ', $general->getItem( 'itm_currency' ));
			$curLog = ''; $curImg = ''; $curCln = '';
			for( $i=0; $i<count($curValue); $i++ )
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
					$curLog .= str_repeat(substr_replace(', '.$curName[$i],"",-4), $curValue[$i] * $sets);
					$curImg .= '<img src="'.$tcgimg.''.$curName[$i].'"> [x'.$curValue[$i] * $sets.']';
					$curCln .= ', +'.$curValue[$i] * $sets.' '.$vtn;
					$curOld[$i] += $curValue[$i] * $sets;
				}
				else {}
			}
			$total2 = implode(" | ", $curOld);

			for( $i=0; $i<$total; $i++ )
			{
				mysqli_data_seek($result,rand($min,$max)-1);
				$row = mysqli_fetch_assoc($result);
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
				echo '<img src="'.$tcgcards.''.$card.'.png" border="0" /> ';
				$rewards .= $card.", ";

				$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
				$rW .= $rX['card_worth'].', ';
			}
			$rewards = substr_replace($rewards,"",-2);

			// Count card worth of choice and random
			$rW = substr_replace($rW,"",-2);
			$rArr = explode(", ", $rW);
			$rSum = 0;
			foreach( $rArr as $val ) { $rSum += $val; }

			echo $curImg;
			echo '<p><strong>Trade Points (x'.$sets.'):</strong> '.$rewards.', '.$curCln.'</p>
			</center>';

			// Insert acquired data
			$today = date("Y-m-d", strtotime("now"));
			$newSet = $rewards.''.$curLog;
			$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$name','Service','Trade Points','(x$sets)','$newSet','$today')");
			$database->query("UPDATE `user_items` SET `itm_currency`='$total2', `itm_cards`=itm_cards+'$rSum' WHERE `itm_name`='$name'");
		}

		else
		{
			echo '<h1>Trading Rewards: Error</h1>
			<p>It seems that there was a problem processing your trade logs form. Kindly send your information to <a href="mailto:'.$tcgemail.'">'.$tcgemail.'</a> or through our Discord server. Thank you and we apologize for the inconvenience.</p>';
		}
	}
} // end redeem trade rewards form process



// Show default trading rewards page
else
{
	$chk = $database->get_assoc("SELECT * FROM `user_trades_rec` WHERE `trd_name`='$player'");
	if( $chk['trd_points'] < 25 )
	{
		echo '<h1>Add Trade Logs</h1>
		<table width="100%">
		<tr>
			<td width="38%" valign="top">
				<p>You don\'t have enough cards traded on your on-site trade logs, hence, a fewer amount of trade points! Kindly add your external trade logs first before claiming a new set of rewards. Do not worry about counting the cards that you have traded away, the system will automatically count it for you!</p>
				<p>- Make sure to <u>add ONLY the logs that you haven\'t turned in yet.</u></p>
			</td>

			<td width="2%"></td>

			<td width="70%" valign="top">
				<center>
				<div class="box-info">You now have a total worth of <b>'.$chk['trd_points'].'</b> trading points on your record.</div>
				</center>
				<br />

				<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=add-trades">
				<input type="hidden" name="name" value="'.$player.'" />
				<table width="100%" cellspacing="3" class="table table-sliced table-striped">
				<tbody>
				<tr>
					<td width="15%"><b>Date:</b></td>
					<td width="45%">
						<input type="date" name="date" />
					</td>
				</tr>
				<tr>
					<td><b>Traded With:</b></td>
					<td>
						<select name="to" style="width: 98%;" />';
						$mem = $database->query("SELECT `usr_name` FROM `user_list` ORDER BY `usr_name` ASC");
						while( $mr = mysqli_fetch_assoc( $mem ) )
						{
							$name = stripslashes($mr['usr_name']);
							echo '<option value="'.$name.'">'.$name."</option>\n";
						}
						echo '</select>
					</td>
				</tr>
				<tr>
					<td><b>Outgoing:</b></td>
					<td><input type="text" name="out" placeholder="e.g. blackcats04, rubies10, mc-'.$player.'" style="width:90%;" /></td>
				</tr>
				<tr>
					<td><b>Incoming:</b></td>
					<td><input type="text" name="inc" placeholder="e.g. tigers11, winter17, mc-Player" style="width:90%;" /></td>
				</tr>
				</tbody>
				</table>
				<input type="submit" name="submit" class="btn-success" value="Send Logs" /> 
				<input type="reset" name="reset" class="btn-danger" value="Reset" />
				</form>
			</td>
		</tr>
		</table>';
	}

	else if( $sub == "add-logs" )
	{
		echo '<h1>Add Trade Logs</h1>
		<table width="100%">
		<tr>
			<td width="38%" valign="top">
				<p>You don\'t have enough cards traded on your on-site trade logs, hence, a fewer amount of trade points! Kindly add your external trade logs first before claiming a new set of rewards. Do not worry about counting the cards that you have traded away, the system will automatically count it for you!</p>
				<p>- Make sure to <u>add ONLY the logs that you haven\'t turned in yet.</u></p>
			</td>

			<td width="2%"></td>

			<td width="70%" valign="top">
				<center>
				<div class="box-info">You now have a total worth of <b>'.$chk['trd_points'].'</b> trading points on your record.</div>
				</center>
				<br />

				<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=add-trades">
				<input type="hidden" name="name" value="'.$player.'" />
				<table width="100%" cellspacing="3" class="table table-sliced table-striped">
				<tbody>
				<tr>
					<td width="15%"><b>Date:</b></td>
					<td width="45%">
						<input type="date" name="date" />
					</td>
				</tr>
				<tr>
					<td><b>Traded With:</b></td>
					<td>
						<select name="to" style="width: 98%;" />';
						$mem = $database->query("SELECT `usr_name` FROM `user_list` ORDER BY `usr_name` ASC");
						while( $mr = mysqli_fetch_assoc( $mem ) )
						{
							$name = stripslashes($mr['usr_name']);
							echo '<option value="'.$name.'">'.$name."</option>\n";
						}
						echo '</select>
					</td>
				</tr>
				<tr>
					<td><b>Outgoing:</b></td>
					<td><input type="text" name="out" placeholder="e.g. blackcats04, rubies10, mc-'.$player.'" style="width:90%;" /></td>
				</tr>
				<tr>
					<td><b>Incoming:</b></td>
					<td><input type="text" name="inc" placeholder="e.g. tigers11, winter17, mc-Player" style="width:90%;" /></td>
				</tr>
				</tbody>
				</table>
				<input type="submit" name="submit" class="btn-success" value="Send Logs" /> 
				<input type="reset" name="reset" class="btn-danger" value="Reset" />
				</form>
			</td>
		</tr>
		</table>';
	}

	else {
		// Explode bombs
		$curValue = explode(' | ', $settings->getValue( 'prize_trade_cur' ));
		$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
		foreach( $curValue as $key => $value )
		{
			$tn = substr_replace($curName[$key],"",-4);
			if( $curValue[$key] > 1 )
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

			if( $curValue[$key] == 0 ) {}
			else
			{
				$arrayCur[] = '<li class="spacer">- <b>'.$curValue[$key].'</b> '.$tn.'</li>';
			}
		}
		// Fix all bombs after explosions
		$arrayCur = implode(" ", $arrayCur);

		// Get total stamp cards
		function getPoints($divisor, $dividend)
		{
			$quotient = (int)($divisor / $dividend);
			$remainder = $divisor % $dividend;
			return array( $quotient, $remainder );
		}
		list($quotient, $remainder) = getPoints($chk['trd_points'], 25);

		echo '<h1>Trading Rewards</h1>
		<table width="100%">
		<tr>
			<td width="38%" valign="top">
				<p>You currently have a total of <b>'.$chk['trd_points'].'</b> trade points on your record!</p>
				<p>Please keep in mind that the form automatically counts the total stamp cards that you can redeem based on your trade points. Hence, you can\'t change how many stamp cards you\'ll be redeeming.</p>
			</td>

			<td width="2%"></td>

			<td width="70%" valign="top">
				<p><b>You will receive the following rewards:</b>
				<li class="spacer">- <b>'.$settings->getValue('prize_trade_reg').'</b> random cards for each stamp cards</li>';
				echo $arrayCur.'<br />
				<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=redeem">
				<input type="hidden" name="name" value="'.$player.'" />
				<table width="100%" cellspacing="3" class="table table-sliced table-striped">
				<tbody>
				<tr>
					<td width="15%"><b>Stamp Cards:</b></td>
					<td width="35%"><input type="text" name="sets" style="width:90%" value="'.$quotient.'" readonly /></td>
				</tr>
				</tbody>
				</table>
				<input type="submit" name="submit" class="btn-success" value="Redeem my trading rewards!" />
				<button type="button" onclick="window.location.href=\''.$tcgurl.'services.php?form='.$form.'&sub=add-logs\';" class="btn-primary">Or add more logs</button>
				</form>
			</td>
		</tr>
		</table>';
	}
}
?>