<?php
/******************************************************************
 * Action:			User Prejoin Donation Rewards
 * Description:		Show page for sending a prejoin donation reward
 */


// Process send rewards form
if( isset( $_POST['submit'] ) )
{
    $id = $_POST['id'];
	$name = $sanitize->for_db($_POST['name']);
	$type = $sanitize->for_db($_POST['type']);
	$subt = $sanitize->for_db($_POST['subt']);
	$cards = $sanitize->for_db($_POST['cards']);
	$money = $sanitize->for_db($_POST['money']);
	$date = $sanitize->for_db($_POST['timestamp']);

	$insert = $database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('$name','$type','$subt','$cards','$money','$date')");

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
	$row = $database->get_assoc("SELECT * FROM `prejoin_record` WHERE `usr_name`='$id'");
	$date = date("Y-m-d H:i:s", strtotime("now"));

	// Explode bombs
	$curValue = explode(' | ', $row['usr_currency']);
	$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
	foreach( $curValue as $key => $value )
	{
		$tn = substr_replace($curName[$key],"",-4);
		if( $curValue[$key] > 1 )
		{
			$var = substr($tn, -1);
			if( $var == "y" )
			{
				$tn = substr_replace($tn,"ies",-1);
			}
			else if( $var == "o" )
			{
				$tn = substr_replace($tn,"oes",-1);
			}
			else
			{
				$tn = $tn.'s';
			}
		}

		else
		{
			$tn = $tn;
		}

		if( $curValue[$key] == 0 ) {}
		else
		{
			$arrayCur[] = '<b>'.$curValue[$key].'</b> '.$tn.', ';
		}
	}
	// Fix all bombs after explosions
	$arrayCur = implode(" ", $arrayCur);
	$arrayCur = substr_replace($arrayCur,"",-2);

	echo '<h1>Send Prejoin Rewards</h1>
	<p>Use the form below to send prejoin rewards to <b>'.$id.'</b>:<br />
	A total of <b>'.$row['usr_cardworth'].'</b> card worth and '.$arrayCur.' will be rewarded to '.$id.'.</p>

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
	<input type="hidden" name="name" id="name" value="'.$id.'" />
	<input type="hidden" name="type" id="type" value="Prejoin Donations" />
	<input type="hidden" name="subtitle" id="subtitle" value="Pre-prejoin" />
	<input type="hidden" name="cards" id="cards" value="'.$row['usr_cardworth'].'" />
	<input type="hidden" name="money" id="money" value="'.$row['usr_currency'].'" />
	<input type="hidden" name="timestamp" id="timestamp" value="'.$date.'" />
	<input type="submit" name="submit" class="btn btn-success" value="Send Prejoin Rewards" />
	</form>';
}
?>