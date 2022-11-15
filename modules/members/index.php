<?php
/*******************************************
 * Module:			Members Main
 * Description:		Show list of all members
 */


if( empty( $id ) )
{
	@include($tcgpath.'themes/headers/mem-header.php');
	if( empty( $stat ) )
	{
		echo '<h1>Members</h1>
		<p>This is the full list of <b>active</b>, <b>pending</b> and members currently in <b>hiatus</b> of <i>'.$tcgname.'</i>. Please take note that all <b>pending</b> members are <u>allowed to participate in the TCG until approved</u>, but only <b>active</b> or approved members have a full access of the TCG.</p>

		<p>All members are sorted by <em>level</em> (but levels that have a member will be visible only), and then by <em>name in alphabetical order</em>. If you want to view the member\'s profile, decks they have mastered, achievements that they may have and the likes, just click on their member card.</p>';

		$lvlcount = $database->num_rows("SELECT * FROM `tcg_levels`");
		for( $i=1; $i<=$lvlcount; $i++ )
		{
			$select = $database->query("SELECT * FROM `user_list` WHERE `usr_level`='$i' AND `usr_status`='Active' ORDER BY `usr_name`");
			$counts = mysqli_num_rows($select);
			if( $counts == 0 ) {}
			else
			{
				echo "<h2>Level ".$i."</h2>\n";
				echo '<center>';
				while( $row = mysqli_fetch_assoc( $select ) )
				{
					echo '<div class="memList">
					<table width="340">
					<tr><td colspan="2" class="memName"><a href="'.$tcgurl.'members.php?id='.$row['usr_name'].'">'.$row['usr_name'].'</a></td></tr>
					<tr><td width="135" align="center">';
					if( $row['usr_mcard'] == "Yes" )
					{
						echo '<a href="'.$tcgurl.'members.php?id='.$row['usr_name'].'"><img src="'.$tcgcards.'mc-'.$row['usr_name'].'.'.$tcgext.'" /></a>';
					}

					else
					{
						echo '<a href="'.$tcgurl.'members.php?id='.$row['usr_name'].'"><img src="'.$tcgcards.'mc-filler.'.$tcgext.'" /></a>';
					}
					echo '</td>

					<td width="215">
					<div class="socIcon">';
						$prejoin = $row['usr_pre'];
						if( $prejoin == "Beta" )
						{
							echo '<li><font color="#e81a33"><span class="fas fa-star" aria-hidden="true" title="Beta Tester"></span></font></li>';
						}
							
						else if( $prejoin == "Yes" )
						{
							echo '<li><font color="#ffa500"><span class="fas fa-star" aria-hidden="true" title="Prejoiner"></span></font></li>';
						}

						else
						{
							echo '<li><font color="#636363"><span class="fas fa-star" aria-hidden="true" title="Non-Prejoiner"></span></font></li>';
						}

						echo '<li><a href="'.$row['usr_url'].'" target="_blank" title="Visit Trade Post"><span class="fas fa-home" aria-hidden="true"></span></a></li>';

						if( $row['usr_rand_trade'] == "0" )
						{
							echo '<li><font color="#d9a3a9"><span class="fas fa-bell-slash" aria-hidden="true" title="I don\'t accept random trades!"></span></font></li>';
						}

						else
						{
							echo '<li><font color="#a4c8de"><span class="fas fa-bell" aria-hidden="true" title="Send me any random trades, please!?"></span></font></li>';
						}

						if( $row['usr_auto_trade'] == "0" )
						{
							echo '<li><font color="#d9a3a9"><span class="fas fa-toggle-off" aria-hidden="true" title="Please don\'t put your trades through!"></span></font></li>';
						}

						else
						{
							echo '<li><font color="#a4c8de"><span class="fas fa-toggle-on" aria-hidden="true" title="Feel free to put all your trades through!"></span></font></li>';
						}

					echo '</div>
					Born on '.date("F d", strtotime($row['usr_bday'])).'<br />
					Collecting <a href="'.$tcgurl.'cards.php?view=released&deck='.$row['usr_deck'].'">'.$row['usr_deck'].'</a><br />';
					if( $row['usr_twitter'] == "N / A" )
					{
						echo 'I don\'t have a Twitter!<br />';
					}

					else
					{
						echo 'Twitter <a href="https://twitter.com/'.$row['usr_twitter'].'" target="_blank">@'.$row['usr_twitter'].'</a><br />';
					}

					if( $row['usr_discord'] == "N / A" )
					{
						echo 'I don\'t have a Discord!';
					}

					else
					{
						echo 'Discord <a href="">'.$row['usr_discord'].'</a>';
					}
						echo '</td></tr>
					</table>
					</div>';
				}
				echo '</center>';
			}
		}
	} // end empty stat

	else if( $stat == "pending" )
	{
		echo '<h1>Members : Pending</h1>
		<p>Below is the complete list of all pending members here at '.$tcgname.'. Although there is nothing much in their profile, you can click their names to view it.</p>';
		$general->member('Pending');
	}

	else if( $stat == "hiatus" )
	{
		echo '<h1>Members : Hiatus</h1>
		<p>Below is the complete list of all members under hiatus here at '.$tcgname.'. Please take note that members who are in hiatus may or may not accept trades, so we suggest to send them a message or check their trade post first to make sure.</p>';
		$general->member('Hiatus');
	}

	else if( $stat == "inactive" )
	{
		echo '<h1>Members : Inactive</h1>
		<p>Below is the complete list of all inactive members at '.$tcgname.'. These are members who are no longer active in the TCG or the TCG community in general.</p>';
		$general->member('Inactive');
	}

	else if( $stat == "retired" )
	{
		echo '<h1>Members : Retired</h1>
		<p>Below is the complete list of all members who quitted '.$tcgname.'. They are no longer a part of the TCG, so trading is no longer possible for them as they may have adopted their cards out.</p>';
		$general->member('Retired');
	}

	@include($tcgpath.'themes/headers/mem-footer.php');
} // end empty ID


// View full member profile
else
{
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_name`='$id'");
	$item = $database->get_assoc("SELECT * FROM `user_items` WHERE `itm_name`='$id'");
	$msg = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
	$log1 = $database->query("SELECT * FROM `user_logs` WHERE `log_name`='$id' ORDER BY `log_date` DESC");
	$log2 = $database->query("SELECT * FROM `user_trades` WHERE `trd_name`='$id' ORDER BY `trd_date` DESC");
	$lvl = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='".$row['usr_level']."'");

	echo '<h1>Profile : '.$row['usr_name'].'</h1>
	<table width="100%">
	<tr>
		<td width="20%" valign="top">
			<div class="socIcon2">
				<li><a href="'.$row['usr_url'].'" target="_blank" title="Visit Trade Post"><span class="fas fa-home" aria-hidden="true"></span></a></li>
				<li><span class="fas fa-gift" aria-hidden="true" title="Born on '.date("F d", strtotime($row['usr_bday'])).'"></span></li>';

				$prejoin = $row['usr_pre'];
				if( $prejoin == "Beta" )
				{
					echo '<li><font color="#e81a33"><span class="fas fa-cannabis" aria-hidden="true" title="Beta Tester"></span></font></li>';
				}

				else if( $prejoin == "Yes" )
				{
					echo '<li><font color="#ffa500"><span class="fas fa-cannabis" aria-hidden="true" title="Prejoiner"></span></font></li>';
				}

				else
				{
					echo '<li><font color="#636363"><span class="fas fa-cannabis" aria-hidden="true" title="Non-Prejoiner"></span></font></li>';
				}

				if( $row['usr_twitter'] == "N / A" ) {}
				else
				{
					echo '<li><a href="https://twitter.com/'.$row['usr_twitter'].'" target="_blank"><span class="fab fa-twitter" aria-hidden="true" title="@'.$row['usr_twitter'].'"></span></a></li>';
				}

				if( $row['usr_discord'] == "N / A" ) {}
				else
				{
					echo '<li><a href=""><span class="fab fa-discord" aria-hidden="true" title="'.$row['usr_discord'].'"></span></a></li>';
				}
			echo '</div><br />
			<center>

			<h3>'.$row['usr_name'].'</h3>';
			if( $row['usr_mcard'] == "Yes" )
			{
				echo '<img src="'.$tcgcards.'mc-'.$row['usr_name'].'.'.$tcgext.'" /><br />';
			}

			else
			{
				echo '<img src="'.$tcgcards.'mc-filler.'.$tcgext.'" /><br />';
			}

			echo '(mc-'.$row['usr_name'].')<br /><br />';

			if( $row['usr_level'] < 10 )
			{
				$level = '0'.$row['usr_level'];
			}

			else
			{
				$level = $row['usr_level'];
			}
			echo '<img src="'.$tcgimg.'badges/'.$item['itm_badge'].'-'.$level.'.png" /><br />(Level '.$row['usr_level'].')
			</center>
		</td>

		<td width="2%"></td>

		<td width="78%" valign="top">
			<ul class="tabs" data-persist="true">
				<li><a href="#overview">About Me</a></li>
				<li><a href="#wishlist">Wishlists</a></li>
				<li><a href="#masteries">Masteries</a></li>
				<li><a href="#gallery">Gallery</a></li>
				<li><a href="#logs">Logs</a></li>
				<li><a href="#trademe">Wanna trade?</a></li>
			</ul>

			<div class="tabcontents">
				<div id="overview">';
					@include($tcgpath.'modules/members/tabs/overview.tab.php');
				echo '</div><!-- #overview -->

				<div id="wishlist">';
					@include($tcgpath.'modules/members/tabs/wishlist.tab.php');
				echo '</div><!-- #wishlist -->

				<div id="masteries" align="center">';
					@include($tcgpath.'modules/members/tabs/masteries.tab.php');
				echo '</div><!-- #masteries -->

				<div id="gallery" align="center">';
					@include($tcgpath.'modules/members/tabs/gallery.tab.php');
				echo '</div><!-- #gallery -->

				<div id="logs" align="center">';
					@include($tcgpath.'modules/members/tabs/activity.tab.php');
					@include($tcgpath.'modules/members/tabs/trade.tab.php');
				echo '</div><!-- #logs -->

				<div id="trademe">';
					@include($tcgpath.'modules/members/tabs/trademe.tab.php');
				echo '</div><!-- #trademe -->
			</div><!-- /.tabcontents -->
		</td>
	</tr>
	</table>';
} // end profile view
?>