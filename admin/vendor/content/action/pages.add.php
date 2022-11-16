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

echo '<h1>Add a Page</h1>
<p>Use the form below to create a new page content for your TCG.<br />
Use the <a href="'.$tcgurl.'admin/content.php?mod=pages">edit</a> form to update the information for existing pages.</p>

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
		echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div>';
	}
}
echo '</center>

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'">
<div class="row">
	<div class="col">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Page Title & Slug</b></span>
				</div>
				<input type="text" name="title" class="form-control" placeholder="Card Decks">
				<input type="text" name="slug" class="form-control" placeholder="card-decks">
			</div>

			<b>Content:</b><br />';
			@include($tcgpath.'admin/theme/text-editor.php');
			echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small>
			<textarea name="entry" id="entry" class="form-control" rows="10" /></textarea>
		</div><!-- .box -->
	</div><!-- .col -->

	<div class="col-4">
		<div class="box">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Publish</b></span>
				</div>
				<input type="date" name="date" class="form-control">
			</div>

			<b>Status:</b><br />
			<input type="radio" name="status" value="Draft" /> Draft &nbsp;&nbsp; 
			<input type="radio" name="status" value="Published" /> Publish<br /><br />

			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Parent page</b></span>
				</div>
				<select name="parent" class="form-control">';
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
			</div>

			<div align="right" style="margin-top:20px;">
				<input type="submit" name="add" class="btn btn-success" value="Add page" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div>
		</div><!-- .box -->
	</div><!-- .col-4 -->
</div><!-- .row -->
</form>';
?>