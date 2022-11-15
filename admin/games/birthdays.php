<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('birthdays')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('birthdays')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
	if( $logDATE >= $ranDATE )
	{
		echo '<h1>'.$games->gameTitle('birthdays').' : Halt!</h1>
		<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
		$general->displayRewards('birthdays');
		echo '</center>';
	}
	
	else
	{
		$row = $database->get_assoc("SELECT `usr_bday` FROM `user_list` WHERE `usr_name`='$player'");
		$date = date("m", strtotime("now"));
		$bday = date("m", strtotime($row['usr_bday']));

		if( $bday != $date )
		{
			echo '<h1>'.$games->gameSet('birthdays').' - '.$games->gameTitle('birthdays').'</h1>
			<!-- CHANGE THE BLURBS -->
			<p class="heading"><img src="'.$tcgurl.'admin/games/images/birthdays.jpg" align="left" width="200" style="border-radius:150px;margin-right: 20px;" />Please don\'t make us sadder than sad as we can\'t be fooled easily! It is obviously not your birthday month yet... We know that everyone is excited for their own birthday presents, but you are a century early! Please hibernate for the mean time and check back in on your birthday month, which is this coming <i>'.date("F", strtotime($row['usr_bday'])).'</i>.</p>
			<p>If you\'re looking for more cards, you can still play some games if you haven\'t yet or trade with fellow traders. Because right now isn\'t the right time to get your presents, sorry!</p>
			<p>&nbsp;</p>';
		}

		else
		{
			if( empty($go) )
			{
?>
<h1><?php echo $games->gameSet('birthdays'); ?> - <?php echo $games->gameTitle('birthdays'); ?></h1>
<img src="<?php echo $tcgurl; ?>admin/games/images/birthdays.jpg" align="left" style="margin-right: 20px;" />
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('birthdays'); ?>
<center>
<form method="post" action="<?php echo $tcgurl; ?>games.php?play=birthdays&go=presents">
<input type="hidden" name="sender" value="<?php echo $player; ?>" />
<input type="hidden" name="recipient" value="<?php echo $tcgowner; ?>" />
<table width="55%" class="table table-sliced table-striped">
<tbody>
	<tr>
		<td valign="top" class="headLine">Choice Cards:</td>
		<td class="tableBody">
		<?php
		for( $i=1; $i<=$games->gameChoiceArr('birthdays'); $i++ )
		{
			echo '<select name="choice'.$i.'" style="width: 82%;">
				<option value="">---</option>';
				$choice = $database->query("SELECT * FROM `tcg_cards` WHERE `card_mast`='Yes' AND `card_status`='Active' ORDER BY `card_filename` ASC");
				while( $cho = mysqli_fetch_assoc( $choice ) )
				{
					$filename = stripslashes($cho['card_filename']);
					echo '<option value="'.$filename.'">'.$cho['card_deckname'].' ('.$filename.")</option>\n";
				}
			echo '</select> 
			<input type="text" name="num'.$i.'" placeholder="00" size="1" maxlength="2" /><br />';
		}
		?>
		</td>
	</tr>
	<tr>
		<td align="right"><b>Image URL:</b></td>
		<td><i><small>For your milestone badge</small></i><br /><input type="text" name="image" placeholder="Link to your image" style="width:90%;" /></td>
	</tr>
</tbody>
</table>
<div align="right"><input type="submit" name="submit" class="btn-success" value="Claim Presents!" /></div>
</form>
</center>
<?php
			}

			else if( $go == "presents" )
			{
				if( !isset( $_SERVER['HTTP_REFERER'] ) )
				{
					echo $ForbiddenAccess;
				}

				else
				{
					$img = $sanitize->for_db($_POST['image']);
					$to = $sanitize->for_db($_POST['recipient']);
					$from = $sanitize->for_db($_POST['sender']);
					$date = date("Y-m-d H:i:s", strtotime("now"));

					$message = "Hello, ".$tcgowner."! ".$from." just sent you their image to use for their birthday milestone badge!\nImage URL: ".$img."\n\nMake sure to make their badge before the month ends!";

					$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('Birthday Milestone','$message','$from','$to','Out','In','0','1','0','0','','$date')");

					if( !$insert )
					{
						echo '<p>There was an error processing your form and the mail was not sent.</p> '.mysqli_error($insert);
					}

					else
					{
						echo '<h1>'.$games->gameTitle('birthdays').' - Prize Pickup</h1>';
						echo '<center><p>Happy birthday! Take everything you see below and don\'t forget to log it!</p>';
						$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('birthdays')."'");
						if( $getWish['wish_set'] == $games->gameSet('birthdays') )
						{
							$choice = explode(", ", $games->gameChoiceArr('birthdays'));
							$random = explode(", ", $games->gameRandArr('birthdays'));
							$currency = explode(" | ", $games->gameCurArr('birthdays'));
							foreach( $choice as $c ) { $cTotal = $c * 2; }
							foreach( $random as $r ) { $rTotal = $r * 2; }
							foreach( $currency as $m ) { $mTotal[] = $m * 2; }
							$mTotal = implode(" | ", $mTotal);
							if( $games->gameSub('birthdays') == '' )
							{
								$general->gamePrize($games->gameSet('birthdays'),$games->gameTitle('birthdays'),$games->gameSub('birthdays'),$rTotal,$cTotal,$mTotal);
							}
							else
							{
								$general->gamePrize($games->gameSet('birthdays'),$games->gameTitle('birthdays'),'('.$games->gameSub('birthdays').')',$rTotal,$cTotal,$mTotal);
							}
						}

						else
						{
							$cTotal = $games->gameChoiceArr('birthdays');
							$rTotal = $games->gameRandArr('birthdays');
							$mTotal = $games->gameCurArr('birthdays');
							if( $games->gameSub('birthdays') == '' )
							{
								$general->gamePrize($games->gameSet('birthdays'),$games->gameTitle('birthdays'),$games->gameSub('birthdays'),$rTotal,$cTotal,$mTotal);
							}
							else
							{
								$general->gamePrize($games->gameSet('birthdays'),$games->gameTitle('birthdays'),'('.$games->gameSub('birthdays').')',$rTotal,$cTotal,$mTotal);
							}
						}
					}
				}
			}
		}
	}
}
?>