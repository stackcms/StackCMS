<?php
/*********************************************
 * Module:			Releases Main
 * Description:		Display releases main page
 */


// Check if user already pulled
if( $logChk['log_subtitle'] == "(".$date.")" )
{
	echo '<h1>Update Pulls ('.$date.') : Halt!</h1>
	<p>You have already pulled cards from this update! If you missed your pulls, here they are:</p>
	<center>';

	$logs = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_subtitle`='($date)'");
	while( $row = mysqli_fetch_assoc( $logs ) )
	{
		echo '<b>'.$row['log_title'].' '.$row['log_subtitle'].':</b> '.$row['log_rewards'].'<br />';
	}
}

else
{
	$getData = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_date`='$date'");
	echo '<h1>Update Pulls : '.$getData['post_date'].'</h1>
	<table width="100%">
	<tr>
		<td width="50%" valign="top">
			<center>';

			$decks = $getData['post_deck'];
			$array = explode(', ',$decks);
			$array_count = count($array);
			for( $i=0; $i<=($array_count -1); $i++ )
			{
				$digits = rand(01,20);
				if($digits < 10)
				{
					$digit = "0".$digits;
				}

				else
				{
					$digit = $digits;
				}
				echo '<a href="'.$tcgurl.'cards.php?view=released&deck='.$array[$i].'"><img src="'.$tcgcards.''.$array[$i].''.$digit.'.'.$tcgext.'" border="0" /></a>';
			}

			echo '</center>

			<ul>
				<li>You can grab a total worth of <b>'.$row['post_amount'].'</b> cards from this release but not more than <b>2</b> cards per deck.</li>
				<li><u>You can only submit your pulls once</u>. Be sure of your choices before submitting.</li>
				<li>Your choices are added to your activity log and cannot be changed.</li>
				<li>Do not forget to comment with what you have taken!</li>
			</ul>
		</td>

		<td width="2%"></td>

		<td width="48%" valign="top">
			<center>Select the decks you want to pull for this release below:</br />
			<form method="post" action="'.$tcgurl.'releases.php?date='.$date.'&go=pulled">
				<input type="hidden" value="'.$user['usr_name'].'">
				<table width="100%" class="table table-sliced table-striped">
				<tbody><tr>
					<td align="right" width="25%" valign="top"><b>Regular Pulls:</b></td>
					<td width="75%">';

					$check0 = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_date`='$date'");
					for( $i=1; $i<=$check0['post_amount']; $i++ )
					{
						echo '<select name="pull'.$i.'" style="width:83%;">
						<option value="">---</option>';

						$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_released`='$date' ORDER BY `card_filename` ASC");
						while( $row = mysqli_fetch_assoc( $query ) )
						{
							$filename = stripslashes($row['card_filename']);
							echo '<option value="'.$filename.'">'.$row['card_deckname'].' ('.$filename.')</option>';
						} // end while

						echo '</select> 
						<input type="text" name="pullnum'.$i.'" placeholder="00" size="1" maxlength="2" /><br />';
					} // end for

					echo '</td>
				</tr></tbody>
				</table>';

				$dname = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_released`='$date' AND `card_donator`='$player'");
				$mname = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_released`='$date' AND `card_maker`='$player'");

				// Check if user is donator
				if( $dname['card_donator'] == $player )
				{
					echo '<p>Select your extra pulls for the decks you have donated below:<br />';
					$check1 = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_released`='$date' AND `card_donator`='$player'");
					echo '<input type="hidden" name="donator_amount" value="'.$check1.'" />
					<table width="100%" class="table table-sliced table-striped">
					<tbody><tr>
						<td align="right" width="25%" valign="top"><b>Donator Pulls:</b></td>
						<td width="75%"><center>Take only <b>one card</b> from each <u>donated decks</u>:</center>';

						for( $i=1; $i<=$check1; $i++ )
						{
							echo '<select name="donator'.$i.'" style="width:83%;">
								<option value="">---</option>';

								$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_released`='$date' AND `card_donator`='$player' ORDER BY `card_filename` ASC");
								while( $row = mysqli_fetch_assoc( $query ) )
								{
									$filename = stripslashes($row['card_filename']);
									echo '<option value="'.$filename.'">'.$row['card_deckname'].' ('.$filename.')</option>';
								} // end while

							echo '</select> 
							<input type="text" name="donatornum'.$i.'" placeholder="00" size="1" maxlength="2" /><br />';
						} // end for

						echo '</td>
					</tr></tbody>
					</table></p>';
				} // end donator check

				// Check if user is maker
				if( $mname['card_maker'] == $player )
				{
					echo '<p>Select your extra pulls for the decks you have made below:<br />';
					$check2 = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_released`='$date' AND `card_maker`='$player'");
					echo '<input type="hidden" name="maker_amount" value="'.$check2.'" />
					<table width="100%" class="table table-sliced table-striped">
					<tbody><tr>
						<td align="right" width="25%" valign="top"><b>Maker Pulls:</b></td>
						<td width="75%"><center>Take only <b>one card</b> from each <u>decks made</u>:</center>';

						for( $i=1; $i<=$check2; $i++ )
						{
							echo '<select name="maker'.$i.'" style="width:83%;">
								<option value="">---</option>';

								$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_released`='$date' AND `card_maker`='$player' ORDER BY `card_filename` ASC");
								while( $row = mysqli_fetch_assoc( $query ) )
								{
									$filename = stripslashes($row['card_filename']);
									echo '<option value="'.$filename.'">'.$row['card_deckname'].' ('.$filename.')</option>';
								} // end while

							echo '</select> 
							<input type="text" name="makernum'.$i.'" placeholder="00" size="1" maxlength="2" /><br />';
						} // end for

						echo '</td>
					</tr></tbody>
					</table></p>';
				} // end maker check

				echo '<input type="submit" name="submit" class="btn-success" value="Claim Pulls" /> 
				<input type="reset" name="reset" class="btn-danger" value="Reset" />
			</form>
			</center>
		</td>
	</tr>
	</table>';
}
?>