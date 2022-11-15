<?php
/* Add decks to the user's wishlist.
 * This is the Add to Wishlist button that you can
 * find under the mastery badge from the cards page.
 */

$database = new Database;
$sanitize = new Sanitize;

$deck = (isset($_GET['deck']) ? $_GET['deck'] : null);
$login = (isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null);
$action = (isset($_POST['action']) ? $_POST['action'] : null);

$row_mem = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
$usr = isset($row_mem['usr_name']) ? $row_mem['usr_name'] : null;

$row_memwish = $database->get_assoc("SELECT * FROM `user_wishlist` WHERE `wlist_deck`='$deck' AND `wlist_name`='$usr'");
$wisher = isset($row_memwish['wlist_name']) ? $row_memwish['wlist_name'] : null;

if( !empty( $wisher ) )
{
	if( $action == "remove" )
	{
		$w_deck = $_POST['deck'];
		$date = date("Y-m-d");
		if( empty( $login ) )
		{
			echo "<i>Please login.</i>";
		}

		else
		{
			$database->query("DELETE FROM `user_wishlist` WHERE `wlist_name`='$wisher' AND `wlist_deck`='$deck' LIMIT 1");
			echo '<form method="POST">
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="deck" value="'.$deck.'">
			<input type="hidden" name="name" value="'.$usr.'">
			<input type="hidden" name="action" value="add">
			<button class="btn-success" type="submit" name="submit" id="submit"><span class="fas fa-heart" aria-hidden="true" alt="Add to Wishlist"></span> Add to Wishlist</button>
			</form>';
		}
	}

	else
	{
		echo '<form method="POST">
		<input type="hidden" name="action" value="remove">
		<input type="hidden" name="deck" value="'.$deck.'">
		<input type="hidden" name="name" value="'.$usr.'">
		<input type="hidden" name="action" value="remove">
		<button class="btn-danger" type="submit" name="submit" id="submit"><span class="fas fa-heart-broken" aria-hidden="true" alt="Remove from Wishlist"></span> Remove from Wishlist</button>
		</form>';
	}
}


else
{
	if( $action == "add" )
	{
		$w_deck = $_POST['deck'];
		$date = date("Y-m-d");
		$activity = '<span class="fas fa-heart" aria-hidden="true"></span> <a href="/members.php?id='.$usr.'">'.$usr.'</a> added <a href="/cards.php?view=released&deck='.$w_deck.'">'.$w_deck.'</a> their wishlist.';
		if( empty( $login ) )
		{
			echo "<i>Please login.</i>";
		}

		else {
			$database->query("INSERT INTO `user_wishlist` (`wlist_id`, `wlist_name`, `wlist_deck`, `wlist_date`) VALUES ('', '$usr', '$w_deck', '$date')");
			$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_date`) VALUES ('$usr', '$activity', '$date')");
			echo '<form method="POST">
			<input type="hidden" name="action" value="remove">
			<input type="hidden" name="deck" value="'.$deck.'">
			<input type="hidden" name="name" value="'.$usr.'">
			<input type="hidden" name="action" value="remove">
			<button class="btn-danger" type="submit" name="submit" id="submit"><span class="fas fa-heart-broken" aria-hidden="true" alt="Remove from Wishlist"></span> Remove from Wishlist</button>
			</form>';
		}
	}

	else
	{
		echo '<form method="POST">
		<input type="hidden" name="action" value="add">
		<input type="hidden" name="deck" value="'.$deck.'">
		<input type="hidden" name="name" value="'.$usr.'">
		<input type="hidden" name="action" value="add">
		<button class="btn-success" type="submit" name="submit" id="submit"><span class="fas fa-heart" aria-hidden="true" alt="Add to Wishlist"></span> Add to Wishlist</button>
		</form>';
	}
}
?>