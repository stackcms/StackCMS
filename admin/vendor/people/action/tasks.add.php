<?php
/**********************************************************
 * Action:			Add a Member Deck Task
 * Description:		Show page for adding a member deck task
 */


// Process add a member deck task form
if( isset( $_POST['submit'] ) )
{
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

	$result = $database->query("INSERT INTO `user_decks` (`task_name`,`task_info`,`task_card`,`task_proof`) VALUES ('$task','$info','$card','$proof')");

	if( !$result )
	{
		$error[] = "Sorry, there was an error and your member deck task was not added. ".mysqli_error($result);
	}

	else
	{
		$success[] = "Your member deck task has successfully been entered into the database.";
	}
}


echo '<h1>Member Decks <span class="fas fa-angle-right" aria-hidden="true"></span> Add a Task</h1>
<p>Use this form to add a new task for your member deck to the database. Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for an existing member deck task.</p>

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
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'">
<div class="row">
	<div class="col-3"><b>Task:</b></div>
	<div class="col"><textarea name="task" rows="3" class="form-control" />(e.g. Master 5 decks.)</textarea></div>
</div><br />

<div class="row">
	<div class="col-3"><b>Info:</b></div>
	<div class="col"><textarea name="info" rows="3" class="form-control" />(Instructions for completing this task)</textarea></div>
</div><br />

<div class="row">
	<div class="col-3"><b>Proof:</b></div>
	<div class="col"><textarea name="proof" rows="3" class="form-control" />(Instructions for submitting this task)</textarea></div>
</div><br />

<div class="row">
	<div class="col-3"><b>Card #:</b></div>
	<div class="col"><input type="text" name="card" placeholder="01" class="form-control" /></div>
</div><br />

<input type="submit" name="submit" class="btn btn-success" value="Add Task"/> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>