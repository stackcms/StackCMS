<?php
/****************************************************
 * Page:			Categories
 * Description:		Show main page of categories list
 */


// Process mass delete form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_cards_cat` WHERE `cat_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the categories were not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The categories were deleted successfully!";
	}
}

// Show category list
echo '<h1>Card Categories</h1>
<p>Below is the list of current categories for your card decks. Feel free to edit or delete the items that suits your own TCG setup.<br />
If you want to add a new deck category, <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=add">use this form</a>.</p>

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

<div class="box">
<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">
<table width="100%" id="admin-cardscat" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="75%">Category Name</th>
	<th scope="col" align="center" width="15%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_id` ASC");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['cat_id'].'" /></td>
	<td align="center">'.$row['cat_id'].'</td>
	<td>'.$row['cat_name'].'</td>
	<td align="center">
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['cat_id'].'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this category" /><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['cat_id'].'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this category" /><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="3">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete"  data-toggle="tooltip" data-placement="bottom" title="Delete selected categories" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';
?>