<?php
/************************************************
 * Action:			Add Freebies
 * Description:		Show page for adding freebies
 */


// Process add a freebie form
if( isset( $_POST['add'] ) )
{
	$type = $sanitize->for_db($_POST['type']);
	$word = $sanitize->for_db($_POST['word']);
	$amnt = $sanitize->for_db($_POST['amount']);
	$cat = $sanitize->for_db($_POST['category']);
	$date = date('Y-m-d', strtotime("now"));

	$result = $database->query("INSERT INTO `user_freebies` (`free_type`,`free_word`,`free_amount`,`free_cat`,`free_date`) VALUES ('$type','$word','$amnt','$cat','$date')") or print ("Can't update freebies.<br />" . mysqli_connect_error());

	if( !$result )
	{
		$error[] = "Sorry, there was an error and the freebie was not added to the database. ".mysqli_error($result);
	}

	else
	{
		$success[] = "You have successfully added a freebie!";
	}
}


// Show add a freebie form
echo '<h1>Add a Freebie</h1>
<p>Make sure to only fill out the fields you need (e.g. spell word field for spell choice).</p>

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
			<option value="1">Spell Choice</option>
			<option value="2">Choice Pack</option>
			<option value="3">Random Pack</option>
			<option value="4">Category Choice</option>
		</select>
	</div>
</div><br />

<div class="row">
	<div class="col-4"><b>Spell Word:</b></div>
	<div class="col"><input type="text" name="word" id="word" class="form-control" placeholder="SUMMER2020" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Category Choice:</b></div>
	<div class="col">
		<select name="category" id="category" class="form-control">
			<option value="0">Not applicable</option>
			<option>----- Select a category -----</option>';
			$c = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_name` ASC");
			while( $cat = mysqli_fetch_assoc( $c ) )
			{
				echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_name']."</option>\n";
			}
		echo '</select>
	</div>
</div><br />

<div class="row">
	<div class="col-4"><b>Choice/Random Amount:</b></div>
	<div class="col"><input type="text" name="amount" id="amount" class="form-control" placeholder="0" /></div>
</div><br />

<input type="submit" name="add" id="add" class="btn btn-success" value="Add Freebie" /> 
<input type="reset" name="reset" id="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>