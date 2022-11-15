<?php
/*************************************************
 * Module:			Special Masteries
 * Description:		Process user special masteries
 */


if( $act == "sent" )
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
	}

	else
	{
		$id = $sanitize->for_db($_POST['id']);
		$name = $sanitize->for_db($_POST['name']);
		$mastered = $sanitize->for_db($_POST['mastered']);
		$set = $sanitize->for_db($_POST['set']);

		if( $mastered == "mcard" )
		{
			$mcard = "Member Card";
		}

		else if( $mastered == "ecard" )
		{
			$mcard = "Event Card";
		}

		// Simplify settings value
		$spcreg = $settings->getValue( 'prize_special_reg' );
		$spccur = $settings->getValue( 'prize_special_cur' );

		// CHECK SETS FOR DUPLICATE VALUES
		$dup_check = $database->query("SELECT itm_name, itm_$mastered FROM `user_items` WHERE itm_name='$name'");
		if( mysqli_num_rows($dup_check) > 0 )
		{
			while( $row = mysqli_fetch_assoc( $dup_check ) )
			{
				$newset_array = explode(', ', $set);
				$newset_count = count($newset_array);
				$dontadd = 0;

				for( $i = 0; $i < $newset_count; $i++ )
				{
					$dontadd += substr_count($row[$mastered], $newset_array[$i]);
				}

				if( $dontadd == 0 )
				{
					$new = $row[$mastered].', '.$set;
					$database->query("UPDATE `user_items` SET `itm_$mastered`='$new' WHERE `itm_name`='$name'");

					// Declare empty strings
					$rewards = '';
					$curLog = '';
					$curImg = '';
					$curCln = '';
					$rw = '';

					echo '<h1>Special Mastery ('.$mcard.')</h1>';
					$min = 1; $max = mysqli_num_rows($result);
					for( $i = 0; $i < $spcreg; $i++ )
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

						$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
						$rW .= $rX['card_worth'].', ';
						$rewards .= $card.", ";
					}
					$rewards = substr_replace($rewards,"",-2);

					// Calculate card worth for random
					$rW = substr_replace($rW,"",-2);
					$rArr = explode(", ", $rW);

					$rSum = 0;
					foreach( $rArr as $val ) { $rSum += $val; }

					// Explode all bombs
					$curValue = explode(' | ', $settings->getValue( 'prize_special_cur' ));
					$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
					$curOld = explode(' | ', $general->getItem( 'itm_currency' ));

					for($i=0; $i<count($curValue); $i++)
					{
						// Pluralize the currencies if more than 1
						$cn = substr_replace($curName[$i],"",-4);
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
						}
						else {}
					}
					$total = implode(" | ", $curOld);

					echo $curImg;
					echo '<p><strong>Special Mastery ('.$mcard.'):</strong> '.$rewards.''.$curCln.'</p>
					</center>';

					// Insert acquired data
					$today = date("Y-m-d", strtotime("now"));
					$newSet = $rewards."".$curLog;
					$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Service','Special Mastery','($mcard)','$newSet','$today')");
					$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$rSum' WHERE `itm_name`='$player'");
				}

				else
				{
					echo 'Seems like you\'ve already used one of the cards on your submitted set. Please go back and recheck your cards.';
				}
			}
		}
	}
}

else
{
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_name`='$player'");
	echo '<h1>Special Mastery Form</h1>
	<p>If you have mastered <i>one set</i> of member or event cards, fill out the form below to receive your rewards.<br />
	<b>Please fill out one form for each mastered sets!</b><br />
	<b><u>1 set = 10 cards gained</u></b></p>
	<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=sent">
	<input type="hidden" name="id" value="'.$row['usr_id'].'" />
	<input type="hidden" name="name" value="'.$row['usr_name'].'" />
	<center><table cellspacing="3" width="80%" class="border">
	<tr>
		<td class="headLine" width="30%">Mastery Type:</td>
		<td class="tableBody"><input type="radio" name="mastered" value="mcard"> Member Card &nbsp; <input type="radio" name="mastered" value="ecard"> Event Card
	</tr>
	<tr>
		<td class="headLine" width="30%">Set Completed:</td>
		<td class="tableBody"><textarea name="set" rows="3" style="width:94%;"></textarea></td>
	</tr>
	<tr><td colspan="2" class="tableBody" align="center"><input type="submit" name="submit" class="btn-success" value="Send Special Mastery!" /></td></tr>
	</table><center>
	</form>';
}
?>