<?php
$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('melting-pot')."'");
$logChk = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('melting-pot')."' AND `log_date` >= '".$range['gup_date']."'");
$counts = $database->num_rows("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_title`='".$games->gameTitle('melting-pot')."' AND `log_date` >= '".$range['gup_date']."'");

if( empty( $go ) )
{
	if( $counts == 2 )
	{
		echo '<h1>'.$games->gameTitle('melting-pot').' : Halt!</h1>
		<p>You have already played this game! If you missed your trades, here they are:</p>';
		while( $row = mysqli_fetch_assoc( $logChk ) )
		{
			echo '<center><b>'.$row['log_title'].':</b> '.$row['log_rewards'].'<br /></center>';
		}
	}

	else
	{
?>

<h1><?php echo $games->gameSet('melting-pot'); ?> - <?php echo $games->gameTitle('melting-pot'); ?></h1>
<?php echo $games->gameBlurb('melting-pot'); ?>
<center>
	<div style="width: 650px;">
	<?php
		$query = $database->query("SELECT * FROM `game_mpot_cards` ORDER BY `mpot_cards` ASC"); 
		$counts = $database->num_rows("SELECT * FROM `game_mpot_cards` ORDER BY `mpot_cards` ASC");
		if( $counts == 0 )
		{
			echo "There are no more cards. Come back next week.";
		}

		else
		{
			while( $row = mysqli_fetch_array( $query ) )
			{
				echo '<img src="'.$tcgcards.''.$row['mpot_cards'].'.'.$tcgext.'" alt="'.$row['mpot_cards'].'" title="'.$row['mpot_cards'].'" style="padding: 2px 0" />';
			}
		}
	?>
	</div>
</center><br />

<center>
<form method="post" action="/games.php?play=melting-pot&go=traded">
	<input type="hidden" name="name" value="<?php echo $player; ?>" />
	<table border="0" width="80%" class="table table-sliced table-striped">
	</tbody>
		<tr>
			<td width="20%" align="right"><b>Taking:</b></td>
			<td width="60%">
				<select name="card1" style="width:88%;">
					<option value="">-----</option>
					<?php
					$query = $database->query("SELECT * FROM `game_mpot_cards` ORDER BY `mpot_cards` ASC");
					while( $row = mysqli_fetch_assoc( $query ) )
					{
						$card = stripslashes($row['mpot_cards']);
						echo "<option value=\"$card\">$card</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><b>Giving:</b></td>
			<td>
				<select name="give1" style="width:80%;">
					<option value="">-----</option>
					<?php
					$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' ORDER BY `card_filename`");
					while( $row = mysqli_fetch_assoc( $query ) )
					{
						$card = stripslashes($row['card_filename']);
						echo "<option value=\"$card\"\>$card</option>\n";
					}
					?>
				</select>
				<input type="text" name="give2" size="1" placeholder="00" />
			</td>
		</tr>
	</tbody>
	</table>
	<input type="submit" name="submit" value="Trade Cards" class="btn-success" />
</form>
</center>

<h2>Trade Logs</h2>
<center>
	<div style="border:1px solid #cccccc;border-radius:8px;text-align:left;overflow:auto;padding:10px;width:90%;height:100px;">
	<?php
	$range = $database->get_assoc("SELECT * FROM `tcg_games_updater` WHERE `gup_set`='".$games->gameSet('melting-pot')."' ORDER BY `gup_id` DESC");
	$dateToday = date("Y-m-d", strtotime($range['gup_date']));
	$weekAgo = date("Y-m-d", strtotime("+1 week"));
	$logs = $database->query("SELECT * FROM `game_mpot_logs` WHERE `mpot_date` BETWEEN '$dateToday' AND '$weekAgo' ORDER BY `mpot_date` DESC");
	$counts = $database->num_rows("SELECT * FROM `game_mpot_logs` WHERE `mpot_date` BETWEEN '$dateToday' AND '$weekAgo' ORDER BY `mpot_date` DESC");

	if( $counts == 0 )
	{
		echo 'There are no new logs for this week.';
	}

	else
	{
		while( $row = mysqli_fetch_assoc( $logs ) )
		{
			$name = stripslashes($row['mpot_name']);
			$take = stripslashes($row['mpot_take']);
			$give = stripslashes($row['mpot_give']);
			$date = date("Y/m/d", strtotime($row['mpot_date']));
			echo "<b>$date</b> - $name exchanged <i>$give</i> for <i>$take</i><br />";
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
		$name = $sanitize->for_db($_POST['name']);
		$card1 = $sanitize->for_db($_POST['card1']);
		$give1 = $sanitize->for_db($_POST['give1']);
		$give2 = $sanitize->for_db($_POST['give2']);
		$date = date("Y-m-d", strtotime("now"));

		$card2 = "$give1$give2";

		$num = $database->num_rows("SELECT * FROM `game_mpot_cards` WHERE `mpot_cards` = '".$card2."'");
		$num2 = $database->num_rows("SELECT * FROM `game_mpot_cards` WHERE `mpot_cards` LIKE '".$give1."%'");

		if( $num > 0 )
		{
			echo "<h1>Error</h1>";
			echo "<center>That card is already in the pile.</center>";
		}

		else if( $num2 > 0 )
		{
			echo "<h1>Error</h1>";
			echo "<center>That deck is already in the pile.</center>";
		}

		else
		{
			$delete = $database->query("DELETE FROM `game_mpot_cards` WHERE `mpot_cards`='$card1' LIMIT 1");
			$insert = $database->query("INSERT INTO `game_mpot_logs` (`mpot_name`, `mpot_take`, `mpot_give`, `mpot_date`) VALUE ('$name', '$card1', '$card2', '$date')");
			$insert1 = $database->query("INSERT INTO `game_mpot_cards` (`mpot_cards`) VALUE ('$card2')");
			if( $insert1 == TRUE )
			{
				echo '<h1>'.$games->gameTitle('melting-pot').'</h1>
				<center><img src="/images/cards/'.$card1.'.png"><br>
				<b>'.$games->gameTitle('melting-pot').':</b> Traded '.$card2.' for '.$card1.'</center>';
				$newSet = 'Traded '.$card2.' for '.$card1;
				$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_rewards`,`log_date`) VALUES ('$player','".$games->gameSet('melting-pot')."','".$games->gameTitle('melting-pot')."','$newSet','$date')");
			}

			else
			{
				echo '<h1>'.$games->gameTitle('melting-pot').' : Error!</h1>
				<p>It seems like there was an error while processing your trade, kindly please contact '.$tcgowner.' about this as soon as possible.</p>';
			}
		}
	}
}
?>