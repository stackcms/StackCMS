<?php
// Define game variables
$gset = $games->gameSet('card-claim');
$gsub = $games->gameSub('card-claim');

$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('card-claim')."'");
$logChk = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('card-claim')."' AND `log_date` >= '".$range['gup_date']."'");
$counts = $database->num_rows("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('card-claim')."' AND `log_date` >= '".$range['gup_date']."'");

if( empty( $go ) )
{
	if( $counts != 0 )
	{
		echo '<h1>'.$games->gameTitle('card-claim').' : Halt!</h1>
		<p>You have already played this game! If you missed the cards you\'ve taken, here they are:</p>';
		while( $row = mysqli_fetch_assoc( $logChk ) )
		{
			echo '<center><b>'.$row['log_title'].':</b> '.$row['log_rewards'].'<br /></center>';
		}
	}

	else
	{
?>

<h1><?php echo $games->gameSet('card-claim'); ?> - <?php echo $games->gameTitle('card-claim'); ?></h1>
<?php echo $games->gameBlurb('card-claim'); ?>
<center><h2>Inventory Pile</h2>
<div style="width: 650px;">
	<?php
	$card = $database->num_rows("SELECT * FROM `game_cclaim_cards`");
	if( $card == 0 )
	{
		echo '<p>There are no more cards in the pile!</p>';
	}

	else if( $card != 0 )
	{
		echo '<p>There are currently <b>'.$card.' cards</b> available!</p>';
    }

	$cards = $database->query("SELECT * FROM `game_cclaim_cards` ORDER BY `cclaim_cards` ASC");
	while( $row = mysqli_fetch_assoc( $cards ) )
	{
		$name = stripslashes($row['cclaim_cards']);
		echo '<img src="'.$tcgcards.''.$name.'.'.$tcgext.'" />';
	}
	?>
</div>

<form method="post" action="<?php echo $tcgurl; ?>games.php?play=card-claim&go=claimed">
	<input type="hidden" name="name" value="<?php echo $player; ?>" />
	<table border="0" cellspacing="3" width="70%" class="border">
	<tr>
		<td width="30%" class="headLine">Claiming #1:</td>
		<td width="70%" class="tableBody">
			<select name="card1" style="width: 98%;">
				<option value="">-----</option>
				<?php
				$query = $database->query("SELECT * FROM `game_cclaim_cards` ORDER BY `cclaim_cards` ASC");
				while( $row = mysqli_fetch_assoc( $query ) )
				{
					$name = stripslashes($row['cclaim_cards']);
					echo '<option value="'.$name.'">'.$name."</option>\n";
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="headLine">Claiming #2:</td>
		<td class="tableBody">
			<select name="card2" style="width: 98%;">
				<option value="">-----</option>
				<?php
				$query = $database->query("SELECT * FROM `game_cclaim_cards` ORDER BY `cclaim_cards` ASC");
				while( $row1 = mysqli_fetch_assoc( $query ) )
				{
					$name = stripslashes($row1['cclaim_cards']);
					echo '<option value="'.$name.'">'.$name."</option>\n";
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tableBody" colspan="2" align="center">
			<input type="submit" name="submit" class="btn-success" value="Claim!" />
		</td>
	</tr>
	</table>
</form>
</center>

<h2>Claim Logs</h2>
<center>
	<div style="border:1px solid #cccccc;border-radius:8px;text-align:left;overflow:auto;padding:10px;width:90%;height:100px;">
	<?php
	$now = $range['gup_date'];
	$next = date("Y-m-d", strtotime("+1 week"));

	$logs = $database->query("SELECT * FROM `game_cclaim_logs` WHERE `cclaim_date` BETWEEN '$now' AND '$next' ORDER BY `cclaim_date` DESC");
	$counts = $database->num_rows("SELECT * FROM `game_cclaim_logs` WHERE `cclaim_date` BETWEEN '$now' AND '$next' ORDER BY `cclaim_date` DESC");

	if( $counts == 0 )
	{
		echo 'There are no new logs for this week.';
	}

	else
	{
		while( $row = mysqli_fetch_assoc( $logs ) )
		{
			$name = stripslashes($row['cclaim_name']);
			$take = stripslashes($row['cclaim_take']);
			$date = date("Y/m/d", strtotime($row['cclaim_date']));
			echo "$date - $name claimed the $take cards.<br />";
		}
	}
	?>
	</div>
</center>

<?php
	}
}

else
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
	}

	else
	{
		$c1 = $sanitize->for_db($_POST['card1']);
		$c2 = $sanitize->for_db($_POST['card2']);
		$name = $sanitize->for_db($_POST['name']);
		$date = date("Y-m-d", strtotime("now"));

		$delete = $database->query("DELETE FROM `game_cclaim_cards` WHERE `cclaim_cards`='$c1' OR `cclaim_cards`='$c2'");
		if( empty( $c2 ) )
		{
			$database->query("INSERT INTO `game_cclaim_logs` (`cclaim_name`,`cclaim_take`,`cclaim_date`) VALUES ('$name','$c1','$date')");
		}

		else
		{
			$database->query("INSERT INTO `game_cclaim_logs` (`cclaim_name`,`cclaim_take`,`cclaim_date`) VALUES ('$name','$c1 and $c2','$date')");
		}

		if( $delete == TRUE )
		{
			if( empty( $c2 ) )
			{
				$database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'1' WHERE `itm_name`='$name'");
				$newSet = $c1;
				echo '<h1>'.$games->gameTitle('card-claim').'</h1>
				<center>Here is your card:<br /><img src="'.$tcgcards.''.$c1.'.'.$tcgext.'"><br />
				<b>'.$games->gameTitle('card-claim').':</b> Claimed '.$c1.'</center>';
				$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$name','".$games->gameSet('card-claim')."','".$games->gameTitle('card-claim')."','$newSet','$date')");
			}

			else
			{
				$database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'2' WHERE `itm_name`='$name'");
				$newSet = $c1.', '.$c2;
				echo '<h1>'.$games->gameTitle('card-claim').'</h1>
				<center>Here are your cards:<br /><img src="'.$tcgcards.''.$c1.'.'.$tcgext.'"> <img src="'.$tcgcards.''.$c2.'.'.$tcgext.'"><br />
				<b>'.$games->gameTitle('card-claim').':</b> Claimed '.$c1.' and '.$c2.'</center>';
				$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$name','".$games->gameSet('card-claim')."','".$games->gameTitle('card-claim')."','$newSet','$date')");
			}
		}

		else
		{
			echo '<h1>'.$games->gameTitle('card-claim').' : Error!</h1>
			<p>It seems like there was an error while processing your claims, kindly please contact '.$tcgowner.' about this as soon as possible.</p>';
		}
	}
}
?>