<?php
/****************************************************
 * Action:			Add New Member/User
 * Description:		Show page for adding a new member
 */

if( isset( $_POST['add'] ) )
{
	$check->Member();
	if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,6})$/",strtolower($_POST['email']) ) )
	{
		exit("<h1>Error</h1>\nThat e-mail address is not valid, please use another.");
	}

	$name = $sanitize->for_db($_POST['username']);
	$email = $sanitize->for_db($_POST['email']);
	$url = $sanitize->for_db($_POST['url']);
	$refer = $sanitize->for_db($_POST['refer']);
	$stat = $sanitize->for_db($_POST['status']);
	$col = $sanitize->for_db($_POST['collecting']);
	$mc = $sanitize->for_db($_POST['memcard']);
	$birthday = $_POST['date'];
	$regdate = date("Y-m-d H:i:s", strtotime("now"));
	$date = date("Y-m-d", strtotime("now"));
	$bio = $_POST['about'];
	$bio = nl2br($bio);
	$bio = str_replace("'","\'",$bio);

	$pass = $_POST['password'];
	$pass2 = $sanitize->for_db($_POST['password2']);
	$hashed = password_hash($pass, PASSWORD_DEFAULT);
	$hash = md5( rand(0,1000) );

	$choice = null; $rand = null; $cW = null; $rW = null;
	for( $i = 1; $i <= $settings->getValue('prize_start_choice'); $i++ )
	{
		$card = "choice$i";
		echo '<img src="'.$tcgcards.''.$col;
		echo $_POST[$card];
		echo '.'.$tcgext.'" />';
		$choice .= $col.$_POST[$card].", ";
	}

	for( $i = 1; $i <= $settings->getValue('prize_start_reg'); $i++ )
	{
		$card = "random$i";
		echo '<img src="'.$tcgcards;
		echo $_POST[$card];
		echo '.'.$tcgext.'" />';
		$rand .= $_POST[$card].", ";
	}

	$choice = substr_replace($choice,"",-2);
	$rand = substr_replace($rand,"",-2);
	$total = $settings->getValue('prize_start_choice') + $settings->getValue('prize_start_reg');

	$insert = $database->query("INSERT INTO `user_list` (`usr_name`,`usr_email`,`usr_url`,`usr_refer`,`usr_bday`,`usr_pass`,`usr_hash`,`usr_status`,`usr_deck`,`usr_mcard`,`usr_bio`,`usr_level`,`usr_reg`) VALUES ('$name','$email','$url','$refer','$birthday','$hashed_pass','$hash','Pending','$col','$mc','$bio','1','$regdate')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the member was not added. ".mysqli_error($insert)."";
	}

	else
	{
		$currSP = explode(", ", $settings->getValue('tcg_currency'));
		$money = '';
		for( $j = 0; $j < count($currSP); $j++ )
		{
			$money .= '0 | ';
		}
		$money = substr_replace($money,"",-2);

		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$name','Service','Starter Pack','$choice, $rand','$date')");
		$database->query("INSERT INTO `user_items` (`itm_name`,`itm_masteries`,`itm_mcard`,`itm_ecard`,`itm_milestone`,`itm_cards`,`itm_currency`) VALUES ('$name','None','None','None','None','$total','$money')");
		$database->query("INSERT INTO `user_trades_rec` (`trd_name`,`trd_date`) VALUES ('$name','$date')");

		// Referral rewards
		if( $refer == "None" ) {}
		else
		{
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_mcard`,`rwd_cards`,`rwd_date`) VALUES ('$refer','Services','(Referral)','No','1','$date')");
		}

		// Email message
		if( function_exists( 'mail' ) )
		{
			$recipient = "$email";
			$subject = $tcgname.": Starter Pack";
			$message = "Thanks for joining $tcgname, $name! We are very excited that you are going to be joining us. Your account is currently pending approval, but you can begin playing games regardless. Below is a copy of your starter pack, in case you did not pick it up from the site.\n\n";

			for( $i = 1; $i <= $settings->getValue('prize_start_choice'); $i++ )
			{
				$card = 'choice'.$i;
				$message .= $tcgcards.''.$col.''.$_POST[$card].'.'.$tcgext."\n";
			}

			for( $i = 1; $i <= $settings->getValue('prize_start_reg'); $i++ )
			{
				$card = 'random'.$i;
				$message .= $tcgcards.''.$_POST[$card].'.'.$tcgext."\n";
			}

			$message .= "\nThanks again for joining and happy trading!\n\n";
			$message .= "-- $tcgowner\n";
			$message .= "$tcgname: $tcgurl\n";
			$headers = "From: $tcgname <$tcgemail> \n";
			$headers .= "Reply-To: $tcgname <$tcgemail>";
			mail($recipient,$subject,$message,$headers);
		}

		else
		{
			$subject = $tcgname.": Starter Pack";
			$message = "Thanks for joining $tcgname, $name! We are very excited that you are going to be joining us. Your account is currently pending approval, but you can begin playing games regardless. Below is a copy of your starter pack, in case you did not pick it up from the site.<br /><br />";
			for( $i = 1; $i <= $settings->getValue('prize_start_choice'); $i++ )
			{
				$card = 'choice'.$i;
				$message .= $tcgcards.''.$col.''.$_POST[$card].'.'.$tcgext.'<br />';
			}

			for( $i = 1; $i <= $settings->getValue('prize_start_reg'); $i++ )
			{
				$card = 'random'.$i;
				$message .= $tcgcards.''.$_POST[$card].'.'.$tcgext.'<br />';
			}
			$message .= "<br />Thanks again for joining and happy trading!<br /><br />
			-- $tcgowner<br />
			$tcgname: $tcgurl";
			@include($tcgpath.'admin/mail/index.php');
		}

		// Send notification to user that their starter pack has been logged
		$date2 = date("Y-m", strtotime("now"));
		$text = '<a href="'.$tcgurl.'account.php?ld='.$date2.'">Your starter pack has been logged on your permanent logs.</a>';
		$database->query("INSERT INTO `user_notices` (`notif_name`,`notif_comm`,`notif_message`,`notif_read`,`notif_date`) VALUES ('$name','0','$text','0','$date')");

		$success[] = "The member was successfully added to the database and their starter pack has been emailed to them.";
	}
} // end form processing





echo '<h1>Add a Member</h1>
<p>Use this form to add a member to the database. <b>If the member has submitted a join form, they are already in the database!</b><br />
Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'">edit</a> form to update information for existing members.</p>

<center>';
if( isset( $error ) )
{
	foreach( $error as $msg )
	{
		echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
	}
}
if( isset( $success ) )
{
	foreach( $success as $msg )
	{
		echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'">
<input type="hidden" name="memcard" value="No" />
<input type="hidden" name="about" value="Coming Soon" />';
for( $i = 1; $i <= $settings->getValue( 'prize_start_choice' ); $i++ )
{
	$sql = $database->get_assoc("SELECT * FROM `tcg_cards`");
	$digit = rand(01,$sql['card_count']);
	if($digit < 10)
	{
		$digit = "0$digit";
	}
	else
	{
		$digit = $digit;
	}
	echo "<input type=\"hidden\" name=\"choice$i\" value=\"$_digits\" />\n";
}

for( $i = 1; $i <= $settings->getValue( 'prize_start_reg' ); $i++ )
{
	echo '<input type="hidden" name="random'.$i.'" value="'; $general->randtype('Active','1'); echo "\" />\n";
}

echo '<div class="box">
	<div class="row">
		<div class="col">';
			$field->Name('');
		echo '</div><!-- col -->

		<div class="col">';
			$field->Email('');
		echo '</div><!-- col -->

		<div class="col">';
			$field->Website('');
		echo '</div>
	</div><!-- row -->
	
	<div class="row">
		<div class="col">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-lock" role="image" title="Password" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="password" name="password" placeholder="********" class="form-control">
				<input type="password" name="password2" placeholder="********" class="form-control">
				<div class="input-group-append">
					<span class="input-group-text"><i>Type twice to verify</i></span>
				</div>
			</div>
		</div><!-- col -->
	</div><!-- row -->
	
	<div class="row">
		<div class="col">';
			$field->Collecting('');
		echo '</div><!-- col -->

		<div class="col">';
			$field->Birthday('');
		echo '</div><!-- col -->

		<div class="col">';
			$field->Referral('');
		echo '</div><!-- col -->
	</div><!-- row -->

	<input type="submit" name="add" class="btn btn-success" value="Add Member" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</div><!-- box -->
</form>';
?>