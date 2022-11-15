<?php
/********************************************************
 * Module:			Join Form
 * Description:		Show and process new user application
 */


// Check if TCG registration is on or off
if( $settings->getValue( 'tcg_registration' ) == "0" )
{
	echo '<h1>Registration : Closed!</h1>
	<p>We regret to inform you that '.$tcgname.' is currently closed for registration. If you want to join us, kindly check on us constantly and wait for us to open the TCG for you! Thank you so much for your interest!</p>';
}

// If registration is on, show form
else
{
	if( $stat == "sent" )
	{
		if( !isset( $_POST['submit'] ) || $_SERVER['REQUEST_METHOD'] != "POST" )
		{
			echo '<p>You did not press the submit button; this page should not be accessed directly.</p>';
		}

		else
		{
			$check->Member();
			if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,6})$/",strtolower($_POST['email'])) )
			{
				exit("<h1>Error</h1>\nThat e-mail address is not valid, please use another.<br /><br />");
			}

			$uname = $sanitize->for_db($_POST['username']);
			$uemail = $sanitize->for_db($_POST['email']);
			$uurl = $sanitize->for_db($_POST['url']);
			$urefer = $sanitize->for_db($_POST['refer']);
			$udeck = $sanitize->for_db($_POST['collecting']);
			$umcard = $sanitize->for_db($_POST['mcard']);
			$ubio = $sanitize->for_db($_POST['about']);
			$ubday = $_POST['date'];

			$pass = $_POST['password'];
			$password2 = $sanitize->for_db($_POST['password2']);
			$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
			$hash = md5( rand(0,1000) );

			$date = date('Y-m-d', strtotime("now"));
			$date2 = date('Y-m-d', strtotime("now"));

			echo '<h1>Welcome!</h1>
			<p>Welcome to '.$tcgname.'! Below is your starter pack. You will not be able to play games until you have been approved by the owner but you can take cards from updates posted on or after '.date("F j, Y").'.</p>

			<center>';

			// Declare empty strings
			$choice = null;
			$rand = null;
			$cW = null;
			$rW = null;

			for( $i=1; $i<=$settings->getValue( 'prize_start_choice' ); $i++ )
			{
				$card = "choice$i";
				echo '<img src="'.$tcgcards.''.$udeck;
				echo $_POST[$card];
				echo '.'.$tcgext.'" />';
				$choice .= $udeck.$_POST[$card].", ";
			}

			for( $i=1; $i<=$settings->getValue('prize_start_reg'); $i++ )
			{
				$card = "random$i";
				echo '<img src="'.$tcgcards;
				echo $_POST[$card];
				echo '.'.$tcgext.'" />';
				$rand .= $_POST[$card].", ";
			}
			echo '<br /><br />

			<b>Starter Pack:</b> ';
			$choice = substr_replace($choice,"",-2);
			$rand = substr_replace($rand,"",-2);
			echo $choice.', '.$rand.'</center>';

			$total = $settings->getValue('prize_start_choice') + $settings->getValue('prize_start_reg');

			$insert = $database->query("INSERT INTO `user_list` (`usr_name`,`usr_email`,`usr_url`,`usr_refer`,`usr_bday`,`usr_pass`,`usr_hash`,`usr_deck`,`usr_bio`,`usr_twitter`,`usr_discord`,`usr_reg`) VALUES ('$uname','$uemail','$uurl','$urefer','$ubday','$hashed_pass','$hash','$udeck','$ubio','N/A','N/A','$date')");

			// Use PHP send mail function (admin email)
			if( function_exists( 'mail' ) )
			{
				$email = "$tcgemail";
				$subject = "New Member";

				$message = "The following member has joined $tcgname: \n";
				$message .= "Name: $uname \n";
				$message .= "Email: $uemail \n";
				$message .= "Trade Post: $uurl \n";
				$message .= "Collecting: $udeck \n";
				$message .= "Referral: $urefer \n";
				$message .= "Birthday: $ubday \n";
				$message .= "Member Card: $umcard \n";
				$message .= "To add them to the approved member list, go to your admin panel.\n";

				$headers = "From: $uname <$uemail> \n";
				$headers .= "Reply-To: $uname <$uemail>";

				if( $insert === TRUE )
				{
					$currSP = explode(", ", $settings->getValue('tcg_currency'));
					$money = '';
					for( $j=0; $j<count($currSP); $j++ )
					{
						$money .= '0 | ';
					}
					$money = substr_replace($money,"",-2);

					$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$uname','Service','Starter Pack','$choice, $rand','$date2')");
					$database->query("INSERT INTO `user_trades_rec` (`trd_name`,`trd_date`) VALUES ('$uname','$date2')");
					$database->query("INSERT INTO `user_items` (`itm_name`,`itm_masteries`,`itm_milestone`,`itm_coupons`,`itm_merchandise`,`itm_cards`,`itm_currency`) VALUES ('$uname','None','None','None','None','$total','$money')");
					$database->query("INSERT INTO `game_motm_list` (`motm_name`,`motm_vote`) VALUES ('$uname','0')");

					// Referral rewards
					if( $refer == "None" ) {}
					else
					{
						$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_date`) VALUES ('$urefer','Services','(Referral)','No','1','$date2')");
					}

					// Send admin email for new members
					mail($email,$subject,$message,$headers);

					// Use PHP send mail function (user email)
					if( function_exists( 'mail' ) )
					{
						$email = "$uemail";
						$subject = $tcgname.": Starter Pack";
						$message = "Thanks for joining ".$tcgname.", ".$uname."! We are very excited that you are going to be joining us. Your account is currently pending approval, but you can begin playing games regardless.\n\nPlease click this link to activate your account:\n".$tcgurl."account.php?do=verify&email=".$uemail."&hash=".$hash."\n\nBelow is a copy of your starter pack, in case you did not pick it up from the site.\n\n";

						for( $i=1; $i<=$settings->getValue( 'prize_start_choice' ); $i++ )
						{
							$card = 'choice'.$i;
							$message .= $tcgcards.''.$udeck.''.$_POST[$card].'.'.$tcgext;
							$message .= "\n";
						}

						for( $i=1; $i<=$settings->getValue( 'prize_start_reg' ); $i++)
						{
							$card = 'random'.$i;
							$message .= $tcgcards.''.$_POST[$card].'.'.$tcgext;
							$message .= "\n";
						}

						$message .= "\nThanks again for joining and happy trading!\n\n";
						$message .= "-- $tcgowner\n";
						$message .= "$tcgname: $tcgurl\n";
						$headers = "From: $tcgname <$tcgemail> \n";
						$headers .= "Reply-To: $tcgname <$tcgemail>";
						mail($email,$subject,$message,$headers);
					}

					// Use Google SMTP if send mail doesn't exist (user email)
					else
					{
						$email = "$uemail";
						$name = "$uname";
						$subject = $tcgname.": Starter Pack";
						$message = "Thanks for joining ".$tcgname.", ".$uname."! We are very excited that you are going to be joining us. Your account is currently pending approval, but you can begin playing games regardless.<br /><br />Please click this link to activate your account:<br />".$tcgurl."account.php?do=verify&email=".$uemail."&hash=".$hash."<br /><br />Below is a copy of your starter pack, in case you did not pick it up from the site.<br /><br />";

						for( $i=1; $i<=$settings->getValue('prize_start_choice'); $i++ )
						{
							$card = 'choice'.$i;
							$message .= $tcgcards.''.$udeck.''.$_POST[$card].'.'.$tcgext.'<br />';
						}

						for( $i=1; $i<=$settings->getValue('prize_start_reg'); $i++ )
						{
							$card = 'random'.$i;
							$message .= $tcgcards.''.$_POST[$card].'.'.$tcgext.'<br />';
						}

						$message .= "<br />Thanks again for joining and happy trading!<br /><br />";
						$message .= "-- $tcgowner<br />";
						$message .= "$tcgname: $tcgurl<br />";

						@include($tcgpath.'admin/mail/index.php');
					}
				}
			}

			// Use Google SMTP if send mail doesn't exist (admin email)
			else if( !function_exists( 'mail' ) )
			{
				$email = "$tcgemail";
				$name = "$tcgowner";
				$subject = "New Member";

				$message = "The following member has joined $tcgname: \n";
				$message .= "Name: $uname\n";
				$message .= "Email: $uemail\n";
				$message .= "Trade Post: $uurl\n";
				$message .= "Collecting: $udeck\n";
				$message .= "Referral: $urefer\n";
				$message .= "Birthday: $ubday\n";
				$message .= "Member Card: $umcard\n";
				$message .= "To add them to the approved member list, go to your admin panel.\n";

				if( $insert === TRUE )
				{
					$currSP = explode(", ", $settings->getValue('tcg_currency'));
					$money = '';
					for( $j=0; $j<count($currSP); $j++ )
					{
						$money .= '0 | ';
					}
					$money = substr_replace($money,"",-2);

					$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$uname','Service','Starter Pack','$choice, $rand','$date2')");
					$database->query("INSERT INTO `user_trades_rec` (`trd_name`,`trd_date`) VALUES ('$uname','$date2')");
					$database->query("INSERT INTO `user_items` (`itm_name`,`itm_masteries`,`itm_milestone`,`itm_coupons`,`itm_merchandise`,`itm_cards`,`itm_currency`) VALUES ('$uname','None','None','None','None','$total','$money')");
					$database->query("INSERT INTO `game_motm_list` (`motm_name`,`motm_vote`) VALUES ('$uname','0')");

					// Referral rewards
					if( $refer == "None" ) {}
					else
					{
						$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_date`) VALUES ('$urefer','Services','(Referral)','No','1','$date2')");
					}

					// Send admin email for new members
					@include($tcgpath.'admin/mail/index.php');

					// Use PHP send mail function (user email)
					if( function_exists( 'mail' ) )
					{
						$email = "$uemail";
						$subject = $tcgname.": Starter Pack";
						$message = "Thanks for joining ".$tcgname.", ".$uname."! We are very excited that you are going to be joining us. Your account is currently pending approval, but you can begin playing games regardless.\n\nPlease click this link to activate your account:\n".$tcgurl."account.php?do=verify&email=".$uemail."&hash=".$hash."\n\nBelow is a copy of your starter pack, in case you did not pick it up from the site.\n\n";

						for( $i=1; $i<=$settings->getValue('prize_start_choice'); $i++ )
						{
							$card = 'choice'.$i;
							$message .= $tcgcards.''.$udeck.''.$_POST[$card].'.'.$tcgext;
							$message .= "\n";
						}

						for( $i=1; $i<=$settings->getValue('prize_start_reg'); $i++ )
						{
							$card = 'random'.$i;
							$message .= $tcgcards.''.$_POST[$card].'.'.$tcgext;
							$message .= "\n";
						}

						$message .= "\nThanks again for joining and happy trading!\n\n";
						$message .= "-- $tcgowner\n";
						$message .= "$tcgname: $tcgurl\n";
						$headers = "From: $tcgname <$tcgemail> \n";
						$headers .= "Reply-To: $tcgname <$tcgemail>";
						mail($email,$subject,$message,$headers);
					}

					// Use Google SMTP if send mail doesn't exist (user email)
					else
					{
						$email = "$uemail";
						$name = "$uname";
						$subject = $tcgname.": Starter Pack";
						$message = "Thanks for joining ".$tcgname.", ".$uname."! We are very excited that you are going to be joining us. Your account is currently pending approval, but you can begin playing games regardless.<br /><br />Please click this link to activate your account:<br />".$tcgurl."account.php?do=verify&email=".$uemail."&hash=".$hash."<br /><br />Below is a copy of your starter pack, in case you did not pick it up from the site.<br /><br />";

						for( $i=1; $i<=$settings->getValue( 'prize_start_choice' ); $i++)
						{
							$card = 'choice'.$i;
							$message .= $tcgcards.''.$udeck.''.$_POST[$card].'.'.$tcgext.'<br />';
						}

						for( $i=1; $i<=$settings->getValue('prize_start_reg'); $i++ )
						{
							$card = 'random'.$i;
							$message .= $tcgcards.''.$_POST[$card].'.'.$tcgext.'<br />';
						}

						$message .= "<br />Thanks again for joining and happy trading!<br /><br />";
						$message .= "-- $tcgowner<br />";
						$message .= "$tcgname: $tcgurl<br />";

						@include($tcgpath.'admin/mail/index.php');
					}
				}
			}

			// Throw error if both SMTP and send mail doesn't work
			else
			{
				echo '<h1>Error</h1>
				<p>It looks like there was an error in processing your join form. Send the information to '.$tcgemail.' and we will send you your starter pack ASAP. Thank you and sorry for the inconvenience.</p>';
			}
		}
	}

	else
	{
		// Change to your own registration rules
		echo '<h1>Join Us!</h1>
		<p>We are glad that you\'re finally joining us here at '.$tcgname.'! Before filling up the form, kindly please have a moment to read the set of rules below, many thanks~!</p>

		<table width="100%">
		<tr><td width="55%" valign="top"><h3>Members must...</h3>
		<ol>
			<li>have a working website (trade post) and email address must be valid.</li>
			<li>use a realistic name or nickname. If your name is already taken on the members list, please change your name or add a number instead (alphanumeric only).</li>
			<li>upload your starter pack within two weeks. If you need more time, just please let me know.</li>
			<li>update your trade posts at least <i>every two months</i>. If you do not, your status will be changed to <b>inactive</b> and you must reactivate your membership to continue trading.</li>
			<li>keep a detailed log on your trade post so we know where you got your cards and other '.$tcgname.' stuff from.</li>
			<li>send a hiatus notice if you need to, because if your trade post is left un-updated, I\'ll assume that you stopped playing or no longer interested.</li>
			<li><u>NOT DIRECT-LINK</u> any graphics from '.$tcgname.'. Please upload them to your own server or a free image site, such as <a href="http://www.photobucket.com/" target="_blank">Photobucket</a> or <a href="http://www.imgur.com/" target="_blank">Imgur</a>.</li>
			<li><u>NOT CHEAT</u> anywhere and in anyway possible. Which means...
				<ul>
					<li>..you are <i>not allowed</i> to refresh any prize page or randomizer unless you are told to do so.</li>
					<li>..you are not allowed to give out answers to fellow players as well.</li>
					<li>..you will play the games only <u>ONCE</u> per round unless told otherwise.</li>
					<li>..you have to wait for the next game update in order to play again.</li>
				</ul>
			</li>
			<li>provide a password to be able to access forms and the interactive section. This password is encoded in the database and cannot be retrieved or viewed by anyone.</li>
			<li>be nice and polite to other members as much as possible. If members don\'t want to trade, respect their decision. Let\'s make this place peaceful and enjoyable.</li>
		</ol>

		<h3>Freebies can/must...</h3>
		<ol>
			<li>be taken from the latest update regardless of joining after it was posted.</li>
			<li>be taken anytime as they are not restricted to any deadlines.</li>
			<li>be all claimed at the same time as we do not allow claiming them in parts.</li>
		</ol>

		<p>Lastly, pulls are automatically added as a comment on the update in which where it was taken. In case that there was a pull that weren\'t added, it is highly required that you add them by simply editing your existing comment. Otherwise, you will be asked to remove any of the cards you took without commenting.</p></td>
		<td width="2%"></td>
		<td width="43%" valign="top">
		<h3>Join Form</h3>
		<form method="post" action="'.$tcgurl.'members.php?page='.$page.'&stat=sent">
		<input type="hidden" name="about" value="Coming Soon" />';

		for( $i=1; $i<=$settings->getValue( 'prize_start_choice' ); $i++ )
		{
			$sql = $database->get_assoc("SELECT * FROM `tcg_cards`");
			$digit = rand(01,$sql['card_count']);
			if( $digit < 10 )
			{
				$digit = "0$digit";
			}

			else
			{
				$digit = $digit;
			}
			echo "<input type=\"hidden\" name=\"choice$i\" value=\"$digit\" />\n";
		}

		for( $i=1; $i<=$settings->getValue( 'prize_start_reg' ); $i++ )
		{
			echo '<input type="hidden" name="random'.$i.'" value="'; $general->randtype('Active','1'); echo "\" />\n";
		}

		$field->Name('');
		$field->Email('');
		$field->Website('');
		$field->Birthday('');
		$field->Collecting('');
		$field->Referral('');
		echo '<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="bi-lock" role="image" title="Password" data-toggle="tooltip" data-placement="bottom"></i></span>
			</div>
			<input type="password" name="password" class="form-control" placeholder="********">
			<input type="password" name="password2" class="form-control" placeholder="Retype your password">
		</div>
		<input type="submit" name="submit" class="btn btn-success" value="Join '.$tcgname.'" /> 
		<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
		</form>
		</td></tr></table>';
	}
}
?>