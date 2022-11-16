<?php
/*******************************************************
 * Action:			Shop Category Main
 * Description:		Show main page of shop category list
 */


// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `shop_category` WHERE `shop_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the shop categories were not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The shop categories were successfully deleted from the database.";
	}
}


// Show shop category list and form
echo '<h1>Shop Category</h1>
<p>If you haven\'t added any category to your shop\'s catalog/store yet, <a href="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action=add">use this form</a> to create a new one.</p>

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
echo '</center>';

$category = $database->query("SELECT * FROM `shop_category`");
$num = mysqli_num_rows( $category );

if( $num == 0 )
{
	echo '<div class="alert alert-warning" role="alert"><center><i>There are currently no shop category! Create some first!</i></center></div>';
}

else
{
	echo '<div class="box">
	<form method="post" action="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'">
	<table class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="15%">Catalog</th>
		<th scope="col" align="center" width="40%">Category</th>
		<th scope="col" align="center" width="20%">Slug</th>
		<th scope="col" align="center" width="15%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $category ) )
	{
		$cat = $database->get_assoc("SELECT * FROM  `shop_catalog` WHERE `shop_id`='".$row['shop_catalog']."'");
		echo '<tr>
		<td align="center"><input type="checkbox" name="id[]" value="'.$row['shop_id'].'" /></td>
		<td align="center">'.$row['shop_id'].'</td>
		<td align="center">'.$cat['shop_catalog'].'</td>
		<td align="center">'.$row['shop_category'].'</td>
		<td align="center">'.$row['shop_slug'].'</td>
		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action=edit&id='.$row['shop_id'].'\';" title="Edit this category" class="btn btn-success" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button>
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action=delete&id='.$row['shop_id'].'\';" title="Delete thhis category" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="5">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected categories" data-toggle="tooltip" data-placement="bottom" /></td>
	<tr></tbody>
	</table>
	</form>
	</div>';
}
?>