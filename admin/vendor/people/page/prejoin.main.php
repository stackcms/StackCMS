<?php
/********************************************************************************
 * Page:			Prejoin Rewards Main
 * Description:		Show main page of potential members for their prejoin rewards
 */


echo '<h1>Reward Your Potential Members</h1>
<p>This is the complete data of all potential members who have donated within the pre-prejoin phase. Make sure to send their rewards first before opening your TCG for prejoin.</p>
<p>With regards to rewards, each donation is counted as <b>1</b> card worth, be your decks are regular, special or rare. However, if your desired reward for each donations varies, you can use the <code>Edit Rewards</code> button to set the actual rewards a potential member would get when your TCG opens for prejoin.</p>';

// Check if there are pre-prejoin donations
$num = $database->num_rows("SELECT * FROM `prejoin_record`");
if( $num == 0 )
{
	echo '<div class="alert alert-warning" role="alert"><center>There are currently no pre-prejoin donations. Make sure to direct your potential members to the <a href="'.$tcgurl.'prejoin.php" target="_blank">prejoin section</a> of your TCG so that they can start donating.</center></div>';
}

else
{
	echo '<div class="box">
	<table id="admin-prejoinmain" class="table table-bordered table-hover">
	<thead class="thead-dark">
		<tr>
			<th scope="col" align="center" width="10%">Name</th>
			<th scope="col" align="center" width="20%">Donations</th>
			<th scope="col" align="center" width="10%">Card Worth</th>
			<th scope="col" align="center" width="10%">Currencies</th>
			<th scope="col" align="center" width="10%">Action</th>
		</tr>
	</thead>
	<tbody>';
	$query = $database->query("SELECT * FROM `prejoin_record` ORDER BY `usr_cards` DESC");
	while( $row = mysqli_fetch_assoc( $query ) )
	{
		echo '<tr>
			<td align="center">'.$row['usr_name'].'</td>
			<td align="center">'.$row['usr_cards'].' decks + '.$row['usr_collaterals'].' items</td>
			<td align="center">'.$row['usr_cardworth'].'</td>
			<td align="center">'.$row['usr_currency'].'</td>
			<td align="center">
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&action=rewards&id='.$row['usr_name'].'\';" title="Send Rewards" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"><i class="bi-gift" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&action=edit&id='.$row['usr_name'].'\';" title="Edit Rewards" class="btn btn-success" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button>
				<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&action=delete&id='.$row['usr_name'].'\';" title="Delete Rewards" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
			</td>
		</tr>';
	}
	echo '</tbody>
	</table>';
}
?>