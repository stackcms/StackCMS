<?php
/******************************************************
 * Action:			Edit Chat Messages
 * Description:		Show page for editing chat messages
 */


// Process edit a chat message form
if( isset( $_POST['update'] ) )
{
	$id = $sanitize->for_db($_POST['id']);
	$name = $sanitize->for_db($_POST['name']);
	$url = $sanitize->for_db($_POST['url']);
	$msg = $_POST['message'];
	$msg = str_replace("'", "\'", $msg);

	$result = $database->query("UPDATE `tcg_chatbox` SET `chat_name`='$name', `chat_url`='$url', `chat_msg`='$msg' WHERE `chat_id`='$id'") or print ("Can't update freebies.<br />" . mysqli_connect_error());

	if( !$result )
	{
		$error[] = "Sorry, there was an error and the chat message was not updated. ".mysqli_error($result)."";
	}

	else
	{
		$success[] = "You have successfully updated the chat message!";
	}
}

// Check if chat ID exists
if ( !isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) )
{
	die("Invalid chatbox ID.");
}

else
{
	$id = (int)$_GET['id'];
}

// Show edit a chat message form
$row = $database->get_assoc("SELECT * FROM `tcg_chat` WHERE `chat_id`='$id'") or print ("Can't select chat message.<br />" . $row . "<br />" . mysqli_connect_error());
$old_name = stripslashes($row['chat_name']);
$old_url = stripslashes($row['chat_url']);
$old_msg = stripslashes($row['chat_msg']);

echo '<h1>Edit a Chat Message</h1>
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

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
<input type="hidden" name="id" value="'.$id.'" />
<div class="box">
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Member Name:</b></span>
		</div>
		<input type="text" name="name" class="form-control" value="'.$old_name.'">
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Trade Post:</b></span>
		</div>
		<input type="text" name="url" class="form-control" value="'.$old_url.'">
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Message:</b></span>
		</div>
		<textarea name="message" cols="50" rows="4" class="form-control">'.$old_url.'</textarea>
	</div>

	<input type="submit" name="update" id="update" class="btn btn-success" value="Edit message" /> 
	<input type="reset" name="reset" id="reset" class="btn btn-danger" value="Reset" />
</div><!-- .box -->
</form>';
?>