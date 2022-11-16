<?php
/*************************************************
 * Action:			Edit Freebies
 * Description:		Show page for editing freebies
 */


// Process edit a freebie form
if( isset( $_POST['update'] ) )
{
	$type = $sanitize->for_db($_POST['type']);
	$word = $sanitize->for_db($_POST['word']);
	$amnt = $sanitize->for_db($_POST['amount']);
	$cat = $sanitize->for_db($_POST['category']);
	$id = $sanitize->for_db($_POST['id']);

	$result = $database->query("UPDATE `user_freebies` SET `free_name`='$name', `free_type`='$type', `free_word`='$word', `free_amount`='$amnt', `free_cat`='$cat' WHERE `free_id`='$id'") or print ("Can't update freebies.<br />" . mysqli_connect_error());

	if( !$result )
	{
		$error[] = "Sorry, there was an error and the freebie was not updated. ".mysqli_error($result);
	}

	else
	{
		$success[] = "You have successfully updated the freebie!";
	}
}


// Check if freebie ID is valid
if( !isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) )
{
	die("Invalid freebie ID.");
}

else
{
	$id = (int)$_GET['id'];
}


// Show edit a freebie form
$row = $database->get_assoc("SELECT * FROM `user_freebies` WHERE `free_id`='$id'") or print ("Can't select freebie.<br />" . $row . "<br />" . mysqli_connect_error());
$old_type = stripslashes($row['free_type']);
$old_word = stripslashes($row['free_word']);
$old_amnt = stripslashes($row['free_amount']);
$old_cat = stripslashes($row['free_cat']);

// Display title of wish type
if( $old_type == 1 ) { $typeName = 'Spell Choice'; }
elseif( $old_type == 2 ) { $typeName = 'Choice Pack'; }
elseif( $old_type == 3 ) { $typeName = 'Random Pack'; }
elseif( $old_type == 4 ) { $typeName = 'Category Choice'; }

echo '<h1>Edit a Freebie</h1>
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
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
<input type="hidden" name="id" value="'.$id.'" />
<div class="row">
	<div class="col-4"><b>Wish Type:</b></div>
	<div class="col">
		<select name="type" id="type" class="form-control" />
			<option value="'.$old_type.'">Current: '.$typeName.'</option>
			<option>----- Select wish type -----</option>
			<option value="1">Spell Choice</option>
			<option value="2">Choice Pack</option>
			<option value="3">Random Pack</option>
			<option value="4">Category Choice</option>
		</select>
	</div>
</div><br />

<div class="row">
	<div class="col-4"><b>Spell Word:</b></div>
	<div class="col"><input type="text" name="word" id="word" class="form-control" value="'.$old_word.'" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Category Choice:</b></div>
	<div class="col">
		<select name="category" id="category" class="form-control" />';
		if( $old_color == 0 )
		{
			echo '<option value="0">Current: Not applicable</option>';
		}

		else
		{
			$get = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$old_cat."'");
			echo '<option value="'.$get['cat_id'].'">Current: '.$get['cat_name'].'</option>';
		}

		echo '<option>----- Select a category -----</option>';
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
	<div class="col"><input type="text" name="amount" id="amount" class="form-control" value="'.$old_amnt.'" /></div>
</div><br />

<input type="submit" name="update" id="update" class="btn btn-success" value="Edit Freebie" /> 
<input type="reset" name="reset" id="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>