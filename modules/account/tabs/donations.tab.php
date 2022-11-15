<?php
/********************************************************
 * Tab:				User Donations
 * Description:		Lists all user's claims and donations
 */


if( $act == "edit" )
{
	if( isset( $_POST['edit'] ) )
	{
		$id = $_POST['id'];
		$file = $sanitize->for_db($_POST['filename']);
		$feat = $sanitize->for_db($_POST['feature']);
		$link = $sanitize->for_db($_POST['link']);
		$cat = $sanitize->for_db($_POST['category']);
		$set = $sanitize->for_db($_POST['set']);
		$update = $database->query("UPDATE `tcg_donations` SET `deck_filename`='$file', `deck_feature`='$feat', `deck_cat`='$cat', `deck_set`='$set', `deck_url`='$link' WHERE `deck_id`='$id'");

		if( !$update )
		{
			$error[] = "There was an error while editing the deck you claimed. ".mysqli_error($update)."";
		}
		else
		{
			$success[] = "The deck you have claimed has been successfully updated!";
		}
	}

	$id = $_GET['id'];
	$sql = $database->get_assoc("SELECT * FROM `tcg_donations` WHERE `deck_id`='$id'");
	echo '<h2>Edit Claims</h2>
	<p>Use the form below if you need to edit your current deck claims such as correcting misspelled filename and such.</p>
	<center>';
	if( isset( $error ) )
	{
		foreach ( $error as $msg )
		{
			echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
		}
	}
	if( isset( $success ) )
	{
		foreach ( $success as $msg )
		{
			echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'account.php?action=edit&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<table width="100%" cellspacing="3" class="table table-sliced table-striped">
	<tbody>
	<tr>
		<td width="15%"><b>Category:</b></td>
		<td width="35%">
			<select name="category" style="width:97%;">';
			$dc = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$sql['deck_cat']."'");
			echo '<option value="'.$sql['deck_cat'].'">Current: '.$dc['cat_name'].'</option>';
			$c = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_name` ASC");
			while( $cat = mysqli_fetch_assoc( $c ) )
			{
				echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_name'].'</option>';
			}
			echo '</select>
		</td>
		<td width="15%"><b>File Name:</b></td>
		<td width="35%"><input type="text" name="filename" style="width:90%;" value="'.$sql['deck_filename'].'"></td>
	</tr>
	<tr>
		<td><b>Feature:</b></td>
		<td><input type="text" name="feature" value="'.$sql['deck_feature'].'" style="width:90%;"></td>
		<td><b>Set/Series:</b></td>
		<td>
			<select name="set" style="width:97%;">';
			$ds = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$sql['deck_set']."'");
			echo '<option value="'.$sql['deck_set'].'">Current: '.$ds['set_name'].'</option>';
			$s = $database->query("SELECT * FROM `tcg_cards_set` ORDER BY `set_name` ASC");
			while( $set = mysqli_fetch_assoc( $s ) )
			{
				echo '<option value="'.$set['set_id'].'">'.$set['set_name'].'</option>';
			}
			echo '</select>
		</td>
	</tr>';
	if( $sql['deck_type'] == "Donations" )
	{
		echo '<tr><td><b>Download link:</b></td><td colspan="3"><input type="text" name="link" value="'.$sql['deck_url'].'" style="width:96%;"></td></tr>';
	}
	
	else
	{
		echo '<input type="hidden" name="link" value="">';
	}
	echo '</tbody>
	</table>
	<input type="submit" name="edit" class="btn-success" value="Edit Deck Claim" /> 
	<input type="reset" name="reset" class="btn-danger" value="Reset" />
	</form>';
}


else if( $act == "donate" )
{
	if( isset( $_POST['donate'] ) )
	{
		$id = $_POST['id'];
		$link = $sanitize->for_db($_POST['link']);
		$deck = $sanitize->for_db($_POST['deck']);
		$date = date("Y-m-d H:i:s", strtotime("now"));
		$update = $database->query("UPDATE `tcg_donations` SET `deck_url`='$link', `deck_type`='Donations' WHERE `deck_id`='$id'");

		if( !$update )
		{
			$error[] = "There was an error while submitting your donation. ".mysqli_error($update)."";
		}

		else
		{
			$database->query("INSERT INTO `user_rewards` (`rwd_name`,`rwd_type`,`rwd_subtitle`,`rwd_cards`,`rwd_currency`,`rwd_date`) VALUES ('$player','Donations','($deck)','".$settings->getValue('prize_deck_reg')."','".$settings->getValue('prize_deck_cur')."','$date')");
			$success[] = "The donation link for the deck you have claimed has been successfully added!";
		}
	}

	$id = $_GET['id'];
	$sql = $database->get_assoc("SELECT * FROM `tcg_donations` WHERE `deck_id`='$id'");
	$set = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$sql['deck_set']."'");

	// Explode bombs
	$curValue = explode(' | ', $settings->getValue( 'prize_deck_cur' ));
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
			$arrayCur[] = '<li class="spacer">- <b>'.$curValue[$key].'</b> '.$tn.'</li>';
		}
	}
	// Fix all bombs after explosions
	$arrayCur = implode(" ", $arrayCur);

	echo '<h2>Add Donation Link</h2>
	<p>Use the form below to submit your image donations for the <b>'.$sql['deck_filename'].'</b> deck. Please keep in mind the exclusive guidelines before donating any deck.</p>
	<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>
	<ul>
		<li>Donated images must be in high quality and unedited, preferrably 600x600 pixels up to 1600x1600 pixels.</li>
		<li>Horizontal images are much preferred than vertical ones to avoid the subjects getting cropped just to fit the card template.</li>
		<li>Only images that is related to nature that will fit to our sets are allowed.</li>
		<li>Donations need at least 25 images, but more is encouraged.</li>
		<li>You can donate up to <b>'.$settings->getValue('xtra_deck_cards').' decks</b> per month.</li>
	</ul>
	<p><b>You will receive the following rewards:</b></p>
	<li class="spacer">- <b>'.$settings->getValue('prize_deck_reg').'</b> random cards</li>';
	echo $arrayCur;
	echo '<br /><form method="post" action="'.$tcgurl.'account.php?action=donate&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<input type="hidden" name="deck" value="'.$sql['deck_filename'].'" />
	<table width="100%" cellspacing="3" class="table table-sliced table-striped">
	<tbody>
	<tr>
		<td width="20%"><b>Donating For:</b></td>
		<td width="80%">'.$set['set_name'].' - '.$sql['deck_feature'].'</td>
	</tr>
	<tr>
		<td><b>Donation Link:</b></td>
		<td><input type="text" name="link" placeholder="e.g. https://site.com/'.$sql['deck_filename'].'.zip" style="width:90%;"></td>
	</tr>
	</tbody>
	</table>
	<input type="submit" name="donate" class="btn-success" value="Send Donations" /> 
	<input type="reset" name="reset" class="btn-danger" value="Reset" />
	</form>';
}


else {
	echo '<h2>Donations</h2>
	<p>Here is the list of your claimed decks that hasn\'t been donated yet and donated decks that hasn\'t been made yet. If you think that you are missing a deck here compared to the claims and donations list from the <a href="'.$tcgurl.'cards.php">cards</a> page, kindly please let '.$tcgowner.' know.</p>
	<table width="100%" class="table table-sliced table-striped">
	<thead>
	<tr>
		<td width="50%"><b>Deck</b></td>
		<td width="15%" align="center"><b>Type</b></td>
		<td width="15%" align="center"><b>Maker</b></td>
		<td width="20%" align="center"><b>Action</b></td>
	</tr>
	</thead>
	<tbody>';
	$decks = $database->query("SELECT * FROM `tcg_donations` WHERE `deck_donator`='$player' ORDER BY `deck_date`");
	while( $deck = mysqli_fetch_assoc( $decks ) )
	{
		echo '<tr>
		<td>'.$deck['deck_feature'].' ('.$deck['deck_filename'].')</td>
		<td align="center">'.$deck['deck_type'].'</td>
		<td align="center">'.$deck['deck_maker'].'</td>
		<td align="center">
			<button onclick="window.location.href=\''.$tcgurl.'account.php?action=edit&id='.$deck['deck_id'].'\';" class="btn-success">Edit</button> ';
			if( $deck['deck_type'] == "Claims" )
			{
				echo '<button onclick="window.location.href=\''.$tcgurl.'account.php?action=donate&id='.$deck['deck_id'].'\';" class="btn-primary">Donate</button>';
			}
			else {}
		echo '</td>
		</tr>';
	}
	echo '</tbody>
	</table>';
}
?>