<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('motm-reward')."'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('motm-reward')."' AND `log_date` >= '".$range['gup_date']."'");

$logDATE = isset($logChk['log_date']) ? $logChk['log_date'] : null;
$ranDATE = isset($range['gup_date']) ? $range['gup_date'] : null;

if( $logDATE >= $ranDATE )
{
	echo '<h1>'.$games->gameTitle('motm-reward').' : Halt!</h1>
	<center><p>You have already played this game! If you missed your rewards, here they are:</p>';
	$general->displayRewards('motm-reward');
	echo '</center>';
}

else
{
	$row = $database->get_assoc("SELECT * FROM `game_motm_logs` WHERE `motm_name`='$player'");
	$date = date("m", strtotime("now"));
	$month = date("m", strtotime($row['motm_date']));

	if( $month != $date )
	{
		echo '<h1>'.$games->gameSet('motm-reward').' - '.$games->gameTitle('motm-reward').'</h1>
		<!-- CHANGE THE BLURBS -->
		<p class="heading"><img src="/admin/games/images/motm.jpg" align="left" width="200" style="border-radius:150px;margin-right: 20px;" />Oh no, how unfortunate? But you are not the member of this month! We\'re sure you have been voted for, but there was someone who got more votes than you. Please check back next time and maybe it\'s your lucky month!</p>
		<p>If you\'re looking for more cards, you can still play some games if you haven\'t yet or trade with fellow traders. Because right now isn\'t the right time to get your presents, sorry!</p>
		<p>&nbsp;</p>';
	}

	else
	{
		if( empty( $go ) )
		{
?>
<h1><?php echo $games->gameSet('motm-reward'); ?> - <?php echo $games->gameTitle('motm-reward'); ?></h1>
<img src="<?php echo $tcgurl; ?>admin/games/images/motm.jpg" align="left" style="margin-right: 20px;" />
<!-- CHANGE THE BLURBS -->
<?php echo $games->gameBlurb('motm-reward'); ?>
<center>
<form method="post" action="<?php echo $tcgurl; ?>games.php?play=motm&go=presents">
<input type="hidden" name="sender" value="<?php echo $player; ?>" />
<input type="hidden" name="recipient" value="<?php echo $tcgowner; ?>" />
<table width="55%" class="table table-sliced table-striped">
<tbody>
	<tr>
		<td valign="top" class="headLine">Choice Cards:</td>
		<td class="tableBody">
		<?php
		for( $i=1; $i<=$games->gameChoiceArr( 'motm-reward' ); $i++ )
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
				$month = date("F", strtotime("now"));

				$message = "Hello, ".$tcgowner."! ".$from." just sent you their image to use for their birthday milestone badge!\nImage URL: ".$img."\n\nMake sure to make their badge before the month ends!";

				$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('Featured Member','$message','$from','$to','Out','In','0','1','0','0','','$date')");

				if( !$insert )
				{
					echo '<p>There was an error processing your form and the mail was not sent.</p>';
				}

				else
				{
					echo '<h1>'.$games->gameTitle('motm-reward').' - Prize Pickup</h1>';
					echo '<center><p>Happy birthday! Take everything you see below and don\'t forget to log it!</p>';
					$getWish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='".$range['gup_date']."' AND `wish_set`='".$games->gameSet('motm-reward')."'");
					if( $getWish['wish_set'] == $games->gameSet( 'motm-reward' ) )
					{
						$choice = explode(", ", $games->gameChoiceArr('motm-reward'));
						$random = explode(", ", $games->gameRandArr('motm-reward'));
						$currency = explode(" | ", $games->gameCurArr('motm-reward'));
						foreach( $choice as $c ) { $cTotal = $c * 2; }
						foreach( $random as $r ) { $rTotal = $r * 2; }
						foreach( $currency as $m ) { $mTotal[] = $m * 2; }
						$mTotal = implode(" | ", $mTotal);
						$general->gamePrize($games->gameSet('motm-reward'),$games->gameTitle('motm-reward'),'('.$month.')',$rTotal,$cTotal,$mTotal);
					}

					else
					{
						$cTotal = $games->gameChoiceArr('motm-reward');
						$rTotal = $games->gameRandArr('motm-reward');
						$mTotal = $games->gameCurArr('motm-reward');
						$general->gamePrize($games->gameSet('motm-reward'),$games->gameTitle('motm-reward'),'('.$month.')',$rTotal,$cTotal,$mTotal);
					}
				}
			}
		}
	}
}
?>