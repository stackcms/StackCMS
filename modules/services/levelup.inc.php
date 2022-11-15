<?php
/****************************************
 * Module:			Level Ups
 * Description:		Process user level up
 */

// Run arrays of rewards
$choice = explode(", ", $settings->getValue( 'prize_level_choice' ));
$random = explode(", ", $settings->getValue( 'prize_level_reg' ));
$money = explode(", ", $settings->getValue( 'prize_level_cur' ));
$array_count = count($choice);
$array_count .= count($random);
$array_count .= count($money);
for( $i=0; $i<=($array_count -1); $i++ )
{
	isset( $choice[$i] );
	isset( $random[$i] );
	isset( $money[$i] );
}


if ( $act == "sent" )
{
	if (!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST")
	{
		exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
	}

	else
	{
		$check->Value();
		$id = $sanitize->for_db($_POST['id']);
		$name = $sanitize->for_db($_POST['name']);
		$email = $sanitize->for_db($_POST['email']);
		$level = $sanitize->for_db($_POST['newlevel']);

		// Check level for activity recording
		$date = date("Y-m-d", strtotime("now"));
		$update = $database->query("UPDATE `user_list` SET `usr_level`='$level' WHERE `usr_id`='$id'");
		$lvlnow = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
		$lvlNew = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='$level'"); // Fetch new level
		$diff = $lvlnow['usr_level'] - 1;
		$lvlOld = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='$diff'");
		$lvlSlug = $lvlOld['lvl_name'] .' > '. $lvlNew['lvl_name'];
		$activity = '<span class="fas fa-level-up-alt" aria-hidden="true"></span> <a href="/members.php?id='.$name.'">'.$name.'</a> ranked up from '.$lvlOld['lvl_name'].' to '.$lvlNew['lvl_name'].'!';

		// Insert data if queries are correct
		if( $update === TRUE )
		{
			$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_type`,`act_slug`,`act_date`) VALUES ('$name','$activity','level','$lvlSlug','$date')");

			echo '<h1>Congrats!</h1>
			<p>Congrats on leveling up, '.$name.'! Here are your rewards. If you have leveled up more than once, please do not use the back button to fill out another form (you will receive the same random cards if you do). A copy of these rewards have been recorded on your on-site permanent activity logs.</p>

			<center>';
			$min=1; $max = mysqli_num_rows($result);
			
			// Declare empty strings
			$rewards = null;
			$choices = null;
			$cW = null;
			$rW = null;

			// Get level tiers
			$x = $lvlNew['lvl_tier'];

			//for( $i=1; $i<=$settings->getValue( 'prize_level_choice' ); $i++ )
			for( $i=1; $i<=$choice[$x]; $i++ )
			{
				$card = "choice$i";
				$card2 = "choicenum$i";
				echo '<img src="'.$tcgcards.''.$_POST[$card].''.$_POST[$card2].'.'.$tcgext.'" />';
				$choices .= $_POST[$card].$_POST[$card2].", ";

				$cX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='".$_POST[$card]."'");
				$cW .= $cX['card_worth'].', ';
			}

			//for( $i=0; $i<$settings->getValue('prize_level_reg'); $i++ )
			for( $i=0; $i<$random[$x]; $i++ )
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
				echo '<img src="'.$tcgcards.''.$card.'.'.$tcgext.'" border="0" /> ';
				$rewards .= $card.", ";

				$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
				$rW .= $rX['card_worth'].', ';
			}

			// Count card worth of choice and random
			$rewards = substr_replace($rewards,"",-2);
			$cW = substr_replace($cW,"",-2);
			$rW = substr_replace($rW,"",-2);
			$cArr = explode(", ", $cW);
			$rArr = explode(", ", $rW);

			$cSum = 0; $rSum = 0;
			foreach( $cArr as $val ) { $cSum += $val; }
			foreach( $rArr as $val ) { $rSum += $val; }
			$tCards = $cSum + $rSum;

			// Explode all bombs
			//$curValue = explode(' | ', $settings->getValue( 'prize_level_cur' ));
			$curValue = explode(' | ', $money[$x]);
			$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
			$curOld = explode(' | ', $general->getItem( 'itm_currency' ));

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
					$curLog .= str_repeat(substr_replace(', '.$curName[$i],"",-4), $curValue[$i]);
					$curImg .= '<img src="'.$tcgimg.''.$curName[$i].'"> [x'.$curValue[$i].']';
					$curCln .= ', +'.$curValue[$i].' '.$vtn;
					$curOld[$i] += $curValue[$i];
				} else {}
			}
			$total = implode(" | ", $curOld);

			echo $curImg;
			echo '<p><strong>Level Up ('.$level.'. '.$lvlNew['lvl_name'].'):</strong> '.$choices.''.$rewards.''.$curCln.'</p>
			</center>';

			// Insert acquired data
			$today = date("Y-m-d", strtotime("now"));
			$newSet = $choices."".$rewards."".$curLog;
			$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Service','Level Up','(".$level.". ".$lvlNew['lvl_name'].")','$newSet','$today')");
			$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$tCards' WHERE `itm_name`='$player'");
		}

		else
		{
			echo '<h1>Error</h1>
			<p>It looks like there was an error in processing your level up form. Send the information to '.$tcgemail.' and we will send you your rewards ASAP. Thank you and sorry for the inconvenience.</p>';
		}
	}
}


// Show level up form
else
{
	// Get level data
	$lvlCurrent = $row['usr_level'] + 1;
	$l = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='$lvlCurrent'");
	$x = $l['lvl_tier'];

	// Explode bombs
	//$curValue = explode(' | ', $settings->getValue( 'prize_level_cur' ));
	$curValue = explode(' | ', $money[$x]);
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

	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

	$sum = $row['usr_level'] + 1;
	$lvlC = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='$sum'");
	$lvl_cards = $lvlC['lvl_cards'];

	echo '<h1>Level Up Form</h1>
	<table width="100%">
	<tr>
	<td width="68%" valign="top">
		<p>Hey there, '.$player.'! As of now, you are currently at Level '.$row['usr_level'].'; which means, you need <b>'.$lvl_cards.' cards</b> to move on to the next level. The form already determines your next level, so you only need to select the choice of cards that you need at the moment.</p>';
		$items = $database->get_assoc("SELECT * FROM `user_items` WHERE `itm_name`='$player'");
		if( $items['itm_cards'] < $lvl_cards )
		{
			echo '<p><b>You haven\'t reached the required worth of cards yet!</b> Please come back again once you have gained at least '.$lvl_cards.' card worth to level up. Thank you!</p>';
		}

		else
		{
			echo '<p><b>Please fill out one form for each level up!</b></p>
			<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=sent">
			<input type="hidden" name="id" value="'.$row['usr_id'].'" />
			<input type="hidden" name="name" value="'.$row['usr_name'].'" />
			<input type="hidden" name="email" value="'.$row['usr_email'].'" />';
			for( $i=1; $i<=$random[$x]; $i++ )
			{
				echo '<input type="hidden" name="random'.$i.'" value="'; $general->randtype('Active','1'); echo '" />';
			}

			echo '<table cellspacing="3" width="100%" class="table table-sliced table-striped">
			<tbody>
			<tr>
				<td width="30%"><b>New Level:</b></td>
				<td>';
				if( $row['usr_level'] == "10" )
				{
					echo '<input type="text" name="newlevel" style="width:90%;" value="10" readonly /">';
				}

				else
				{
					echo '<select name="newlevel" style="width: 97%;">
					<option value="'.$l['lvl_id'].'">'.$l['lvl_name'].' (Level '.$l['lvl_id'].')</option>';
					echo '</select>';
				}
				echo '</td>
			</tr>
			<tr>
				<td valign="top"><b>Choice Cards:</b></td>
				<td>';
				for( $i=1; $i<=$choice[$x]; $i++ )
				{
					echo '<select name="choice'.$i.'" style="width: 80%;">
					<option value="">---</option>';
					$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_mast`='Yes' AND `card_status`='Active' ORDER BY `card_filename` ASC");
					while( $row2 = mysqli_fetch_assoc( $query ) )
					{
						$filename = stripslashes($row2['card_filename']);
						echo '<option value="'.$filename.'">'.$row2['card_deckname'].' ('.$filename.')</option>';
					}
					echo '</select> 
					<input type="text" name="choicenum'.$i.'" placeholder="00" style="width:15%;" maxlength="2" /><br />';
				}
				echo '</td>
			</tr>
			</tbody>
			</table>
			<input type="submit" name="submit" class="btn-success" value="Level Up" /> 
			<input type="reset" name="reset" class="btn-danger" value="Reset" />
			</form>';
		}
	echo '</td>

	<td width="2%"></td>

	<td width="30%" valign="top">
		<p><b>You will receive the following rewards:</b>
		<li class="spacer">- <b>'.$choice[$x].'</b> choice cards</li>
		<li class="spacer">- <b>'.$random[$x].'</b> random cards</li>';
		echo $arrayCur.'</p>
	</td>
	</tr>
	</table>';
}
?>