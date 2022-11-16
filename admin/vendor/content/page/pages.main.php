<?php
/*********************************************************
 * Page:				Page Main
 * Description:			Show main tab of page content list
 */


// Mass delete page content
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_post` WHERE `post_id`='$id' AND `post_type`='page'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the pages were not deleted from the database. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The pages were deleted successfully from the database.";
	}
}

// Mass draft page content
if( isset( $_POST['mass-draft'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$draft = $database->query("UPDATE `tcg_post` SET `post_status`='Draft' WHERE `post_id`='$id' AND `post_type`='page'");
	}

	if( !$draft )
	{
		$error[] = "Sorry, there was an error and the selected page contents were not drafted. ".mysqli_error($draft)."";
	}

	else
	{
		$success[] = "The selected page contents has been successfully drafted from the database.";
	}
}

// Mass archive page content
if( isset( $_POST['mass-archive'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$archive = $database->query("UPDATE `tcg_post` SET `post_status`='Archived' WHERE `post_id`='$id' AND `post_type`='page'");
	}

	if( !$archive )
	{
		$error[] = "Sorry, there was an error and the selected page contents were not archived. ".mysqli_error($archive)."";
	}

	else
	{
		$success[] = "The selected page contents has been successfully archived from the database.";
	}
}

echo '<h1>Page Content</h1>
<p>&raquo; Do you want to <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&action=add">add a page</a>?</p>

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

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'">
<div class="box">
<table width="100%" id="admin-pagesmain" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="43%">Title</th>
	<th scope="col" align="center" width="20%">Added on</th>
	<th scope="col" align="center" width="10%">Status</th>
	<th scope="col" align="center" width="17%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_post` WHERE `post_type`='page' ORDER BY `post_id` DESC");
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
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=edit&id='.$id.'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this page"><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=delete&id='.$id.'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this page"><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="5">With selected: 
		<input type="submit" name="mass-draft" class="btn btn-primary" value="Draft" data-toggle="tooltip" data-placement="bottom" title="Draft selected pages" />
		<input type="submit" name="mass-archive" class="btn btn-warning" value="Archive" data-toggle="tooltip" data-placement="bottom" title="Archive selected pages" />
		<input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" data-toggle="tooltip" data-placement="bottom" title="Delete selected pages" />
	</td>
</tr>
</tfoot>
</table>
</div><!-- .box -->
</form>';
?>