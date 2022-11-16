<?php
/****************************************************
 * Action:			Edit Blog Posts
 * Description:		Show page for editing a blog post
 */


// Process edit a blog post form
if( isset( $_POST['update'] ) )
{
	$timestamp = $_POST['date'];
	$id = $sanitize->for_db($_POST['id']);
	$mem = $sanitize->for_db($_POST['members']);
	$mas = $sanitize->for_db($_POST['masters']);
	$lvl = $sanitize->for_db($_POST['levels']);
	$decks = $sanitize->for_db($_POST['decks']);
	$wish = $sanitize->for_db($_POST['wish']);
	$amount = $sanitize->for_db($_POST['amount']);
	$stat = $sanitize->for_db($_POST['status']);
	$icon = $sanitize->for_db($_POST['icon']);
	$game = $sanitize->for_db($_POST['games']);
	$auth = $sanitize->for_db($_POST['author']);
	$refer = $sanitize->for_db($_POST['referrals']);
	$type = $sanitize->for_db($_POST['type']);
	$aff = $_POST['affiliates'];
	$entry = $_POST['entry'];
	$title = $_POST['title'];

	$entry = nl2br($entry);
	$entry = str_replace("'","\'",$entry);
	$title = str_replace("'","\'",$title);

	$update = $database->query("UPDATE `tcg_post` SET `post_date`='$timestamp', `post_title`='$title', `post_icon`='$icon', `post_auth`='$auth', `post_member`='$mem', `post_master`='$mas', `post_level`='$lvl', `post_affiliate`='$aff', `post_game`='$game', `post_referral`='$refer', `post_deck`='$decks', `post_wish`='$wish', `post_amount`='$amount', `post_content`='$entry', `post_status`='$stat', `post_type`='$type' WHERE `post_id`='$id' LIMIT 1") or print ("Can't update entry.<br />" . mysqli_connect_error());

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the blog entry was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The blog entry has been updated successfully!";
	}
}


// Check if ID is valid
if( !isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) )
{
	die("Invalid entry ID.");
}

else
{
	$id = (int)$_GET['id'];
}

// Show edit a blog post form
$row = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_id`='$id' AND `post_type`='post'") or print ("Can't select entry.<br />" . $row . "<br />" . mysqli_connect_error());
$old_title = htmlspecialchars($row['post_title']);
$old_aff = htmlspecialchars($row['post_affiliate']);
$old_title = str_replace('"','\'',$old_title);
$old_entry = str_replace("<br />", " ", nl2br($row['post_content']));

echo '<h1>Edit a Blog Post</h1>
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
<input type="hidden" name="author" value="'.$row['post_auth'].'" />
<div class="row">
	<div class="col">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Title</b></span>
				</div>
				<input type="text" name="title" class="form-control" value="'.$old_title.'">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Icon</b></span>
				</div>
				<input type="text" name="icon" class="form-control" value="'.$row['post_icon'].'">
				<div class="input-group-append">
					<span class="input-group-text"><i>(Optional)</i></span>
				</div>
			</div>

			<b>Content:</b><br />';
			@include($tcgpath.'admin/theme/text-editor.php');
			echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small><br />
			<textarea name="entry" id="entry" class="form-control" rows="10" />'.$old_entry.'</textarea><br />

			<b>Masteries:</b><br />
			<textarea name="masters" class="form-control" rows="4" />'.$row['post_master'].'</textarea><br />

			<b>Level Ups:</b><br />
			<textarea name="levels" class="form-control" rows="4" />'.$row['post_level'].'</textarea><br />

			<b>Affiliates:</b><br />
			<small><i>Use HTML links to display the linked affiliated TCG on the update. Otherwise type <code>None</code>.</i></small><br />
			<textarea name="affiliates" class="form-control" rows="4" />'.$old_aff.'</textarea>
		</div><!-- box -->
	</div><!-- col -->

	<div class="col-4">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Publish</b></span>
				</div>
				<input type="date" name="date" class="form-control" value="'.$row['post_date'].'">
			</div>

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Status</b></span>
				</div>
				<select name="status" class="form-control">
					<option value="'.$row['post_status'].'">Current: '.$row['post_status'].'</option>
					<option>----- Select status -----</option>
					<option value="Draft">Draft</option>
					<option value="Published">Published</option>
					<option value="Scheduled">Scheduled</option>
				</select>
			</div>

			<b>Post Type:</b><br />';
			if( $row['post_type'] == "post" )
			{
				echo '<input type="radio" name="type" value="post" checked /> Post &nbsp;&nbsp;&nbsp; 
				<input type="radio" name="type" value="page" /> Page';
			}

			else
			{
				echo '<input type="radio" name="type" value="post" /> Post &nbsp;&nbsp;&nbsp; 
				<input type="radio" name="type" value="page" checked /> Page';
			}

			echo '<br /><br />

			<b>New Decks:</b><br />
			<textarea name="decks" class="form-control" rows="2" />'.$row['post_deck'].'</textarea><br />

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Amount</b></span>
				</div>
				<input type="text" name="amount" value="'.$row['post_amount'].'" class="form-control" />
			</div>

			<small><i>Type <code>None</code> on the fields if there are no new members or referrals.</i></small><br />
			<b>New Members:</b><br />
			<input type="text" name="members" value="'.$row['post_member'].'" class="form-control" /><br />

			<b>Referrals:</b><br />
			<input type="text" name="referrals" value="'.$row['post_referral'].'" class="form-control" /><br />

			<b>Games:</b><br />
			<input type="text" name="games" value="'.$row['post_game'].'" class="form-control" /><br />

			<b>Wishes:</b><br />
			<small><i>Select the appropriate choice if there are granted wishes or none for this update.</i></small><br />';
			if( $row['post_wish'] == "Yes" )
			{
				echo '<input type="radio" name="wish" value="Yes" checked /> Yes &nbsp;&nbsp;&nbsp; 
				<input type="radio" name="wish" value="None" /> None';
			}

			else
			{
				echo '<input type="radio" name="wish" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; 
				<input type="radio" name="wish" value="None" checked /> None';
			}

			echo '<div align="right" style="margin-top:20px;">
				<input type="submit" name="update" class="btn btn-success" value="Edit Blog" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div>
		</div><!-- box -->
	</div><!-- col-4 -->
</div><!-- row -->
</form>';
?>