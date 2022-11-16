<?php
/***************************************************
 * Action:			Add User Wishes
 * Description:		Show page for adding user wishes
 */


// Process addition form
if( isset( $_POST['add'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$type = $sanitize->for_db($_POST['type']);
	$word = $sanitize->for_db($_POST['word']);
	$amnt = $sanitize->for_db($_POST['amount']);
	$cat = $sanitize->for_db($_POST['category']);
	$set = $sanitize->for_db($_POST['set']);
	$wish = $sanitize->for_db($_POST['wish']);
	$stat = $sanitize->for_db($_POST['status']);
	$date = date('Y-m-d', strtotime("now"));

	// Add wish blurbs for the database
	if( $type == "1" && !empty($word) )
	{
		$wish = "I wish for choice cards spelling ".$word."!";
	}

	if( $type == "2" && !empty($amnt) )
	{
		$wish = "I wish for a pack of ".$amnt." choice cards!";
	}

	if( $type == "3" && !empty($amnt) )
	{
		$wish = "I wish for a pack of ".$amnt." random cards!";
	}

	if( $type == "4" && $color != "None" )
	{
		$wish = "I wish for choice cards from any ".$cat." decks!";
	}

	if( $type == "5" && $amnt == "2" )
	{
		$wish = "I wish for a double deck release!";
	}

	if( $type == "6" && $set == "None" )
	{
		$wish = "I wish for double rewards for the ".$set." set!";
	}

	$result = $database->query("INSERT INTO `user_wishes` ( `wish_name`,`wish_type`,`wish_word`,`wish_amount`,`wish_cat`,`wish__set`,`wish_text`,`wish_status`,`wish_date`) VALUES ('$name','$type','$word','$amnt','$cat','$set','$wish','Pending','$date')") or print ("Can't add wish.<br />" . mysqli_connect_error());

	if( !$result )
	{
		$error[] = "Sorry, there was an error and the wish was not added to the database. ".mysqli_error($result);
	}

	else
	{
		$success[] = "You have successfully added a wish!";
	}
}

// Show add wishes form
echo '<h1>Add a User Wish</h1>
<p>Make sure to only fill up the fields according to the wish type (e.g. Spell Choice should only have the Word field filled).</p>

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

<div class="box" style="width: 600px;">
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'">
<div class="row">
	<div class="col-4"><b>Wish Type:</b></div>
	<div class="col">
		<select name="type" id="type" class="form-control" />
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
	<div class="col"><input type="text" name="word" id="word" class="form-control" placeholder="SUMMER2020" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Card Amount:</b></div>
	<div class="col"><input type="text" name="amount" id="amount" class="form-control" placeholder="0" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Choice Category:</b></div>
	<div class="col">
		<select name="category" id="category" class="form-control" />
			<option value="0">Not applicable</option>
			<option>----- Select a category -----</option>';
			$c = $database->query("SELECT * FROM `tcg_cards_cat`");
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
		<select name="set" id="set" class="form-control" />
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

<input type="submit" name="add" class="btn btn-success" value="Add Wish" />
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>