<?php
/*************************************************
 * Tab:				User Gallery
 * Description:		Display user's collected cards
 */


echo '<h2>Member Cards</h2>
<center>';
if( $general->getItem( 'itm_mcard' ) == "None" || $general->getItem( 'itm_mcard' ) == "" )
{
	echo '<i>You haven\'t traded any member cards yet.</i>';
}

else
{
	echo '<img src="'.$tcgcards.''.str_replace(", ", ".$tcgext\" title=\"\"> <img src=\"$tcgcards", $general->getItem('itm_mcard')).'.'.$tcgext.'">';
}
echo '</center>


<h2>Event Cards</h2>
<center>';
if( $general->getItem( 'itm_ecard' ) == "None" || $general->getItem( 'itm_ecard' ) == "")
{
	echo '<i>You haven\'t pulled any event cards yet.</i>';
}

else
{
	echo '<img src="'.$tcgcards.''.str_replace(", ", ".$tcgext\" title=\"\"> <img src=\"$tcgcards", $general->getItem('itm_ecard')).'.'.$tcgext.'">';
}
echo '</center>


<h2>Milestone Cards</h2>
<center>';
if( $general->getItem( 'itm_milestone' ) == "None" || $general->getItem( 'itm_milestone' ) == "" )
{
	echo '<i>You haven\'t gained any milestone cards yet.</i>';
}

else {
	echo '<img src="'.$tcgcards.''.str_replace(", ", ".$tcgext\" title=\"\"> <img src=\"$tcgcards", $general->getItem('itm_milestone')).'.'.$tcgext.'">';
}
echo '</center>';
?>