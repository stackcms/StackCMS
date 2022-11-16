<?php
/*******************************************************
 * Tab:				Active Members
 * Description:		Show main tab of active members list
 */


echo '<form method="post" action="'.$tcgurl.'admin/people.php?mod=members">';
$l = $database->num_rows("SELECT * FROM `tcg_levels`");
for( $i = 1; $i <= $l; $i++ )
{
	$sql = $database->query("SELECT * FROM `user_list` WHERE `usr_level`='$i' AND `usr_status`='Active' ORDER BY `usr_id` ASC");
	$num = $database->num_rows("SELECT * FROM `user_list` WHERE `usr_level`='$i' AND `usr_status`='Active'");
	$lvl = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='$i'");

	if( $num == 0 ) {}
	else
	{
		echo '<h2>'.$lvl['lvl_name'].' (Level '.$i.")</h2>\n";
		echo '<table width="100%" id="admin-membersactive" class="table table-bordered table-hover">
		<thead class="thead-dark"><tr>
			<th scope="col" align="center" width="5%"></th>
			<th scope="col" align="center" width="5%">ID</th>
			<th scope="col" align="center" width="15%">Name</th>
			<th scope="col" align="center" width="15%">Registered</th>
			<th scope="col" align="center" width="10%">Referral</th>
			<th scope="col" align="center" width="18%">Information</th>
			<th scope="col" align="center" width="17%">Action</th>
		</tr></thead>
		<tbody>';

		while( $row = mysqli_fetch_assoc( $sql ) )
		{
			echo '<tr>
			<td align="center"><input type="checkbox" name="id[]" value="'.$row['usr_id'].'" /></td>
			<td align="center">'.$row['usr_id'].'</td>
			<td align="center">'.$row['usr_name'].'</td>
			<td align="center">'.date("F d, Y", strtotime($row['usr_reg'])).'</td>
			<td align="center">'.$row['usr_refer'].'</td>
			<td align="center">
				<button type="button" onClick="window.location.href=\''.$row['usr_url'].'\';" target="_blank" title="Visit Trade Post" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"><i class="bi-house" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&page=logs&name='.$row['usr_name'].'\';" title="View Activity Logs" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"><i class="bi-file-earmark-arrow-down" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&page=trades&name='.$row['usr_name'].'\';" title="View Trade Logs" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"><i class="bi-file-earmark-arrow-up" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&page=decks&name='.$row['usr_name'].'\';" title="View Member Deck" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"><i class="bi-file-earmark-image" role="image"></i></button>
			</td>
			<td align="center">
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=email&id='.$row['usr_id'].'\';" title="Send Email" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"><i class="bi-envelope" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=rewards&id='.$row['usr_id'].'\';" title="Send Rewards" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"><i class="bi-gift" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=edit&id='.$row['usr_id'].'\';" title="Edit Member" class="btn btn-success" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=delete&id='.$row['usr_id'].'\';" title="Delete Member" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
			</td>
			</tr>';
		}

		echo '</tbody>

		<tfoot>
		<tr>
			<td align="center"><span class="arrow-right">↳</span></td>
			<td colspan="6">
				With selected: 
				<input type="submit" name="mass-hiatus" class="btn btn-primary" value="Hiatus" data-toggle="tooltip" data-placement="bottom" title="Hiatus selected members" /> 
				<input type="submit" name="mass-inactive" class="btn btn-warning" value="Inactive" data-toggle="tooltip" data-placement="bottom" title="Inactivate selected members" />
				<input type="submit" name="mass-retired" class="btn btn-warning" value="Retired" data-toggle="tooltip" data-placement="bottom" title="Retire selected members" /> 
				<input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" data-toggle="tooltip" data-placement="bottom" title="Delete selected members" />
			</td>
		</tr>
		</tfoot>
		</table>';
	}
}
echo '</form>';
?>