<?php
/*******************************************************
 * Action:			Edit Page Content
 * Description:		Show page for editing a page content
 */


// Process add a page content form
if( isset( $_POST['update'] ) )
{
	$title = $_POST['title'];
	$id = $sanitize->for_db($_POST['id']);
	$slug = $sanitize->for_db($_POST['slug']);
	$parent = $sanitize->for_db($_POST['parent']);
	$status = $sanitize->for_db($_POST['status']);
	$timestamp = $_POST['date'];
	$content = $_POST['entry'];
	$content = nl2br($content);

	$content = str_replace("'","\'",$content);
	$title = str_replace("'","\'",$title);

	$update = $database->query("UPDATE `tcg_post` SET `post_date`='$timestamp', `post_title`='$title', `post_slug`='$slug', `post_parent`='$parent', `post_content`='$content', `post_status`='$status', `post_type`='page' WHERE `post_id`='$id' LIMIT 1") or print ("Can't update page content.<br />" . mysqli_connect_error());

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the page content was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The page content has been updated successfully!";
	}
}


// Check if ID is valid
if( !isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']) ) {
	die("Invalid page ID.");
}

else
{
	$id = (int)$_GET['id'];
}

// Show edit a page content form
$row = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_id`='$id' AND `post_type`='page'") or print ("Can't select page.<br />" . $sql . "<br />" . mysqli_connect_error());
$old_title = stripslashes($row['post_title']);
$old_slug = stripslashes($row['post_slug']);
$old_parent = stripslashes($row['post_parent']);
$old_status = stripslashes($row['post_status']);
$old_title = str_replace('"','\'',$old_title);
$old_content = str_replace("<br />", " ", nl2br($row['post_content']));

echo '<center>';
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
<table width="100%" cellspacing="5" cellpadding="0">
<tr>
	<td width="68%" valign="top">
		<b>Title:</b><br />
		<input type="text" name="title" id="title" value="'.$old_title.'" style="width:96%;" /><br /><br />

		<b>Slug:</b> <small><i>Usually the lowercase version of the page\'s title with hyphens (e.g. card-decks)</i></small><br />
		<input type="text" name="slug" id="slug" value="'.$old_slug.'" style="width:96%;" /><br /><br />

		<b>Content:</b><br />';
		@include($tcgpath.'admin/theme/text-editor.php');
		echo '<textarea style="width:96%" rows="10" name="entry" id="entry" class="textEditor" id="content">'.$old_content.'</textarea><br />
		<i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i>
	</td>

	<td width="2%">&nbsp;</td>

	<td width="30%" valign="top">
		<b>Publish:</b><br />
		<input type="date" name="date" value="'.$row['post_date'].'"><br /><br />';
		if( $old_status == "Published" )
		{
			echo '<input type="radio" name="status" value="Draft" /> Draft 
			<input type="radio" name="status" value="Published" checked /> Publish';
		}

		else
		{
			echo '<input type="radio" name="status" value="Draft" checked /> Draft 
			<input type="radio" name="status" value="Published" /> Publish';
		}

		echo '<br /><br />

		<b>Parent Page:</b><br />
		<select name="parent">';
			$old = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_id`='$old_parent' AND `post_type`='page' ORDER BY `post_id`");
			if( $old_parent == 0 )
			{
				echo '<option value="0">None</option>';
			}

			else
			{
				echo '<option value="'.$old_parent.'">'.$old['post_title'].'</option>';
			}

			$new = $database->query("SELECT * FROM `tcg_post` WHERE `post_parent`='0' AND `post_type`='page' ORDER BY `post_id`");
			$num = mysqli_num_rows($new);
			if( $num == 0 )
			{
				echo '<option value="0">None</option>';
			}

			else
			{
				echo '<option value="0">None</option>';
				while( $row = mysqli_fetch_assoc( $new ) )
				{
					echo '<option value="'.$row['post_id'].'">'.$row['post_title'].'</option>';
				}
			}
			echo '</select>

		<div align="right" style="margin-top:20px;">
			<input type="submit" name="update" class="btn-success" value="Update Page" /> 
			<input type="reset" name="reset" class="btn-cancel" value="Reset" />
		</div>
	</td>
</tr>
</table>
</form>
</center>';
?>