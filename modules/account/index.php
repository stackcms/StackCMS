<?php
/******************************************
 * Module:			Account Main
 * Description:		Show user account panel
 */


if( empty( $login ) )
{
	header( "Location: account.php?do=login" );
}

else
{
    $row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
	$trd = $database->get_assoc("SELECT * FROM `user_trades_rec` WHERE `trd_name`='".$row['usr_name']."'");
	$lvlName = $database->get_assoc("SELECT `lvl_name` FROM `tcg_levels` WHERE `lvl_id`='".$row['usr_level']."'");
	$about = stripslashes($row['usr_bio']);

	// Explode bombs
	$curValue = explode(' | ', $general->getItem( 'itm_currency' ));
	$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
	foreach( $curValue as $key => $value )
	{
		$tn = substr_replace($curName[$key],"",-4);
		if( $curValue[$key] > 1 )
		{
			$var = substr($tn, -1);
			if( $var == "y" )
			{
				$tn = substr_replace($tn,"ies",-1);
			}
			else if( $var == "o" )
			{
				$tn = substr_replace($tn,"oes",-1);
			}
			else
			{
				$tn = $tn.'s';
			}
		}
		else
		{
			$tn = $tn;
		}

		if( empty($curValue[$key]) )
		{
			$arrayCell[] = '<img src="'.$tcgimg.''.$curName[$key].'"><br /><b>x0</b> '.$tn.'<br /><br />';
		}
		else
		{
			$arrayCell[] = '<img src="'.$tcgimg.''.$curName[$key].'"><br /><b>x'.$curValue[$key].'</b> '.$tn.'<br /><br />';
		}
	}
	// Fix all bombs after explosions
	$arrayCell = implode(" ", $arrayCell);

	echo '<ul class="tabs" data-persist="true">
		<li><a href="#overview">Overview</a></li>
		<li><a href="#activity">Activity Logs</a></li>
		<li><a href="#trade">Trade Logs</a></li>
		<li><a href="#masteries">Mastered Decks</a></li>
		<li><a href="#gallery">Gallery</a></li>
		<li><a href="#donations">Donations</a></li>';
		// Check for Member Deck plugin
		$sql = $database->num_rows("SHOW TABLES LIKE 'tcg_cards_user'");
		if( $sql == 0 ) {}
		else
		{
			echo '<li><a href="#member-decks">Member Decks</a></li>';
		}
	echo '</ul>

	<div class="tabcontents" align="left">
		<div id="overview">';
			@include($tcgpath.'modules/account/tabs/overview.tab.php');
		echo '</div><!-- #overview -->

		<div id="activity">';
			@include($tcgpath.'modules/account/tabs/activity.tab.php');
		echo '</div><!-- #activity -->

		<div id="trade">';
			@include($tcgpath.'modules/account/tabs/trade.tab.php');
		echo '</div><!-- #trade -->

		<div id="masteries">';
			@include($tcgpath.'modules/account/tabs/masteries.tab.php');
		echo '</div><!-- #masteries -->

		<div id="gallery">';
			@include($tcgpath.'modules/account/tabs/gallery.tab.php');
		echo '</div><!-- #gallery -->

		<div id="donations">';
			@include($tcgpath.'modules/account/tabs/donations.tab.php');
		echo '</div><!-- #donations -->';

		// Check for Member Deck plugin
		$sql = $database->num_rows("SHOW TABLES LIKE 'tcg_cards_user'");
		if( $sql == 0 ) {}
		else
		{
			echo '<div id="member-decks">';
			@include($tcgpath.'modules/account/tabs/member-deck.tab.php');
			echo '</div><!-- #member-decks -->';
		}
	echo '</div><!-- .tabcontents -->';
}
?>