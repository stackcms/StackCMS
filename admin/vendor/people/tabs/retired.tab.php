<?php
/********************************************************
 * Tab:				Retired Members
 * Description:		Show main tab of retired members list
 */


echo '<h2>Retired</h2>';
$result = $database->num_rows("SELECT * FROM `user_list_quit` ORDER BY `usr_name`");
if( $result === 0 )
{
	echo '<center>There are currently no retired members at the moment.</center>';
}

else
{
	$get = $database->query("SELECT * FROM `user_list_quit` ORDER BY `usr_name`");
	echo '<table width="100%" id="admin-membersretired" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="20%">Member Card</th>
		<th scope="col" align="center" width="30%">Name</th>
		<th scope="col" align="center" width="25%">Joined</th>
		<th scope="col" align="center" width="25%">Retired</th>
	</tr></thead>
	<tbody>';

	while( $quit = mysqli_fetch_assoc( $get ) )
	{
		echo '<tr>
		<td align="center"><img src="'.$settings->getValue('file_path_cards').''.$quit['usr_mcard'].'.'.$settings->getValue('cards_file_type').'" /></td>
		<td align="center">'.$quit['usr_name'].'</td>
		<td align="center">'.$quit['usr_joined'].'</td>
		<td align="center">'.$quit['usr_quit'].'</td>
		</tr>';
	}

	echo '</tbody></table>';
}
?>