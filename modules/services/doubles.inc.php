<?php
/************************************************
 * Module:			Doubles Exchange
 * Description:		Process user doubles exchange
 */


if( $act == "sent" )
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit( "<p>You did not press the submit button; this page should not be accessed directly.</p>" );
	}

	else
	{
		$from = $sanitize->for_db($_POST['sender']);
		$to = $sanitize->for_db($_POST['recipient']);
		$cards = htmlspecialchars(strip_tags($_POST['cards']));
		$date = date("Y-m-d H:i:s", strtotime("now"));

		$total = explode(",", $cards);
		$total = count($total);

		$message = "Hello, ".$tcgowner."! I have exchanged the following doubles for ".$total." random cards:\n".$cards."\nMany thanks!";

		$insert = $database->query("INSERT INTO `user_mbox` (`msg_subject`,`msg_text`,`msg_sender`,`msg_recipient`,`msg_box_from`,`msg_box_to`,`msg_see_from`,`msg_see_to`,`msg_del_from`,`msg_del_to`,`msg_origin`,`msg_date`) VALUES ('Doubles Exchange','$message','$from','$to','Out','In','0','1','0','0','','$date')");

		// Process form if queries are correct
		if( !$insert )
		{
			echo '<h1>Doubles Exchange : Error</h1>
			<p>It looks like there was an error in processing your doubles form. Send the information to '.$tcgemail.' and we will send you your doubles ASAP. Thank you and sorry for the inconvenience.</p> '.mysqli_error($insert);
		}

		else
		{
			echo '<h1>Doubles Exchange : Pick Up</h1>
			<p>Thanks for trading in your double cards. Below are your cards! Don\'t forget to take down your doubled cards and log them.</p>

			<center>';
			$min = 1; $max = mysqli_num_rows($result); $rewards = null;
			for($i=0; $i<$total; $i++)
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
				echo '<img src="'.$tcgcards.''.$card.'.png" border="0" /> ';
				$rewards .= $card.", ";
			}
			$rewards = substr_replace($rewards,"",-2);

			echo '<p><strong>Doubles Exchange (x'.$total.' cards):</strong> '.$rewards.'</p>
			</center>';

			// Insert acquired data
			$today = date("Y-m-d", strtotime("now"));
			$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$from','Service','Doubles Exchange','(x$total cards)','$rewards','$today')");
		}
	}
} // end form process

else
{
	echo '<h1>Doubles Exchange</h1>
	<p>Welcome to the Doubles Exchange services, a place where you can <b>swap in any cards you have more than one copy of</b>! If you have multiple copies of the same card with no one to trade them out to, you can use this service to refresh your trade post and get rid of the cards that no one seems to want.</p>
	<p>Do keep in mind that <u>only duplicates from your <b>trade pile</b> count</u>! This means, you must have at least 2 copies of a single card that does not include cards you have already mastered and/or cards you are currently keeping.</p>
	<p><b>Example:</b> If I have 2 copies of autumn01 from my trade pile, I can exchange one copy of it. However, if I have 2 copies of autumn01, 1 from my keeping and 1 from my trade pile, it won\'t be eligible for an exchange since I can trade it out easily.</p>
	<ul><li>You can exchange as much doubles you want, as long as you have two copies of the same card from your trade pile.</li>
	<li>Make sure to separate the cards with commas, then followed by a space to help the script count the cards you are exchanging with.</li></ul>

	<form method="post" action="'.$tcgurl.'services.php?form='.$form.'&action=sent">
	<input type="hidden" name="sender" value="'.$player.'" />
	<input type="hidden" name="recipient" value="'.$tcgowner.'" />
	<textarea name="cards" rows="3" style="width:95%;" /></textarea><br /> 
	<input type="submit" name="submit" class="btn-success" value="Exchange" />
	<input type="reset" name="reset" class="btn-danger" value="Reset" />
	</form>';
}
?>