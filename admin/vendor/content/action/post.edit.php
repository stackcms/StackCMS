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
		echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
	}
}

if( isset( $success ) )
{
	foreach( $success as $msg )
	{
		echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
	}
}

echo '<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
<input type="hidden" name="id" value="'.$id.'" />
<input type="hidden" name="author" value="'.$row['post_auth'].'" />
<table width="100%" cellspacing="0" cellpadding="5">
<tr>
	<td width="68%" valign="top">
		<table width="100%">
		<tr>
			<td width="49%">
				<b>Title:</b><br />
				<input type="text" name="title" value="'.$old_title.'" style="width: 93%;" />
			</td>
			<td width="2%"></td>
			<td width="49%">
				<b>Icon:</b> <i>(Optional)</i><br />
				<input type="text" name="icon" value="'.$row['post_icon'].'" style="width: 93%;" />
			</td>
		</tr>
		</table><br />

		<b>Content:</b><br />';
		@include($tcgpath.'admin/theme/text-editor.php');
		echo '<textarea style="width:96%" rows="15" name="entry" id="entry">'.$old_entry.'</textarea><br />
		<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small><br /><br />

		<b>New Masteries:</b><br />
		<textarea style="width: 96%" rows="6" name="masters" id="masters">'.$row['post_master'].'</textarea><br /><br />

		<b>New Level Ups:</b><br />
		<textarea style="width: 96%" rows="6" name="levels" id="levels">'.$row['post_level'].'</textarea><br /><br />

		<b>New Affiliates:</b><br />
		<small><i>Use HTML links to display the linked affiliated TCG on the update. Otherwise type <code>None</code>.</i></small>
		<textarea name="affiliates" id="affiliates" style="width:96%;">'.$old_aff.'</textarea>
	</td>

	<td width="2%">&nbsp;</td>

	<td width="30%" valign="top">
		<b>Publish:</b><br />
		<input type="date" name="date" value="'.$row['post_date'].'"><br />';
		if( $row['post_status'] == "Draft" )
		{
			echo '<input type="radio" name="status" value="Draft" checked /> Draft 
			<input type="radio" name="status" value="Published" /> Published 
			<input type="radio" name="status" value="Scheduled" /> Scheduled 
			<input type="radio" name="status" value="Archived" /> Archived';
		}

		else if( $row['post_status'] == "Published" )
		{
			echo '<input type="radio" name="status" value="Draft" /> Draft 
			<input type="radio" name="status" value="Published" checked /> Published 
			<input type="radio" name="status" value="Scheduled" /> Scheduled 
			<input type="radio" name="status" value="Archived" /> Archived';
		}

		else if( $row['post_status'] == "Scheduled" )
		{
			echo '<input type="radio" name="status" value="Draft" /> Draft 
			<input type="radio" name="status" value="Published" /> Published 
			<input type="radio" name="status" value="Scheduled" checked /> Scheduled 
			<input type="radio" name="status" value="Archived" /> Archived';
		}

		else
		{
			echo '<input type="radio" name="status" value="Draft" /> Draft 
			<input type="radio" name="status" value="Published" /> Published 
			<input type="radio" name="status" value="Scheduled" /> Scheduled 
			<input type="radio" name="status" value="Archived" checked /> Archived';
		}

		echo '<br /><br />

		<b>Post Type:</b><br />';
		if( $row['post_type'] == "post" )
		{
			echo '<input type="radio" name="type" value="post" checked /> Post 
			<input type="radio" name="type" value="page" /> Page';
		}

		else
		{
			echo '<input type="radio" name="type" value="post" /> Post 
			<input type="radio" name="type" value="page" checked /> Page';
		}

		echo '<br /><br />

		<b>New Decks:</b><br />
		<textarea name="decks" id="decks" style="width: 90%;" rows="2" />'.$row['post_deck'].'</textarea><br />
		<input type="text" name="amount" id="amount" value="'.$row['post_amount'].'" style="width: 58%;" /> <small><i>Card Amount</i></small><br /><br />

		<b>New Members:</b><br />
		<small><i>Type <code>None</code> on the fields if there are no new members or referrals.</i></small>
		<input type="text" name="members" id="members" value="'.$row['post_member'].'" style="width:90%;" /><br /><br />

		<b>Referrals:</b><br />
		<input type="text" name="referrals" id="referrals" value="'.$row['post_referral'].'" style="width:90%;" /><br /><br />

		<b>Games:</b><br />
		<input type="text" name="games" value="'.$row['post_game'].'" style="width: 90%;" /><br /><br />

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
	echo '<br /><br />
	<div align="right" style="margin-top:20px;">
		<input type="submit" name="update" class="btn-success" value="Edit Blog" /> 
		<input type="reset" name="reset" class="btn-cancel" value="Reset" />
	</div>
	</td>
</tr>
</table>
</form>
</center>';
?>