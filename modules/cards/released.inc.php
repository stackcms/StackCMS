<?php
/*************************************************
 * Module:			Released Decks
 * Description:		Shows list of released decks
 */


if( empty( $deck ) )
{
	echo '<h1>Cards : Released</h1>';

	// SHOW SEARCH FORM
	$general->cardSearch('cards','card','Active');

	$c = $database->num_rows("SELECT * FROM `tcg_cards_cat`");
	for( $i=1; $i<=$c; $i++ )
	{
		$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_cat`='$i' AND `card_status`='Active' ORDER BY `card_cat` ASC, `card_filename` ASC");
		$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='$i'");
		$counts = mysqli_num_rows($sql);
		if( $counts == 0 ) {}
		else
		{
			echo '<h3>'.$cat['cat_name'].'</h3>
			<table width="100%" class="table table-sliced table-striped"><thead>
			<tr>
				<td width="70%" align="center"><b>Deck Name</b></td>
				<td width="20%" align="center"><b>Deck Color</b></td>
				<td width="10%" align="center"><b>#/$</b></td>
			</tr></thead>
			<tbody>';

			while( $row = mysqli_fetch_assoc( $sql ) )
			{
				echo '<tr>
				<td align="center"><a href="'.$tcgurl.'cards.php?view='.$view.'&deck='.$row['card_filename'].'">'.$row['card_deckname'].'</a></td>
				<td align="center"><font color="'.$row['card_color'].'">'.$row['card_color'].'</font></td>
				<td align="center">';
				if( $row['card_filename'] == "member" )
				{
					$query = $database->num_rows("SELECT * FROM `user_list` WHERE `usr_mcard`='Yes'");
					echo $memnum.'/0';
				}

				else
				{
					echo $row['card_count'].'/'.$row['card_worth'];
				}
				echo '</td>
				</tr>';
			}

			echo '</tbody></table>';
		}
	}
}

else
{
	$row = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_filename`='$deck' AND `card_status`='Active'");
	$set = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_id`='".$row['card_set']."'");
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
					while( $row2 = mysqli_fetch_assoc( $sql ) )
					{
						echo '<img src="'.$tcgcards.'mc-'.$row2['usr_name'].''.$tcgext.'" />';
					}
				}

				else
				{
					for( $x=1; $x<=$row['card_count']; $x++ )
					{
						if( $x < 10 )
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
						<td colspan="2" valign="middle"><b>Set/Series:</b> '.$set['set_name'].'</td>
					</tr>
					<tr>
						<td width="45%" valign="middle"><b>Made/Donated by:</b> '.$row['card_maker'].' / '.$row['card_donator'].'</td>
						<td width="45%" valign="middle"><b>Color:</b> <font color="'.$row['card_color'].'">'.$row['card_color'].'</font></td>
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
					<tr>
						<td colspan="2" valign="middle"><b>Mastered by:</b> '.$row['card_masters'].'</td>
					</tr>
				</tbody>
			</table>

			<h3>Other Decks</h3>
			<select onchange="location = this.value;" id="theinput" name="mastered" class="selectpicker dropdown" data-live-search="true" data-size="5" data-width="100%" style="width:100%">
				<option data-divider="true" style="height:10px;">-------------------</option>
				<option id="theinput" value="'.$tcgurl.'cards.php?view='.$view.'&deck='.$row['card_filename'].'" onClick="window.location = \''.$tcgurl.'cards.php?view='.$view.'&deck='.$row['card_filename'].'\';" disabled>'.$row['card_filename'].'</option>';
				$odecks = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' ORDER BY `card_filename`");
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