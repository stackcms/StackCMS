<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('higher-lower')."'");
$counts = $database->num_rows("SELECT * FROM `game_hol_logs` WHERE `hol_name`='".$row['usr_name']."' AND `hol_date` >= '".$range['gup_date']."'");
$week = date("Y-m-d", strtotime("+6 days", strtotime($range['gup_date'])));
$now = isset($range['gup_date']) ? $range['gup_date'] : null;

if( empty( $go ) )
{
?>

<h1><?php echo $games->gameSet('higher-lower'); ?> - <?php echo $games->gameTitle('higher-lower'); ?></h1>
<?php echo $games->gameBlurb('higher-lower'); ?>
<center><h2>This week's card is...</h2>

<?php
$get = $database->get_assoc("SELECT * FROM `game_hol_cards` WHERE `hol_date`='$now'");
echo '<img src="'.$tcgcards.''.$get['hol_filename'].''.$get['hol_number'].'.'.$tcgext.'" /><br />'.$get['hol_filename'].'<b>'.$get['hol_number'].'</b><br /><br />
<b><u>This week ends on <i>'.date("F d, Y", strtotime("+6 days", strtotime($get['hol_date']))).'</i> at <i>11:59PM PHT</i>!</u></b>';
if( $counts == 1 )
{
	echo '<p>You have already played this game! Please wait until next week to receive your rewards.</p>';
}

else
{
?>

<p><form method="post" action="<?php echo $tcgurl; ?>games.php?play=higher-lower&go=guess">
<input type="hidden" name="name" value="<?php echo $player; ?>" />
<input type="text" name="guess" placeholder="higher / lower" size="25" /><input type="submit" name="submit" class="btn-success" value="Guess!" />
</form></p>

<?php
}
?>

<h2>Logs</h2>
<div style="border:1px solid #cccccc;border-radius:8px;text-align:left;overflow:auto;padding:10px;width:50%;height:100px;">
<?php
$sql = $database->query("SELECT * FROM `game_hol_logs` WHERE `hol_date` BETWEEN '$now' AND '$week' ORDER BY `hol_date` DESC");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<b>'.$row['hol_date'].':</b> '.$row['hol_name'].' guessed it to be <u>'.$row['hol_guess'].'</u>.<br />';
}
?>
</div>
</center>

<?php
}

else
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
	}

	else
	{
		$name = $sanitize->for_db($_POST['name']);
		$guess = $sanitize->for_db($_POST['guess']);
		$date = date("Y-m-d", strtotime("now"));

		$insert = $database->query("INSERT INTO `game_hol_logs` (`hol_name`,`hol_guess`,`hol_date`) VALUES ('$name','$guess','$date')");

		if( $insert == TRUE )
		{
			echo '<h1>'.$games->gameTitle('higher-lower').'</h1>
			<p>Your guess has been saved into the '.$games->gameTitle('higher-lower').'\'s logs. Kindly please wait until next week for your rewards as you will automatically receive it once a new round starts.</p>';
		}

		else
		{
			echo '<h1>'.$games->gameTitle('higher-lower').' : Error!</h1>
			<p>It seems like there was an error while processing your form, kindly please contact '.$tcgowner.' about this as soon as possible.</p>';
		}
	}
}
?>