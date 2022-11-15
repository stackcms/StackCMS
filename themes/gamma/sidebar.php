<?php
if( !empty( $login ) )
{
	echo '<h2>Member Panel</h2>
	<div class="gameUpdate">
		<a href="'.$tcgurl.'account.php">My Account</a>
		<a href="'.$tcgurl.'shoppe.php">Shoppe</a>
		<a href="'.$tcgurl.'rewards.php?name='.$player.'">Rewards ('; echo $count->numRewards(); echo ')</a>
		<a href="'.$tcgurl.'messages.php?id='.$player.'">Messages ('; echo $count->numMail(); echo ')</a>
		<a href="'.$tcgurl.'account.php?do=logout">Logout</a>
	</div>';
}
else
{
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	echo '<h2>Member Login</h2>
	<p align="center">Kindly login your account in order to access the entire TCG.</p>
	<form method="post" action="'.$tcgurl.'account.php?do=login&action=loggedin" style="padding-bottom:1px;">
	<input type="hidden" name="redirect" value="'.$actual_link.'">
	<input type="text" name="email" placeholder="username@domain.tld" style="width:93%" /><br />
	<input type="password" name="password" placeholder="********" style="width:93%" /><br />
	<input type="submit" name="submit" value="Login" class="btn-success" />';
	if( $settings->getValue( 'tcg_registration' ) == "0" ) {}
	else
	{
		echo '<input type="button" onClick="window.location.href=\''.$tcgurl.'members.php?page=join\';" value="Register" class="btn-info" />';
	}
	echo '</form>';
}
echo '<center>If you just joined this month and don\'t have it yet, you can grab the monthly event card! Just comment on the latest update with it.<br /><br />
<img src="'.$tcgcards.'ec-'.date("m").''.date("Y").'.png" /><br />
<b>Event Achievement:</b> ec-'.date("m").''.date("Y").'</center>

<hr>';

if( $settings->getValue( 'xtra_motm' ) == "0" ) {}
else
{
	$week = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Weekly'");
	$month = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='Monthly'");
	if( $settings->getValue( 'xtra_motm_scope' ) == "Week" )
	{
		echo '<h2>Member of the '.$settings->getValue( 'xtra_motm_scope' ).'</h2>
		<p align="center">';
		$row = $database->get_assoc("SELECT * FROM `user_list_motm` WHERE `motm_date`='".$week['gup_date']."'");
		echo 'Congratulations, <em>'.$row['motm_name'].'</em>!<br /><br />';
		if( $row['motm_date'] == $week['gup_date'] )
		{
			echo '<img src="'.$tcgcards.'mc-'.$row['motm_name'].'.png" />';
		}
		else
		{
			echo '<img src="'.$tcgcards.'mc-filler.png" />';
		}
		echo '<br /><br />
		You are the member for this week!<br />';
	}

	else
	{
		echo '<h2>Member of the '.$settings->getValue( 'xtra_motm_scope' ).'</h2>
		<p align="center">';
		$row = $database->get_assoc("SELECT * FROM `user_list_motm` WHERE `motm_date`='".$month['gup_date']."'");
		echo 'Congratulations, <em>'.$row['motm_name'].'</em>!<br /><br />';
		if( $row['motm_date'] == $month['gup_date'] )
		{
			echo '<img src="'.$tcgcards.'mc-'.$row['motm_name'].'.png" />';
		}
		else
		{
			echo '<img src="'.$tcgcards.'mc-filler.png" />';
		}
		echo '<br /><br />
		You are the member for the month of <u>'.date("F").'</u>!<br />';
	}
	echo 'Do not forget to <a href="'.$tcgurl.'games.php?play=motm">claim your rewards</a>!</p>
	<hr>';
}

echo '<h2>Calendar</h2>';
@include($tcgpath.'themes/calendar.php');
// MAKE SURE TO CHANGE YOUR WEEKLY SCHEDULE
echo '<p align="center">All deadlines are '.$tcgname.'\'s local time:
<iframe src="https://freesecure.timeanddate.com/clock/i7873tak/n145/fn16/fs11/fc58687d/tct/pct/ftb/tt0/tw1/tm3/td2/tb2" frameborder="0" width="100%" height="14" allowTransparency="true"></iframe>
</p>
<p align="center">Weekly updates every <i>'.$settings->getValue('update_scope').'</i> '.date("T", strtotime("now")).'!</p>

<hr>';

if( $settings->getValue( 'xtra_chatbox' ) == "0" ) {}
else
{
	echo '<h2>Chat Box</h2>
	<center>Feel free to use the chatbox for quick inquiries ONLY if you don\'t have a Discord, otherwise use them for random discussions if you like.<br />
	<iframe title="dwi-chat" src="'.$tcgurl.'admin/chat.msg.php" width="100%" height="120" frameborder="0" scrolling="auto"></iframe>
	<iframe title="dwi-form" src="'.$tcgurl.'admin/chat.form.php" width="100%" height="80" frameborder="0" scrolling="no"></iframe>
	</center>';
}
?>