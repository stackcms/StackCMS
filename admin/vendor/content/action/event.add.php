<?php
/***************************************************
 * Action:			Add Event Cards
 * Description:		Show page for adding event cards
 */


// Process add an event card form
$img = (isset($_FILES['img']) ? $_FILES['img'] : null);
$file = (isset($_GET['name']) ? $_GET['name'] : null);

if( isset( $_POST['add'] ) )
{
	$filename = $sanitize->for_db($_POST['filename']);
	$title = $sanitize->for_db($_POST['title']);
	$group = $sanitize->for_db($_POST['group']);
	$released = $_POST['date'];

	$img_desc = $upload->reArrayFiles($img);
	$upload->folderPath('images','cards');

	$insert = $database->query("INSERT INTO `tcg_cards_event` (`event_filename`,`event_title`,`event_group`,`event_date`) VALUES ('$filename','$title','$group','$released')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the event card was not added. ".mysqli_error($insert)."";
	}

	else
	{
		$success[] = "The event card was successfully added to the database!";
	}
}


// Show add an event card form
echo '<h1>Add an Event Card</h1>
<p>Use this form to add an event card to the database.<br />
Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for existing event cards.</p>

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

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action='.$act.'" multipart="" enctype="multipart/form-data">
<div class="col" style="width: 600px;">
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Title:</b></span>
		</div>
		<input type="text" name="title" placeholder="e.g. Halloween 2021" class="form-control" />
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>File Name:</b></span>
		</div>
		<input type="text" name="filename" placeholder="e.g. ec-halloween2021" class="form-control" />
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Group:</b></span>
		</div>
		<select name="group" class="form-control" />
			<option>--- Select Group ---</option>
			<option value="Events">Events</option>
			<option value="Holidays">Holidays</option>
			<option value="Layouts">Layouts</option>
			<option value="Milestones">Milestones</option>
			<option value="Monthly">Monthly</option>
			<option value="Seasons">Seasons</option>
		</select>
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Release Date:</b></span>
		</div>
		<input type="date" name="date" class="form-control" />
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Upload card</b></span>
		</div>
		<div class="custom-file">
			<input type="file" name="img[]" class="custom-file-input" id="inputGroupFile">
			<label class="custom-file-label" for="inputGroupFile">Choose file</label>
		</div>
	</div>

	<input type="submit" name="add" class="btn btn-success" value="Add event card" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</div>
</form>';
?>