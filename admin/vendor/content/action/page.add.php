<?php
/****************************************************
 * Action:			Add Page Content
 * Description:		Show page for adding page content
 */


// Process add a page content form
if( isset( $_POST['add'] ) )
{
	$title = $_POST['title'];
	$slug = $sanitize->for_db($_POST['slug']);
	$parent = $sanitize->for_db($_POST['parent']);
	$status = $sanitize->for_db($_POST['status']);
	$timestamp = $_POST['date'];
	$content = $_POST['entry'];
	$content = nl2br($content);

	$content = str_replace("'","\'",$content);
	$title = str_replace("'","\'",$title);

	$result = $database->query("INSERT INTO `tcg_post` (`post_title`,`post_slug`,`post_parent`,`post_content`,`post_status`,`post_type`,`post_date`,`post_auth`,`post_icon`,`post_member`,`post_affiliate`,`post_master`,`post_level`,`post_deck`) VALUES ('$title','$slug','$parent','$content','$status','page','$timestamp','None','None','None','None','None','None','None')") or print("Can't insert into table tcg_post.<br />" . $result . "<br />Error:" . mysqli_connect_error());

	if( !$result )
	{
		$error[] = "Sorry, there was an error and your page content was not added. ".mysqli_error($result)."";
	}

	else
	{
		$success[] = "Your page content has been successfully entered into the database.";
	}
}

echo '<p>Use the form below to create a new page content for your TCG.<br />
Use the <a href="'.$tcgurl.'admin/content.php?#page">edit</a> form to update the information for existing pages.</p>

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
		echo '<div class="box-success"><b>Success!</b> '.$msg.'</div>';
	}
}

echo '<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'">
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr>
	<td width="68%" valign="top">
		<b>Title:</b><br />
		<input type="text" name="title" placeholder="Information" style="width:96%;" /><br /><br />

		<b>Slug:</b> <small><i>Usually the lowercase version of the page\'s title with hyphens (e.g. card-decks)</i></small><br />
		<input type="text" name="slug" placeholder="information" style="width:96%;" /><br /><br />

		<b>Content</b><br />';
		@include($tcgpath.'admin/theme/text-editor.php');
		echo '<textarea name="entry" id="entry" class="textEditor" style="width:96%;" rows="10" /></textarea><br />
		<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small>
	</td>

	<td width="2%">&nbsp;</td>

	<td width="30%" valign="top">
		<b>Publish:</b><br />
		<input type="date" name="date"><br />
		<input type="radio" name="status" value="Draft" /> Draft 
		<input type="radio" name="status" value="Published" /> Publish<br /><br />

		<b>Parent Page:</b><br />
		<select name="parent" style="width: 95%;">';
		$sql = $database->query("SELECT * FROM `tcg_post` WHERE `post_parent`='0' AND `post_type`='page' ORDER BY `post_id`");
		$num = mysqli_num_rows($sql);
		if( $num == 0 )
		{
			echo '<option value="0">None</option>';
		}

		else
		{
			echo '<option value="0">None</option>';
			while( $row = mysqli_fetch_assoc( $sql ) )
			{
				echo '<option value="'.$row['post_id'].'">'.$row['post_title'].'</option>';
			}
		}
		echo '</select>

		<div align="right" style="margin-top:20px;">
			<input type="submit" name="add" class="btn-success" value="Add Page" /> 
			<input type="reset" name="reset" class="btn-cancel" value="Reset" />
		</div>
	</td>
</tr>
</table>
</form>
<center>';
?>