<?php
/***************************************************
 * Action:			Add Blog Posts
 * Description:		Show page for adding a blog post
 */


// Process add a blog post form
if( isset( $_POST['add'] ) )
{
	$mem = $sanitize->for_db($_POST['members']);
	$lvl = $sanitize->for_db($_POST['levels']);
	$mas = $sanitize->for_db($_POST['masters']);
	$decks = $sanitize->for_db($_POST['decks']);
	$wish = $sanitize->for_db($_POST['wish']);
	$card = $sanitize->for_db($_POST['amount']);
	$stat = $sanitize->for_db($_POST['status']);
	$auth = $sanitize->for_db($_POST['author']);
	$icon = $sanitize->for_db($_POST['icon']);
	$game = $sanitize->for_db($_POST['games']);
	$refer = $sanitize->for_db($_POST['referrals']);
	$aff = $_POST['affiliates'];
	$title = $_POST['title'];
	$entry = $_POST['entry'];

	$entry = str_replace("'","\'",$entry);
	$title = str_replace("'","\'",$title);

	$timestamp = $_POST['date'];
	$entry = nl2br($entry);

	$result = $database->query("INSERT INTO `tcg_post` (`post_date`,`post_title`,`post_auth`,`post_icon`,`post_member`,`post_master`,`post_level`,`post_affiliate`,`post_game`,`post_referral`,`post_deck`,`post_status`,`post_wish`,`post_amount`,`post_content`,`post_type`) VALUES ('$timestamp','$title','$auth','$icon','$mem','$mas','$lvl','$aff','$game','$refer','$decks','$stat','$wish','$card','$entry','post')") or print("Can't insert into table tcg_post.<br />" . $result . "<br />Error:" . mysqli_connect_error());

	if( !$result )
	{
		$error[] = "Sorry, there was an error and your blog entry was not added. ".mysqli_error($result)."";
	}

	else
	{
		$success[] = "Your blog entry has successfully been entered into the database.";
	}
}

echo '<h1>Add a Blog Post</h1>
<p>Use the form below to create a new blog post for your TCG\'s weekly update.<br />
If you want to update the information for an existing blog post, kindly use the <a href="'.$PHP_SELF.'?mod='.$mod.'#post">edit form</a> instead.</p>

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

echo '<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'">
<input type="hidden" name="author" value="'.$player.'" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="68%" valign="top">
		<table width="100%">
		<tr>
			<td width="49%">
				<b>Title:</b><br />
				<input type="text" name="title" style="width: 93%;" />
			</td>
			<td width="2%"></td>
			<td width="49%">
				<b>Icon:</b> <i>(Optional)</i><br />
				<input type="text" name="icon" placeholder="image file (e.g. icon.png)" style="width: 93%;" />
			</td>
		</tr>
		</table><br />

		<b>Content:</b><br />';
		@include($tcgpath.'admin/theme/text-editor.php');
		echo '<textarea name="entry" id="entry" class="textEditor" style="width: 93%;" rows="10" /></textarea><br />
		<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small><br /><br />

		<b>New Masteries:</b><br />
		<textarea name="masters" style="width: 93%;" rows="4" /></textarea><br /><br />

		<b>New Level Ups:</b><br />
		<textarea name="levels" style="width: 93%;" rows="4" /></textarea><br /><br />

		<b>New Affiliates:</b><br />
		<small><i>Use HTML links to display the linked affiliated TCG on the update. Otherwise type <code>None</code>.</i></small>
		<textarea name="affiliates" style="width: 93%;" rows="4" /></textarea>
	</td>

	<td width="2%">&nbsp;</td>

	<td width="30%" valign="top">
		<b>Publish:</b><br />
		<input type="date" name="date"><br />
		<input type="radio" name="status" value="Draft" /> Draft 
		<input type="radio" name="status" value="Published" /> Publish 
		<input type="radio" name="status" value="Scheduled" /> Schedule<br /><br />

		<b>New Decks:</b><br />
		<textarea name="decks" style="width: 90%;" rows="2" /></textarea><br />
		<input type="text" name="amount" value="" style="width: 58%;" /> <small><i>Card Amount</i></small><br /><br />

		<b>New Members:</b><br />
		<small><i>Type <code>None</code> on the fields if there are no new members or referrals.</i></small>
		<input type="text" name="members" value="" style="width: 90%;" /><br /><br />

		<b>Referrals:</b><br />
		<input type="text" name="referrals" value="" style="width: 90%;" /><br /><br />

		<b>Games:</b><br />
		<input type="text" name="games" value="" style="width: 90%;" /><br /><br />

		<b>Wishes:</b><br />
		<small><i>Select the appropriate choice if there are granted wishes or none for this update.</i></small><br />
		<input type="radio" name="wish" value="Yes" /> Yes &nbsp;&nbsp;&nbsp; 
		<input type="radio" name="wish" value="None" /> None

		<div align="right" style="margin-top:20px;">
			<input type="submit" name="add" class="btn-success" value="Add Blog" /> 
			<input type="reset" name="reset" class="btn-cancel" value="Reset" />
		</div>
	</td>
</tr>
</table>
</form>
</center>';
?>