<?php
/*************************************************
 * Action:			User Rewards
 * Description:		Show page for sending a reward
 */


// Process send rewards form
if( isset( $_POST['submit'] ) )
{
	$id = $sanitize->for_db($_POST['id']);
	$name = $sanitize->for_db($_POST['name']);
	$type = $sanitize->for_db($_POST['type']);
	$subt = $sanitize->for_db($_POST['subt']);
	$cards = $sanitize->for_db($_POST['cards']);
	$currency = $sanitize->for_db($_POST['currency']);
	$mcard = $sanitize->for_db($_POST['mcard']);
	$mstone = $sanitize->for_db($_POST['mstone']);
	$date = $sanitize->for_db($_POST['timestamp']);

	$insert = $database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_cards`,`rwd_mcard`,`rwd_mstone`,`rwd_currency`,`rwd_date`) VALUES ('$name','$type','$subt','$cards','$mcard','$mstone','$currency','$date')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the rewards were not sent. ".mysqli_error($insert);
	}

	else
	{
		$success[] = "The rewards were successfully sent to $name.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show send rewards form
else
{
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
	$date = date("Y-m-d", strtotime("now"));
	
	echo '<h1>Send Rewards</h1>
	<p>Use the form below to send rewards to a member.</p>

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

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" id="id" value="'.$id.'" />
	<input type="hidden" name="timestamp" id="timestamp" value="'.$date.'" />
	<div class="box" style="width: 600px;">
		<div class="row">
			<div class="col-4"><b>Player Name:</b></div>
			<div class="col">
				<input type="text" name="name" value="'.$row['usr_name'].'" readonly class="form-control" />
			</div>
		</div><br />

		<div class="row">
			<div class="col-4"><b>Rewarded for:</b></div>
			<div class="col">
				<select name="type" class="form-control">
					<option value="Daily Bonus">Daily Bonus</option>
					<option value="Donations">Donations</option>
					<option value="Games">Games</option>
					<option value="Gift">Gift</option>
					<option value="Referrals">Referrals</option>
					<option value="Starter Pack">Starter Pack</option>
				</select>
			</div>
		</div><br />

		<div class="row">
			<div class="col-4"><b>Subtitle:</b></div>
			<div class="col">
				<input type="text" name="subt" placeholder="e.g. (deckname)" class="form-control" />
				<small><i>(Leave blank if not applicable)</i></small>
			</div>
		</div><br />

		<div class="row">
			<div class="col-4"><b>Member Card:</b></div>
			<div class="col">
				<input type="radio" name="mcard" value="Yes" /> Yes &nbsp;&nbsp; 
				<input type="radio" name="mcard" value="No" /> No<br />
				<small><i>(Select No if not applicable)</i></small>
			</div>
		</div><br />

		<div class="row">
			<div class="col-4"><b>Achievements:</b></div>
			<div class="col">
				<textarea name="mstone" class="form-control" rows="2"></textarea>
				<small><i>(Leave blank if not applicable)</i></small>
			</div>
		</div><br />

		<div class="row">
			<div class="col-4"><b>Cards & Currencies:</b></div>
			<div class="col">
				<div class="input-group mb-3">
					<input type="text" name="cards" placeholder="amount of cards" class="form-control">
					<input type="text" name="currency" placeholder="amount of currencies" class="form-control">
				</div>
			</div>
		</div>

		<input type="submit" name="submit" class="btn btn-success" value="Send Rewards" /> 
		<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</div><!-- box -->
	</form>';
}
?>