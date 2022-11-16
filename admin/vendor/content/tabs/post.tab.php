<?php
/**************************************************
 * Tab:				Post Main
 * Description:		Show main tab of blog post list
 */


// Mass delete blog posts
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_post` WHERE `post_id`='$id' AND `post_type`='post'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the selected blog posts were not deleted from the database. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The selected blog posts has been successfully deleted from the database.";
	}
}

// Mass draft blog posts
if( isset( $_POST['mass-draft'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$draft = $database->query("UPDATE `tcg_post` SET `post_status`='Draft' WHERE `post_id`='$id' AND `post_type`='post'");
	}

	if( !$draft )
	{
		$error[] = "Sorry, there was an error and the selected blog posts were not drafted. ".mysqli_error($draft)."";
	}

	else
	{
		$success[] = "The selected blog posts has been successfully drafted from the database.";
	}
}

// Mass archive blog posts
if( isset( $_POST['mass-archive'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$archive = $database->query("UPDATE `tcg_post` SET `post_status`='Archived' WHERE `post_id`='$id' AND `post_type`='post'");
	}

	if( !$archive )
	{
		$error[] = "Sorry, there was an error and the selected blog posts were not archived. ".mysqli_error($archive)."";
	}

	else
	{
		$success[] = "The selected blog posts has been successfully archived from the database.";
	}
}

// Show list of blog posts
echo '<h2>Blog Posts</h2>
<p>&raquo; Do you want to <a href="'.$tcgurl.'admin/content.php?mod=posts&action=add">add an update</a>?</p>

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

<form method="post" action="'.$tcgurl.'admin/content.php">
<table width="100%" id="admin-postsmain" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="42%">Title</th>
	<th scope="col" align="center" width="20%">Posted on</th>
	<th scope="col" align="center" width="10%">Status</th>
	<th scope="col" align="center" width="17%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_post` WHERE `post_type`='post' ORDER BY `post_id` DESC");
while( $row = mysqli_fetch_array( $sql ) )
{
	$date  = date("F d, Y", strtotime($row['post_date']));
	$id = $row['post_id'];
	$title = strip_tags(stripslashes($row['post_title']));
	if( mb_strlen($title) >= 30 )
	{
		$title = substr($title, 0, 30);
		$title = $title . "...";
	}
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['post_id'].'" /></td>
	<td align="center">'.$row['post_id'].'</td>
	<td>'.$title.'</td>
	<td align="center">'.$date.'</td>
	<td align="center">'.$row['post_status'].'</td>
	<td align="center">
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod=posts&action=edit&id='.$id.'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this post" /><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod=posts&action=delete&id='.$id.'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this post" /><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="5">With selected: 
		<input type="submit" name="mass-draft" class="btn btn-primary" value="Draft" data-toggle="tooltip" data-placement="bottom" title="Draft selected posts" />
		<input type="submit" name="mass-archive" class="btn btn-warning" value="Archive" data-toggle="tooltip" data-placement="bottom" title="Archive selected posts" />
		<input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" data-toggle="tooltip" data-placement="bottom" title="Delete selected posts" />
	</td>
</tr>
</tfoot>
</table>
</form>';
?>