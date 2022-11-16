<?php
/**********************************************************
 * Module:			Empty Shoppe Action
 * Description:		Show main page of shoppe inventory list
 */


$c1 = $database->num_rows("SELECT * FROM `shop_catalog`");
for( $i = 1; $i <= $c1; $i++ )
{
	$cat1 = $database->get_assoc("SELECT * FROM `shop_catalog` WHERE `shop_id`='$i'");
	echo '<h1>'.$cat1['shop_catalog'].'</h1>';

	$c2 = $database->num_rows("SELECT * FROM `shop_category` WHERE `shop_catalog`='$i'");
	$sql = $database->query("SELECT * FROM `shop_items` WHERE `shop_catalog`='$i' ORDER BY `shop_file`");
	$cat2 = $database->get_assoc("SELECT * FROM `shop_category` WHERE `shop_catalog`='$i'");

	if( mysqli_num_rows($sql) == 0 )
	{
		echo '<div class="alert alert-warning" role="alert"><center><i>You don\'t have any items in your inventory. <a href="'.$tcgurl.'admin/shoppe.php?mod=items&action=add">Want to add one</a>?</i></center></div>';
	}

	else
	{
		echo '<div class="box">
			<h2>'.$cat2['shop_category'].'</h2>
			<table id="admin-shoppeitems'.$i.'" class="table table-bordered table-hover">
			<thead class="thead-dark"><tr>
				<th scope="col" align="center" width="20%">Item Name</th>
				<th scope="col" align="center" width="25%">Item SKU</th>
				<th scope="col" align="center" width="15%">Price</th>
				<th scope="col" align="center" width="10%">Quantity</th>
				<th scope="col" align="center" width="20%">Action</th>
			</tr></thead>
			<tbody>';

			while( $row = mysqli_fetch_assoc( $sql ) )
			{
				echo '<tr>
				<td align="center">'.$row['shop_item'].'</td>
				<td align="center">'.substr_replace($row['shop_file'],"",-4).'</td>
				<td align="center">'.$row['shop_currency'].'</td>
				<td align="center">'.$row['shop_quantity'].'</td>
				<td align="center">
					<button onClick="window.location.href=\''.$tcgurl.'admin/shoppe.php?mod=items&action=edit&id='.$row['shop_id'].'\';" title="Edit this item" class="btn btn-success" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button> 
					<button onClick="window.location.href=\''.$tcgurl.'admin/shoppe.php?mod=items&action=delete&id='.$row['shop_id'].'\';" title="Delete this item" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
				</td>
				</tr>';
			}

			echo '</tbody></table>
		</div><!-- box -->';
	}
}
?>