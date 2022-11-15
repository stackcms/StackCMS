<?php
/****************************************************************
 * Module:			Submit a Task
 * Description:		Process user member deck task submission form
 */


// Process submit a member deck task form
if( isset( $_POST['submit'] ) )
{
	$uid = intval($_POST['id']);
	$task = $sanitize->for_db($_POST['task']);
	$proof = $_POST['proof'];

	$proof = nl2br($proof);
	$proof = str_replace("'", "\'", $proof);
	$task = str_replace("'", "\'", $task);

	$userinfo = $database->get_assoc("SELECT * FROM `tcg_cards_user` WHERE `ud_id`='$uid'");
	$user = $userinfo['ud_deck'];

	if( $task !== '' )
	{
		$task = explode('; ',$task);
		$proof = explode('; ',$proof);
		function adddeck(&$value,$key)
		{
			$value = trim($value);
			$value = ''.$value.'';
		}
		array_walk($task,'adddeck');

		if( empty( $userinfo['ud_cards'] ) && empty( $userinfo['ud_task_id'] ) && empty( $userinfo['ud_proof_logs'] ) )
		{
			$c = implode('; ',$task);
			$t = implode('; ',$task);
			$p = implode('; ',$proof);
		}

		else
		{
			$task = implode('; ',$task);
			$proof = implode('; ',$proof);
			$c = $userinfo['ud_cards'].'; '.$task;
			$t = $userinfo['ud_task_id'].'; '.$task;
			$p = $userinfo['ud_proof_logs'].'; '.$proof;
		}
	}

	$result = $database->query("UPDATE `tcg_cards_user` SET `ud_task_id`='$t', `ud_proof_logs`='$p' WHERE `ud_id`='$uid' LIMIT 1");

	if( !$result )
	{
		$error[] = "Failed to update the member deck. ".mysqli_error($result);
	}

	else
	{
		$success[] = "Your completed task has been submitted! Please wait for an admin to review your task before activating your card!";
	}
}

// Check user's member deck
$sql = $database->get_assoc("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$player' AND `ud_completed`='0'");
$counts = $database->num_rows("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$player' AND `ud_completed`='0'");
if( $counts == 0 ) {
	echo '<h1>Member Deck : Halt!</h1>
	<p>You haven\'t activated your member deck yet! Please activate them first before submitting a task.</p>';
}

else
{
	echo '<h1>Member Deck : Submit Task</h1>
	<p>If you have successfully completed a specific task assigned to your member deck card, feel free to submit it by using the form below. Make sure to <u>submit one completed task per form</u>.</p>

	<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="box-error"><b>Error!</b> '.$msg.'</div>';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="box-success"><b>Success!</b> '.$msg.'</div>';
		}
	}
	echo '</center>

	<form action="'.$tcgurl.'services.php?form='.$form.'" method="post">
	<input name="id" type="hidden" value="'.$sql['ud_id'].'">
	<table width="100%" cellspacing="3" class="table table-striped table-sliced">
		<tr>
			<td width="10%">Task:</td>
			<td width="90%"><select name="task" style="width:99%;">';
	$c = $database->num_rows("SELECT * FROM `user_decks`");
	for( $i=1; $i<=$c; $i++ )
	{
		$task = $database->get_assoc("SELECT * FROM `user_decks` WHERE `task_id`='$i'");
		echo '<option value="'.$task['task_id'].'">#'.$task['task_id'].': '.$task['task_name'].'</option>';
	}
		echo '</select></td>
		</tr>
		<tr>
			<td>Proof:</td>
			<td><textarea name="proof" rows="3" style="width:96%;"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="submit" id="submit" class="btn-success" value="Submit Task" />
				<input type="submit" name="reset" id="reset" class="btn-danger" value="Reset" />
			</td>
		</tr>
	</table>
	</form>';
}
?>