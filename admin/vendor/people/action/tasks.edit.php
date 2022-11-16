<?php
/***********************************************************
 * Action:			Edit a Member Deck Task
 * Description:		Show page for editing a member deck task
 */


// Process edit a member deck task form
if( isset( $_POST['update'] ) )
{
	$id = $sanitize->for_db($_POST['id']);
	$card = $sanitize->for_db($_POST['card']);
	$task = $_POST['task'];
	$info = $_POST['info'];
	$proof = $_POST['proof'];

	$task = str_replace("'", "\'", $task);
	$info = str_replace("'", "\'", $info);
	$proof = str_replace("'", "\'", $proof);

	$task = nl2br($task);
	$info = nl2br($info);
	$proof = nl2br($proof);

	$update = $database->query("UPDATE `user_decks` SET `task_name`='$task', `task_info`='$info', `task_card`='$card', `task_proof`='$proof' WHERE `task_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the member deck task was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "You have successfully updated the member deck task!";
	}
}


// Check if task ID is valid
if( !isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) )
{
	die("Invalid freebie ID.");
}

else
{
	$id = (int)$_GET['id'];
}


// Show edit a member deck task form
$row = $database->get_assoc("SELECT * FROM `user_decks` WHERE `task_id`='$id'");
echo '<h1>Member Decks <span class="fas fa-angle-right" aria-hidden="true"></span> Edit a Task</h1>
<p>Use this form to edit an existing member deck task in the database. Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add a new task for your member decks.</p>

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

<div class="box" style="width: 700px;">
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
<input type="hidden" name="id" value="'.$id.'" />
<div class="row">
	<div class="col-3"><b>Task:</b></div>
	<div class="col"><textarea name="task" rows="3" class="form-control" />'.$row['task_name'].'</textarea></div>
</div><br />

<div class="row">
	<div class="col-3"><b>Info:</b></div>
	<div class="col"><textarea name="info" rows="3" class="form-control" />'.$row['task_info'].'</textarea></div>
</div><br />

<div class="row">
	<div class="col-3"><b>Proof:</b></div>
	<div class="col"><textarea name="proof" rows="3" class="form-control" />'.$row['task_proof'].'</textarea></div>
</div><br />

<div class="row">
	<div class="col-3"><b>Card #:</b></div>
	<div class="col"><input type="text" name="card" value="'.$row['task_card'].'" class="form-control" /></div>
</div><br />

<input type="submit" name="update" class="btn btn-success" value="Edit Task" />
<input type="submit" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>