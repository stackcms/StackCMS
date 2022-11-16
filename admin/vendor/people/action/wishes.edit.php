<?php
/****************************************************
 * Action:			Edit User Wishes
 * Description:		Show page for editing user wishes
 */


// Process edit form
if( isset( $_POST['update'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$type = $sanitize->for_db($_POST['type']);
	$word = $sanitize->for_db($_POST['word']);
	$amnt = $sanitize->for_db($_POST['amount']);
	$color = $sanitize->for_db($_POST['category']);
	$set = $sanitize->for_db($_POST['set']);
	$wish = $sanitize->for_db($_POST['wish']);
	$stat = $sanitize->for_db($_POST['status']);
	$id = $sanitize->for_db($_POST['id']);

	$result = $database->query("UPDATE `user_wishes` SET `wish_name`='$name', `wish_type`='$type', `wish_word`='$word', `wish_amount`='$amnt', `wish_cat`='$cat', `wish_set`='$set', `wish_text`='$wish', `wish_status`='$stat' WHERE `wish_id`='$id'") or print ("Can't update wish.<br />" . mysqli_connect_error());

	header("Location: people.php?mod=members&page=wishes");
}


// Check if wishes ID is valid
if( !isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) )
{
	die("Invalid entry ID.");
}

else
{
	$id = (int)$_GET['id'];
}

$row = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_id`='$id'") or print ("Can't select entry.<br />" . $row . "<br />" . mysqli_connect_error());
$old_name = stripslashes($row['wish_name']);
$old_stat = stripslashes($row['wish_status']);
$old_wish = stripslashes($row['wish_wish']);
$old_type = stripslashes($row['wish_type']);
$old_word = stripslashes($row['wish_word']);
$old_amnt = stripslashes($row['wish_amount']);
$old_cat = stripslashes($row['wish_cat']);
$old_set = stripslashes($row['wish_set']);

echo '<h1>Edit a User Wish</h1>

<div class="box" style="width: 600px;">
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
<input type="hidden" name="id" value="'.$id.'" />
<div class="row">
	<div class="col-4"><b>Wished by:</b></div>
	<div class="col"><input type="text" name="name" class="form-control" value="'.$old_name.'" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Wish Type:</b></div>
	<div class="col">
		<select name="type" class="form-control" />
			<option value="'.$old_type.'">Current: '.$old_type.'</option>
			<option>----- Select a wish type -----</option>
			<option value="1">Spell Choice</option>
			<option value="2">Choice Pack</option>
			<option value="3">Random Pack</option>
			<option value="4">Category Choice</option>
			<option value="5">Deck Release</option>
			<option value="6">Game Rewards</option>
		</select>
	</div>
</div><br />

<div class="row">
	<div class="col-4"><b>Spell Word:</b></div>
	<div class="col"><input type="text" name="word" class="form-control" value="'.$old_word.'" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Card Amount:</b></div>
	<div class="col"><input type="text" name="amount" class="form-control" value="'.$old_amnt.'" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Choice Category:</b></div>
	<div class="col">
		<select name="category" class="form-control" />';
		if( $old_cat == 0 )
		{
			echo '<option value="0">Current: None</option>';
		}

		else
		{
			$get = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='$old_cat'");
			echo '<option value="'.$get['cat_id'].'">Current: '.$get['cat_name'].'</option>';
		}

		echo '<option>----- Select a category -----</option>';
		$c = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_name` ASC");
		while( $cat = mysqli_fetch_assoc( $c ) )
		{
			echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_name'].'</option>';
		}
		echo '</select>
	</div>
</div><br />

<div class="row">
	<div class="col-4"><b>Game Set:</b></div>
	<div class="col">
		<select name="set" class="form-control" />
			<option value="'.$old_set.'">Current: '.$old_set.'</option>
			<option value="None">Not applicable</option>
			<option>----- Select a game set -----</option>
			<option value="Weekly">Weekly Set</option>
			<option value="Set A">Bi-weekly A Set</option>
			<option value="Set B">Bi-weekly B Set</option>
			<option value="Monthly">Monthly Set</option>
			<option value="Special">Special Set</option>
		</select>
	</div>
</div><br />

<div class="row">
	<div class="col-4"><b>Wish Text:</b></div>
	<div class="col"><input type="text" name="wish" value="'.$old_wish.'" class="form-control"></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Status:</b></div>
	<div class="col">
		<select name="status" class="form-control">
			<option value="'.$old_stat.'">Current: '.$old_stat.'</option>
			<option>----- Select a status -----</option>
			<option value="Pending">Pending</option>
			<option value="Granted">Granted</option>
		</select>
	</div>
</div><br />

<input type="submit" name="update" class="btn btn-success" value="Edit Wish" />
</form>
</div>';
?>