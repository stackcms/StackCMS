<?php
/*****************************************
 * Module:			Deck Masteries
 * Description:		Process user masteries
 */


// Run arrays of rewards
$choice = explode(", ", $settings->getValue( 'prize_master_choice' ));
$random = explode(", ", $settings->getValue( 'prize_master_reg' ));
$money = explode(", ", $settings->getValue( 'prize_master_cur' ));
$array_count = count($choice);
$array_count .= count($random);
$array_count .= count($money);
for( $i=0; $i<=($array_count -1); $i++ )
{
	isset( $choice[$i] );
	isset( $random[$i] );
	isset( $money[$i] );
}


if( $sub == "select" )
{
	// Fetch selected deck
	$fetch1 = $_POST['select'];

	// Process full mastery form
	if( $act == "sent" )
	{
		// Fetch selected deck
		$fetch2 = $sanitize->for_db($_POST['mastered-deck']);

		// Get specific info for mastered deck
		$mdeck = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_filename`='".$fetch2."'");
		if( $mdeck['card_worth'] == "3" ){ $x = 2; }
		elseif( $mdeck['card_worth'] == "2" ){ $x = 1; }
		else{ $x = 0; }

		if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
		{
			exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
		}

		else
		{
			$id = $_POST['id'];
			$name = $sanitize->for_db($_POST['name']);
			$mastdeck = $sanitize->for_db($_POST['mastered-deck']);
			$new = $sanitize->for_db($_POST['new']);

			// Update user's masteries on their profile
			$rowMas1 = $database->query("SELECT * FROM `user_items` WHERE `itm_id`='$id'");
			while( $rowmas = mysqli_fetch_assoc($rowMas1) )
			{
				if( $rowmas['itm_masteries'] != "None" )
				{
					$mast1="$rowmas[itm_masteries], ";
				}
				else
				{
					$mast1="";
				}
			}
			$update = $database->query("UPDATE `user_items` SET `itm_masteries`='$mast1$mastdeck' WHERE `itm_id`='$id'");

			// Update card's masters on the page
			$rowMas2 = $database->query("SELECT * FROM `tcg_cards` WHERE `card_filename`='$mastdeck'");
			while( $rowmas2 = mysqli_fetch_assoc($rowMas2) )
			{
				if( $rowmas2['card_masters'] != "None")
				{
					$mast2="$rowmas2[card_masters], ";
				}
				else
				{
					$mast2="";
				}
			}
			$update2 = $database->query("UPDATE `tcg_cards` SET `card_masters`='$mast2$name' WHERE `card_filename`='$mastdeck'");

			$mast = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_filename`='$mastdeck'");

			// Do logs for activities recording
			$date = date("Y-m-d", strtotime("now"));
			$activity = '<span class="fas fa-flag-checkered" aria-hidden="true"></span> <a href="/members.php?id='.$name.'">'.$name.'</a> mastered the <a href="/cards.php?view=released&deck='.$mastdeck.'">'.$mast['card_deckname'].'</a> deck!';

			// Process masteries if all queries are correct
			if( $update === TRUE && $update2 === TRUE )
			{
				$database->query("UPDATE `user_list` SET `usr_deck`='$new' WHERE `usr_id`='$id'");
				$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_type`,`act_slug`,`act_date`) VALUES ('$name','$activity','master','$mastdeck','$date')");

				echo '<h1>Congrats!</h1>
				<p>Congratulations on mastering the <b><u>'.$mastdeck.'</u></b> deck, '.$name.'! Here are your rewards. If you have mastered more than one deck, please do not use the back button to fill out another form (you will receive the same random cards if you do). A copy of these rewards have been recorded on your on-site permanent activity logs.</p>

				<center>';
				$min = 1;
				$max = mysqli_num_rows($result);

				// Declare empty strings
				$rewards = null;
				$choices = null;
				$cW = null;
				$rW = null;

				for( $i=1; $i<=$choice[$x]; $i++ )
				{
					$card = "choice$i";
					$card2 = "choicenum$i";
					echo '<img src="'.$tcgcards.''.$_POST[$card].''.$_POST[$card2].'.'.$tcgext.'" /> ';
					$choices .= $_POST[$card].$_POST[$card2].", ";

					$cX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='".$_POST[$card]."'");
					$cW .= $cX['card_worth'].', ';
				}

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

					$rX = $database->get_assoc("SELECT `card_worth` FROM `tcg_cards` WHERE `card_filename`='$card2'");
					$rW .= $rX['card_worth'].', ';
					$rewards .= $card.", ";
				}

				// Calculate card worth for choice and random
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
				$curValue = explode(' | ', $money[$x]);
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
						$curLog .= str_repeat(substr_replace(', '.$curName[$i],"",-4), $curValue[$i]);
						$curImg .= '<img src="'.$tcgimg.''.$curName[$i].'"> [x'.$curValue[$i].'] ';
						$curCln .= ', +'.$curValue[$i].' '.$vtn;
						$curOld[$i] += $curValue[$i];
					}
					else {}
				}
				$total = implode(" | ", $curOld);

				echo $curImg;
				echo '<p><strong>Deck Mastery ('.$mastdeck.'):</strong> '.$choices.''.$rewards.''.$curCln.'</p>
				</center>';

				// Insert acquired data
				$today = date("Y-m-d", strtotime("now"));
				$newSet = $choices."".$rewards."".$curLog;
				$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Service','Deck Mastery','($mastdeck)','$newSet','$today')");
				$database->query("UPDATE `user_items` SET `itm_currency`='$total', `itm_cards`=itm_cards+'$tCards' WHERE `itm_name`='$player'");
			}

			else
			{
				echo '<h1>Error</h1>
				<p>It looks like there was an error in processing your mastery form. Send the information to '.$tcgemail.' and we will send you your rewards ASAP. Thank you and sorry for the inconvenience.</p>';
			}
		}
	}

	// Show new collecting and choice form
	else
	{
		// Get specific info for mastered deck
		$mdeck = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_filename`='".$fetch1."'");
		if( $mdeck['card_worth'] == "3" ){ $x = 2; }
		elseif( $mdeck['card_worth'] == "2" ){ $x = 1; }
		else{ $x = 0; }

		// Explode bombs
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

		echo '<h1>Master Form</h1>
		<p>Congratulations! You\'re almost there to master the <b><u>'.$fetch1.'</u></b> deck! But before submitting your masteries, please make sure that you have collected all <b>20 cards</b> of the deck and that none remains pending from your trade post.</p>
		<p><b>You will receive the following rewards:</b>
		<li class="spacer">- <b>'.$choice[$x].'</b> choice cards</li>
		<li class="spacer">- <b>'.$random[$x].'</b> random cards</li>';
		echo $arrayCur.'</p>

		<p><b>Please fill out one form for each mastered deck!</b></p>

		<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&sub=select&action=sent">
		<input type="hidden" name="id" value="'.$uid.'" />
		<input type="hidden" name="name" value="'.$player.'" />
		<input type="hidden" name="mastered-deck" value="'.$fetch1.'" />';
		for( $i=1; $i<=$random[$x]; $i++ )
		{
			echo '<input type="hidden" name="random'.$i.'" value="'; $general->randtype('Active','1'); echo '" />';
		}
		echo '<table cellspacing="3" width="100%" class="table table-sliced table-striped">
		<tbody>
		<tr>
			<td><b>New Collecting:</b></td>
			<td>
				<select name="new" class="custom-select">
				<option value="">-----</option>';
				$coll = $database->query("SELECT * FROM `tcg_cards` WHERE `card_mast`='Yes' AND `card_status`='Active' ORDER BY `card_filename` ASC");
				while( $col = mysqli_fetch_assoc( $coll ) )
				{
					echo '<option value="'.$col['card_filename'].'">'.$col['card_deckname'].' ('.$col['card_filename'].")</option>\n";
				} // end while
				echo '</select>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Choice Cards:</b></td>
			<td>';
			for( $i=1; $i<=$choice[$x]; $i++ )
			{
				echo '<div class="input-group mb-3">
				<select name="choice'.$i.'" class="custom-select">
				<option value="">---</option>';
				$dchoice = $database->query("SELECT * FROM `tcg_cards` WHERE `card_mast`='Yes' AND `card_status`='Active' ORDER BY `card_filename` ASC");
				while( $cho = mysqli_fetch_assoc( $dchoice ) )
				{
					$filename = stripslashes( $cho['card_filename'] );
					echo '<option value="'.$filename.'">'.$cho['card_deckname'].' ('.$filename.")</option>\n";
				}
				echo '</select>
				<div class="input-group-append">
                    <input type="text" name="choicenum'.$i.'" placeholder="00" class="form-control" maxlength="2" />
                </div>
                </div>';
			}
			echo '</td>
		</tr>
		</tbody>
		</table>
		<input type="submit" name="submit" class="btn btn-success" value="Send Mastery" /> 
		<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
		</form>';
	}
}

else
{
	// Show mastered deck selector form
	echo '<h1>Choose a Deck to Master</h1>
	<p>Please select the deck that you currently mastered using the form below:</p>
	<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&sub=select">
	<div class="input-group mb-3">
		<select name="select" id="select" class="form-control">
			<option value="">----- Select a deck -----</option>';
			$mast = $database->query("SELECT * FROM `tcg_cards` WHERE `card_mast`='Yes' AND `card_status`='Active' ORDER BY `card_filename` ASC");
			while ( $mas = mysqli_fetch_assoc( $mast ) )
			{
				echo '<option value="'.$mas['card_filename'].'">'.$mas['card_deckname'].' ('.$mas['card_filename'].")</option>\n";
			} // end while
		echo '</select> 
		<div class="input-group-append">
			<input type="submit" name="submit" class="btn btn-success" value="Send Mastery" /> 
			<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
		</div>
	</div>
	</form>';
}

?>