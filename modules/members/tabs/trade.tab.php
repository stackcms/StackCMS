<?php
/***********************************************
 * Tab:				Trade Log
 * Description:		Display user's trade logs
 */


echo '<h3>Trade Logs</h3>
<div style="text-align: justify; padding-right: 20px; margin-top: 20px; line-height: 20px; font-size: 14px; overflow: auto; height: 300px;">';

$timestamp = '';
while( $row = mysqli_fetch_assoc( $log2 ) )
{
	if( $row['trd_date'] != $timestamp )
	{
		echo '<br /><b>'.date('F d, Y', strtotime($row['trd_date'])).' -----</b><br/>';
		$timestamp = $row['trd_date'];
	}
	echo '<li class="spacer">- <b>Traded '.$row['trd_trader'];
	echo ':</b> my '.$row['trd_out'].' for '.$row['trd_inc'].'</li>';
} // end trade logs
echo '</div><br />';

$file = $tcgpath.'modules/members/trade/'.$player.'.txt';
if( file_exists( $file ) )
{
	echo '<div align="right"><button onClick="window.location.href=\''.$tcgurl.'modules/members/trade/'.$player.'.txt\';" class="btn-default" title="View Archived Trade Logs">View Archived Trade Logs</button></div>';
}
else {}
?>