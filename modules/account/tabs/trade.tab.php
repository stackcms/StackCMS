<?php
/**************************************************
 * Tab:				User Trade
 * Description:		Display user's trade activities
 */


$trade = isset($_GET['td']) ? $_GET['td'] : null;
$trades = $database->query("SELECT * FROM `user_trades` WHERE `trd_name`='$player' GROUP BY YEAR(trd_date), MONTH(trd_date) ORDER BY `trd_date` DESC");
if( empty( $trade ) )
{
	echo '<h2>Trade Logs</h2>
	<p>These trade logs shows your permanent trading activities with your fellow players, these also counts your current turned in trade points. So if you have a trade log from your trade post that hasn\'t been turned in yet, kindly do so to have it recorded here.</p>

	<div align="center" class="logLink">';
	while( $row = mysqli_fetch_assoc( $trades ) )
	{
		echo '<a href="'.$tcgurl.'account.php?td='.date("Y-m", strtotime($row['trd_date'])).'">'.date("F Y", strtotime($row['trd_date'])).'</a>';
	} // end trade while
	echo '</div><br />';

	$file = $tcgpath.'modules/members/trade/'.$player.'.txt';
	if( file_exists( $file ) )
	{
		echo '<div align="right"><button onClick="window.location.href=\''.$tcgurl.'modules/members/trade/'.$player.'.txt\';" class="btn-default" title="View Archived Trade Logs">View Archived Trade Logs</button></div>';
	}
	else {}
}

else
{
	$show2 = $database->query("SELECT * FROM `user_trades` WHERE `trd_name`='$player' AND `trd_date` LIKE '$trade-%' ORDER BY `trd_date` DESC");
	echo '<h2>'.date("F Y", strtotime($trade)).'</h2>';
	$timestamp = '';
	while( $row2 = mysqli_fetch_assoc( $show2 ) )
	{
		if ( $row2['trd_date'] != $timestamp )
		{
			echo '<br /><b>'.date('F d, Y', strtotime($row2['trd_date'])).' -----</b><br/>';
			$timestamp = $row2['trd_date'];
		}
		echo '<li class="spacer">- <i><b>Traded '.$row2['trd_trader'].':</b></i> my '.$row2['trd_out'].' for '.$row2['trd_inc'].'</li>';
	} // end while
}
?>