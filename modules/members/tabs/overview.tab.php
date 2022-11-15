<?php
/**************************************************
 * Tab:				Member Overview
 * Description:		Display user's profile overview
 */


echo '<table width="100%" cellspacing="0" border="0" class="table table-sliced table-striped">
	<tbody>
		<tr>
			<td width="50%"><b>Status:</b> '.$row['usr_status'].'</td>
			<td width="50%"><b>Rank:</b> '.$lvl['lvl_name'].' (<i>Level '.$row['usr_level'].'</i>)</td>
		</tr>
		<tr>
			<td><b>Collecting:</b> <a href="'.$tcgurl.'cards.php?view=released&deck='.$row['usr_deck'].'">'.$row['usr_deck'].'</a></td>
			<td><b>Card Count:</b> '.$item['itm_cards'].'</td>
		</tr>
		<tr>
			<td><b>Joined:</b> '.date("F d, Y", strtotime($row['usr_reg'])).'</td>
			<td colspan="2"><b>Last seen:</b> '.date("F d, Y", strtotime($row['usr_sess'])).'</i> at <i>'.date("h:i A", strtotime($row['usr_sess'])).'</td>
		</tr>
	</tbody>
</table>

<p>'.$row['usr_bio'].'</p>';
?>