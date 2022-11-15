<?php
/*
 * Class library for administrative functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Admin
 * Description:		Functions to use for admin contents
 */
class Admin
{
	function members( $stat )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$settings = new Settings;
		$stat = $sanitize->for_db( $stat );
		$tcgurl = $settings->getValue( 'tcg_url' );

		$result = $database->num_rows("SELECT * FROM `user_list` WHERE `usr_status`='$stat' ORDER BY `usr_id` ASC");
		$sql = $database->query("SELECT * FROM `user_list` WHERE `usr_status`='$stat' ORDER BY `usr_id` ASC");

		if( $result === 0 )
		{
			echo '<center>There are currently no members at this status.</center>';
		}

		else
		{
			echo '<form method="post" action="'.$tcgurl.'admin/people.php?mod=members">
			<table width="100%" class="table table-bordered table-hover">
			<thead class="thead-dark">
			<tr>
				<th scope="col" align="center" width="5%"></th>
				<th scope="col" align="center" width="5%">ID</th>
				<th scope="col" align="center" width="20%">Name</th>
				<th scope="col" align="center" width="25%">URL</th>
				<th scope="col" align="center" width="25%">Email</th>
				<th scope="col" align="center" width="20%">Action</th>
			</tr>
			</thead>
			<tbody>';

			while( $row = mysqli_fetch_assoc( $sql ) )
			{
				echo '<tr>
				<td align="center"><input type="checkbox" name="id[]" value="'.$row['usr_id'].'" /></td>
				<td align="center">'.$row['usr_id'].'</td>
				<td align="center">'.$row['usr_name'].'</td>
				<td align="center"><a href="'.$row['usr_url'].'" target="_blank">http://</a></td>
				<td align="center"><a href="'.$tcgurl.'admin/people.php?mod=members&action=email&id='.$row['usr_id'].'">Email?</a></td>
				<td align="center">
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=edit&id='.$row['usr_id'].'\';" class="btn btn-success" title="Edit this member" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button> ';
					if( $stat == 'Pending' )
					{
						echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=approve&id='.$row['usr_id'].'\';" class="btn btn-primary" title="Approve this member" data-toggle="tooltip" data-placement="bottom"><i class="bi-check2" role="image"></i></button> ';
					}

					else if( $stat == 'Hiatus' || $stat == 'Inactive' )
					{
						echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=reactivate&id='.$row['usr_id'].'\';" class="btn btn-primary" title="Reactivate this member" data-toggle="tooltip" data-placement="bottom"><i class="bi-check2" role="image"></i></button> ';
					}
					echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=delete&id='.$row['usr_id'].'\';" class="btn btn-delete" title="Delete this member" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button> 
				</td>
				</tr>';
			}

			echo '<tr>
				<td align="center"><span class="arrow-right">↳</span></td>
				<td colspan="5">With selected: ';
				if( $stat == 'Pending' )
				{
					echo '<input type="submit" name="mass-approve" class="btn btn-success" value="Approve" title="Approve select members" data-toggle="tooltip" data-placement="bottom" />';
				}

				else if( $stat == 'Hiatus' )
				{
					echo '<input type="submit" name="mass-reactivate" class="btn btn-success" value="Reactivate" title="Reactivate selected members" data-toggle="tooltip" data-placement="bottom" /> <input type="submit" name="mass-inactivate" class="btn btn-warning" value="Inactivate" title="Inactivate selected members" data-toggle="tooltip" data-placement="bottom" />';
				}

				else if( $stat == 'Inactive' )
				{
					echo '<input type="submit" name="mass-reactivate" class="btn btn-success" value="Reactivate" title="Reactivate selected members" data-toggle="tooltip" data-placement="bottom" /> <input type="submit" name="mass-hiatus" class="btn btn-warning" value="Hiatus" title="Hiatus selected members" data-toggle="tooltip" data-placement="bottom" />';
				}
				echo '<input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected members" data-toggle="tooltip" data-placement="bottom" />
			</tr>
			</tbody>
			</table>
			</form>';
		}
	}


	function affiliates( $stat )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$settings = new Settings;
		$stat = $sanitize->for_db($stat);
		$tcgurl = $settings->getValue( 'tcg_url' );
		$tcgimg = $settings->getValue( 'file_path_img' );

		$result = $database->num_rows("SELECT * FROM `tcg_affiliates` WHERE `aff_status`='$stat' ORDER BY `aff_id` ASC");
		$sql = $database->query("SELECT * FROM `tcg_affiliates` WHERE `aff_status`='$stat' ORDER BY `aff_id` ASC");

		if( $result === 0 )
		{
			echo '<center>There are currently no affiliates under this status.</center>';
		}

		else
		{
			echo '<form method="post" action="'.$tcgurl.'admin/people.php?mod=affiliates">
			<table class="table table-bordered table-hover">
			<thead class="thead-dark">
			<tr>
				<th scope="col" align="center" width="5%"></th>
				<th scope="col" align="center" width="5%">ID</th>
				<th scope="col" align="center" width="45%">Owner</th>
				<th scope="col" align="center" width="15%">Affiliate</th>
				<th scope="col" align="center" width="20%">Action</th>
			</tr>
			</thead>
			<tbody>';

			while( $row = mysqli_fetch_assoc( $sql ) )
			{
				echo '<tr>
				<td align="center"><input type="checkbox" name="id[]" value="'.$row['aff_id'].'" /></td>
				<td align="center">'.$row['aff_id'].'</td>
				<td align="center"><b>'.$row['aff_owner'].'</b> of '.$row['aff_subject'].' TCG</td>
				<td align="center"><a href="'.$row['aff_url'].'" target="_blank"><img src="'.$tcgimg.'aff/'.$row['aff_button'].'" title="'.$row['aff_subject'].' TCG" alt="'.$row['aff_subject'].' TCG"></a></td>
				<td align="center">
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/affiliates.php?mod=email&id='.$row['aff_id'].'\';" class="btn btn-success" title="Email this affiliate" data-toggle="tooltip" data-placement="bottom" /><i class="bi-envelope" role="image"></i></button> ';
					if( $stat == 'Pending' )
					{
						echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/affiliates.php?mod=approve&id='.$row['aff_id'].'\';" class="btn btn-primary" title="Approve this affiliate" data-toggle="tooltip" data-placement="bottom"><i class="bi-check2" role="image"></i></button> ';
					}

					else if( $stat == 'Hiatus' || $stat == 'Inactive' )
					{
						echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/affiliates.php?mod=reactivate&id='.$row['aff_id'].'\';" class="btn btn-primary" title="Reactivate this affiliate" data-toggle="tooltip" data-placement="bottom"><i class="bi-check2" role="image"></i></button> ';
					}
					echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/affiliates.php?mod=edit&id='.$row['aff_id'].'\';" class="btn btn-success" title="Edit this affiliate" data-toggle="tooltip" data-placement="bottom" /><i class="bi-gear" role="image"></i></button> 
					<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/affiliates.php?mod=delete&id='.$row['aff_id'].'\';" class="btn btn-danger" title="Delete this affiliate" data-toggle="tooltip" data-placement="bottom" /><i class="bi-trash3" role="image"></i></button>
				</td>
				</tr>';
			}

			echo '<tr>
				<td align="center"><span class="arrow-right">↳</span></td>
				<td colspan="4">With selected: ';
				if( $stat == 'Pending' )
				{
					echo '<input type="submit" name="mass-approve" class="btn btn-success" value="Approve" title="Approve selected affiliates" data-toggle="tooltip" data-placement="bottom" /> ';
				}

				else if( $stat == 'Hiatus' || $stat == 'Inactive' )
				{
					echo '<input type="submit" name="mass-reactivate" class="btn btn-success" value="Reactivate" title="Reactivate selected affiliates" data-toggle="tooltip" data-placement="bottom" /> ';
				}

				else if( $stat == 'Active' )
				{
					echo '<input type="submit" name="mass-hiatus" class="btn btn-default" value="Hiatus" title="Hiatus selected affiliates" data-toggle="tooltip" data-placement="bottom" /> 
					<input type="submit" name="mass-inactive" class="btn btn-warning" value="Inactive" title="Inactivate selected affiliates" data-toggle="tooltip" data-placement="bottom" /> ';
				}
				echo '<input type="submit" name="mass-closed" class="btn btn-warning" value="Closed" title="Close selected affiliates" data-toggle="tooltip" data-placement="bottom" /> 
				<input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected affiliates" data-toggle="tooltip" data-placement="bottom" />
			</tr>
			</tbody>
			</table>
			</form>';
		}
	}


	function shopItems()
	{
		$database = new Database;
		$settings = new Settings;
		$tcgurl = $settings->getValue( 'tcg_url' );

		$c1 = $database->num_rows("SELECT * FROM `shop_catalog`");
		for( $i = 1; $i <= $c1; $i++ )
		{
			$cat1 = $database->get_assoc("SELECT * FROM `shop_catalog` WHERE `shop_id`='$i'");
			echo '<h1>'.$cat1['shop_catalog'].'</h1>';

			$c2 = $database->num_rows("SELECT * FROM `shop_category` WHERE `shop_catalog`='$i'");
			$sql = $database->query("SELECT * FROM `shop_items` WHERE `shop_catalog`='$i' ORDER BY `shop_file`");
			$cat2 = $database->get_assoc("SELECT * FROM `shop_category` WHERE `shop_catalog`='$i'");

			if( mysqli_num_rows( $sql ) === 0 )
			{
				echo '<center><i>You don\'t have any items in your inventory. <a href="'.$tcgurl.'admin/shoppe.php?action=add-item">Want to add one</a>?</i></center>';
			}

			else
			{
				echo '<h2>'.$cat2['shop_category'].'</h2>';
				echo '<table class="table table-bordered table-hover">
				<thead class="thead-dark">
				<tr>
					<th scope="col" align="center" width="20%">Item Name</th>
					<th scope="col" align="center" width="25%">Item SKU</th>
					<th scope="col" align="center" width="15%">Price</th>
					<th scope="col" align="center" width="10%">Quantity</th>
					<th scope="col" align="center" width="20%">Action</th>
				</tr>
				</thead>
				<tbody>';

				while( $row = mysqli_fetch_assoc( $sql ) )
				{
					echo '<tr>
					<td align="center">'.$row['shop_item'].'</td>
					<td align="center">'.substr_replace($row['shop_file'],"",-4).'</td>
					<td align="center">'.$row['shop_currency'].'</td>
					<td align="center">'.$row['shop_quantity'].'</td>
					<td align="center">
						<button onClick="window.location.href=\''.$tcgurl.'admin/shoppe.php?mod=items&action=edit&id='.$row['shop_id'].'\';" title="Edit Item" class="btn btn-success" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button> 
						<button onClick="window.location.href=\''.$tcgurl.'admin/shoppe.php?mod=items&action=delete&id='.$row['shop_id'].'\';" title="Delete Item" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
					</td>
					</tr>';
				}

				echo '</tbody></table>';
			}
		}
	}
}
?>