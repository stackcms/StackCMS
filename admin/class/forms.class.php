<?php
/*
 * Class library for displaying similar forms
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Forms
 * Description:		Functions to display similar form fields
 */
class Forms
{
	// Name field
	function Name( $control )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$control = $sanitize->for_db($control);

		if( $control == "edit" )
		{
			$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

			echo '<tr>
				<td width="15%" align="right"><b>Name:</b></td>
				<td width="35%" colspan="3"><input type="text" name="username" value="'.$row['usr_name'].'" style="width: 96%;" readonly /></td>
			</tr>';
		}

		else if( $control == "manage" )
		{
			$id = isset($_GET['id']) ? $_GET['id'] :  null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-person" role="image" title="Player Name" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="text" name="username" class="form-control" value="'.$row['usr_name'].'">
			</div>';
		}

		else
		{
			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-person" role="image" title="Player Name" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="text" name="username" class="form-control" placeholder="Jane Doe">
			</div>';
		}
	}

	// Email field
	function Email( $control )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$control = $sanitize->for_db($control);

		if( $control == "edit" )
		{
			$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

			echo '<tr>
				<td width="15%" align="right"><b>Email:</b></td>
				<td width="35%" colspan="3"><input type="text" name="email" value="'.$row['usr_email'].'" style="width: 96%;" /></td>
			</tr>';
		}

		else if( $control == "manage" )
		{
			$id = isset($_GET['id']) ? $_GET['id'] :  null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-envelope" role="image" title="Email Address" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="text" name="email" class="form-control" value="'.$row['usr_email'].'">
			</div>';
		}

		else
		{
			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-envelope" role="image" title="Email Address" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="text" name="email" class="form-control" placeholder="username@domain.tld">
			</div>';
		}
	}

	// URL or trade post field
	function Website( $control )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$control = $sanitize->for_db($control);

		if( $control == "edit" )
		{
			$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

			echo '<tr>
				<td width="15%" align="right"><b>Trade Post:</b></td>
				<td width="35%" colspan="3"><input type="text" name="url" value="'.$row['usr_url'].'" style="width: 96%;" /></td>
			</tr>';
		}

		else if( $control == "manage" )
		{
			$id = isset($_GET['id']) ? $_GET['id'] :  null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-house-heart" role="image" title="Trade Post" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="text" name="url" class="form-control" value="'.$row['usr_url'].'">
			</div>';
		}

		else
		{
			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-house-heart" role="image" title="Trade Post" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="text" name="url" class="form-control" placeholder="http://">
			</div>';
		}
	}

	// User birthday field
	function Birthday( $control )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$control = $sanitize->for_db($control);

		if( $control == "edit" )
		{
			$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

			echo '<tr>
				<td width="15%" align="right"><b>Birthday:</b></td>
				<td width="35%" colspan="3"><input type="date" name="date" value="'.$row['usr_bday'].'" /></td>
			</tr>';
		}

		else if( $control == "manage" )
		{
			$id = isset($_GET['id']) ? $_GET['id'] :  null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-gift" role="image" title="Birthday" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="date" name="date" class="form-control" value="'.$row['usr_bday'].'">
			</div>';
		}

		else
		{
			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-gift" role="image" title="Birthday" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<input type="date" name="date" class="form-control">
			</div>';
		}
	}

	// Collecting deck field
	function Collecting( $control )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$control = $sanitize->for_db($control);

		if( $control == "edit" )
		{
			$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

			echo '<td align="right"><b>Collecting:</b></td>
			<td>
				<select name="collecting" style="width: 98%;">
					<option value="'.$row['usr_deck'].'">Current: '.$row['usr_deck'].'</option>';
					$collect = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' ORDER BY `card_filename` ASC");
					while ( $collecting = mysqli_fetch_assoc( $collect ) )
					{
						echo '<option value="'.$collecting['card_filename'].'">'.$collecting['card_deckname'].' ('.$collecting['card_filename'].')</option>';
					}
				echo '</select>
			</td>';
		}

		else if( $control == "manage" )
		{
			$id = isset($_GET['id']) ? $_GET['id'] :  null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-image" role="image" title="Collecting Deck" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<select name="collecting" class="form-control">';
					$current = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_filename`='".$row['usr_deck']."'");
					echo '<option value="'.$row['usr_deck'].'">Current: '.$current['card_deckname'].' ('.$current['card_filename'].')</option>';
					$row_collect = $database->query("SELECT * FROM `tcg_cards` ORDER BY `card_filename` ASC");
					while( $col = mysqli_fetch_assoc($row_collect) )
					{
						echo '<option value="'.$col['card_filename'].'">'.$col['card_deckname'].' ('.$col['card_filename'].')</option>';
					} // end while
				echo '</select>
			</div>';
		}

		else
		{
			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-image" role="image" title="Collecting Deck" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<select name="collecting" class="form-control">
					<option value="">----- Select a deck -----</option>';
					$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_worth`='1' ORDER BY `card_set` ASC, `card_deckname` ASC");
					while( $row = mysqli_fetch_assoc( $query ) )
					{
						$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['card_cat']."'");
						$catNAME = stripslashes($cat['cat_name']);
						$name = stripslashes($row['card_filename']);
						$deckname = stripslashes($row['card_deckname']);
						echo '<option value="'.$name.'">'.$catNAME.' - '.$deckname."</option>\n";
					} // end while
				echo '</select>
			</div>';
		}
	}

	// Referral field
	function Referral( $control )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$control = $sanitize->for_db($control);

		if( $control == "manage" )
		{
			$id = isset($_GET['id']) ? $_GET['id'] :  null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-people" role="image" title="Referral" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<select name="refer" class="form-control" />
					<option value="'.$row['usr_refer'].'">Current: '.$row['usr_refer'].'</option>
					<option>----- Select referral -----</option>
					<option value="None">None (e.g. TCG wiki, Google search)</option>';
					$row_mem = $database->query("SELECT * FROM `user_list` ORDER BY `usr_name` ASC");
					while( $mem = mysqli_fetch_assoc( $row_mem ) )
					{
						$name = stripslashes($mem['usr_name']);
						echo '<option value="'.$name.'">'.$name."</option>\n";
					} // end while
				echo '</select>
			</div>';
		}

		else
		{
			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-people" role="image" title="Referral" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<select name="refer" class="form-control" />
					<option value="None">None (e.g. TCG wiki, Google search)</option>';
					$mem = $database->query("SELECT * FROM `user_list` ORDER BY `usr_name` ASC");
					while( $row = mysqli_fetch_assoc( $mem ) )
					{
						$name = stripslashes($row['usr_name']);
						echo '<option value="'.$name.'">'.$name."</option>\n";
					} // end while
				echo '</select>
			</div>';
		}
	}

	// User status field
	function Status( $control )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$control = $sanitize->for_db($control);

		if( $control == "edit" )
		{
			$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

			echo '<td align="right" width="15%"><b>Status:</b></td>
			<td width="35%">
				<select name="status" style="width: 98%;">
					<option value="'.$row['usr_status'].'">Current: '.$row['usr_status'].'</option>
					<option value="Hiatus">Hiatus</option>
					<option value="Active">Active</option>
				</select>
			</td>';
		}

		else if( $control == "manage" )
		{
			$id = isset($_GET['id']) ? $_GET['id'] :  null;
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");

			echo '<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="bi-activity" role="image" title="Status" data-toggle="tooltip" data-placement="bottom"></i></span>
				</div>
				<select name="status" class="form-control">
					<option value="'.$row['usr_status'].'">Current: '.$row['usr_status'].'</option>
					<option>----- Select status -----</option>
					<option value="Active">Active</option>
					<option value="Pending">Pending</option>
					<option value="Hiatus">Hiatus</option>
					<option value="Inactive">Inactive</option>
					<option value="Retired">Retired</option>
				</select>
			</div>';
		}
	}
}
?>