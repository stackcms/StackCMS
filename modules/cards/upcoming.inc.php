<?php
/*************************************************
 * Module:			Upcoming Decks
 * Description:		Shows list of upcoming decks
 */


if( empty( $deck ) )
{
	echo '<h1>Cards : Upcoming</h1>
	<p>Below you will find all of the upcoming card decks here at '.$tcgname.' which are either complete (made but haven\'t been released) or incomplete (work in progress). Any decks that have been listed here are no longer <em>subject for claiming or donation</em>.</p>';

	// SHOW SEARCH FORM
	$general->cardSearch('cards','card','Upcoming');

	echo '<table width="100%" cellspacing="0" border="0">
	<tr>
		<td width="50%" valign="top">
			<h3>Recently Made</h3>
			<small>Do not take from these as they aren\'t released yet!</small>

			<center>';
			$r = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Upcoming' ORDER BY `card_id` DESC LIMIT 12");
			while( $rec = mysqli_fetch_assoc( $r ) )
			{
				$digits = rand(01,$rec['card_count']);
				if( $digits < 10 )
				{
					$digit = '0'.$digits;
				}
				else
				{
					$digit = $digits;
				}
				$card = $rec['card_filename'].''.$digit;
				echo '<a href="'.$tcgurl.'cards.php?view='.$view.'&deck='.$rec['card_filename'].'"><img src="'.$tcgcards.''.$card.'.'.$tcgext.'"></a>';
			}
			echo '</center>
		</td>

		<td width="1%">&nbsp;</td>

		<td width="49%" valign="top">
			<h3>Upcoming Week\'s Releases</h3>
			<table width="100%" cellspacing="3" class="border">
			<tr>
				<td width="80%" class="headLineSmall">Deck</td>
				<td width="20%" class="headLineSmall">Votes</td>
			</tr>';
			$vs = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Upcoming' ORDER BY `card_votes` DESC LIMIT 4");
			$vc = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_status`='Upcoming' ORDER BY `card_votes` DESC LIMIT 4");
			if( $vc['card_votes'] == 0 )
			{
				echo '<tr>
				<td colspan="2" class="tableBodySmall" align="center"><i>There are no voted decks at the moment.</i></td>
				</tr>';
			}

			else {
				while( $v = mysqli_fetch_assoc( $vs ) )
				{
					echo '<tr>
					<td class="tableBodySmall"><a href="'.$tcgurl.'cards.php?view='.$view.'&deck='.$v['card_filename'].'">'.$v['card_deckname'].'</a></td>
					<td class="tableBodySmall" align="center">'.$v['card_votes'].'</td>
					</tr>';
				}
			}
			echo '</table><br />

			<h3>Top 5 Donators</h3>
			<small>The data below shows only the decks that are already made.</small>
			<table width="100%" cellspacing="3" class="border">
			<tr>
				<td width="80%" class="headLineSmall">Member</td>
				<td width="20%" class="headLineSmall">Decks</td>
			</tr>';
			$ds = $database->query("SELECT card_donator, COUNT(*) AS `card_count` FROM `tcg_cards` GROUP BY `card_donator` ORDER BY `card_count` DESC LIMIT 5");
			while( $d = mysqli_fetch_assoc( $ds ) )
			{
				echo '<tr>
				<td class="tableBodySmall"><a href="'.$tcgurl.'members.php?id='.$d['card_donator'].'">'.$d['card_donator'].'</a></td>
				<td class="tableBodySmall" align="center">'.$d['card_count'].'</td>
				</tr>';
			}
			echo '</table>
		</td>
	</tr>
	</table>';

	$c = $database->num_rows("SELECT * FROM `tcg_cards_set`");
	for( $i=1; $i<=$c; $i++ )
	{
		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='$i'");
		$select = $database->query("SELECT * FROM `tcg_cards` WHERE `card_set`='".$cat['set_id']."' AND `card_status`='Upcoming' ORDER BY `card_set` ASC, `card_filename` ASC");
		$counts = mysqli_num_rows($select);
		if( $counts == 0 ) {}
		else
		{
			echo '<br /><h3>'.$cat['set_name'].'</h3>
			<table width="100%" class="table table-bordered table-striped"><thead>
			<tr>
				<td width="30%" align="center"><b>Color</b></td>
				<td width="60%" align="center"><b>Deckname</b></td>
				<td width="10%" align="center"><b>#/$</b></td>
			</tr></thead>
			<tbody>';
			while( $row = mysqli_fetch_assoc( $select ) )
			{
				echo '<tr>
				<td align="center"><font color="'.$row['card_color'].'">'.$row['card_color'].'</font></td>
				<td align="center"><a href="'.$tcgurl.'cards.php?view='.$view.'&deck='.$row['card_filename'].'">'.$row['card_deckname'].'</a> ('.$row['card_filename'].')</td>
				<td align="center">';
				if( $row['card_filename'] == "member" )
				{
					$memnum = $database->num_rows("SELECT * FROM `user_list` WHERE `usr_mcard`='Yes'");
					echo $memnum.'/0';
				}

				else
				{
					echo $row['card_count'].'/'.$row['card_worth'];
				}
				echo '</td></tr>';
			}
			echo '</tbody></table>';
		}
	}
}

else
{
	$row = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_filename`='$deck' AND `card_status`='Upcoming'");
	$cardSET = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['card_set']."'");

	echo '<h1><font color="'.$row['card_color'].'"><span class="fas fa-tint" aria-hidden="true"></span></font> '.$row['card_deckname'].'</h1>

	<table width="100%" cellspacing="0" border="0">
	<tr>
		<td width="70%" valign="top">
			<p>'.$row['card_desc'].'</p>

			<div align="center">';
				// Get total deck width
				$width = $settings->getValue('cards_size_width') * $row['card_break'];
				echo '<div style="width: '.$width.'px;">';
				if( $set == "member" )
				{
					$sql = $database->query("SELECT * FROM `user_list` WHERE `usr_mcard`='Yes' ORDER BY `usr_name`");
					while( $row2 = mysqli_fetch_assoc( $query2 ) )
					{
						echo '<img src="'.$tcgcards.'mc-'.$row2['usr_name'].''.$tcgext.'" />';
					}
				}

				else {
					for( $x=1; $x<=$row['card_count']; $x++ )
					{
						if ( $x < 10 )
						{
							echo '<img src="'.$tcgcards.''.$row['card_filename'].'0'.$x.'.'.$tcgext.'" />';
						}

						else
						{
							echo '<img src="'.$tcgcards.''.$row['card_filename'].''.$x.'.'.$tcgext.'" />';
						}
					}
				}
				echo '</div>
			</div>
		</td>

		<td width="2%"></td>

		<td width="28%" valign="top">
			<h3>Deck Information</h3>
			<div class="box-warning">
				<b>This is an upcoming deck!</b><br />Please do not take the cards below until it is released.
			</div><br />

			<table width="100%" cellspacing="0" border="0" class="table table-bordered table-striped">
				<tbody>
					<tr>
						<td colspan="2" valign="middle" align="center">';
						$name = $row['card_filename'].'-master';
						$file = glob("images/cards/" . $name . "*");
						if( count( $file ) > 0 )
						{
							echo '<img src="'.$tcgcards.''.$row['card_filename'].'-master.'.$tcgext.'" /><br />';
						}

						else
						{
							echo '<img src="'.$tcgcards.''.$row['card_filename'].'.'.$tcgext.'" /><br />';
						}
						@include($tcgpath.'admin/wish.php');
						echo '</td>
					</tr>
					<tr>
						<td colspan="2" valign="middle"><b>Deck/File Name:</b> '.$row['card_deckname'].' (<i>'.$row['card_filename'].'</i>)</td>
					</tr>
					<tr>
						<td colspan="2" valign="middle"><b>Set/Series:</b> '.$cardSET['set_name'].'</td>
					</tr>
					<tr>
						<td width="40%" valign="middle"><b>Made/Donated:</b> '.$row['card_maker'].' / '.$row['card_donator'].'</td>
						<td width="40%" valign="middle"><b>Color:</b> <font color="'.$row['card_color'].'">'.$row['card_color'].'</font></td>
					</tr>
					<tr>
						<td valign="middle"><b>Released:</b> '.$row['card_released'].'</td>
						<td valign="middle"><b>Masterable:</b> '.$row['card_mast'].'</td>
					</tr>
					<tr>
						<td colspan="2" valign="middle"><b>Wished by:</b> ';
						$w = $database->query("SELECT * FROM `user_wishlist` WHERE `wlist_deck`='$deck'");
						$c = mysqli_num_rows($w);
						if( $c != 0 )
						{
							$names = array();
							while( $rw = mysqli_fetch_array( $w ) )
							{
								$names[] = '<a href="'.$tcgurl.'members.php?id='.$rw['wlist_name'].'">'.$rw['wlist_name'].'</a>';
							}
							echo implode(', ', $names);
						}
						else
						{
							echo "None";
						}
						echo '</td>
					</tr>
					<tr><td colspan="2" valign="middle"><b>Mastered by:</b> '.$row['card_masters'].'</td></tr>
				</tbody>
			</table>

			<h3>Other Decks</h3>
			<select onchange="location = this.value;" id="theinput" name="mastered" class="selectpicker dropdown" data-live-search="true" data-size="5" data-width="100%" style="width:100%">
				<option data-divider="true" style="height:10px;">-------------------</option>
				<option id="theinput" value="'.$tcgurl.'cards.php?view='.$view.'&deck='.$row['card_filename'].'" onClick="window.location = \''.$tcgurl.'cards.php?view='.$view.'&deck='.$row['card_filename'].'\';" disabled>'.$row['card_filename'].'</option>';
				$odecks = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Upcoming' ORDER BY `card_filename`");
				while( $decks = mysqli_fetch_assoc( $odecks ) )
				{
					echo '<option id="theinput" value="'.$tcgurl.'cards.php?view='.$view.'&deck='.$decks['card_filename'].'" onClick="window.location = \''.$tcgurl.'cards.php?view='.$view.'&deck='.$decks['card_filename'].'\';">'.$decks['card_filename'].'</option>';
				}
			echo '</select>
		</td>
	</tr>
	</table>';
}
?>