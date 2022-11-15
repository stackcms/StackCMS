<?php
/***********************************************************
 * Tab:				Gallery
 * Description:		Display user's list special cards/badges
 */


if( $item['itm_mcard'] == "None" ) {}
else
{
    echo '<h3>Member Cards</h3>
    <img src="'.$tcgcards.''.str_replace(", ", ".".$tcgext."\" title=\"\"> <img src=\"$tcgcards", $item['itm_mcard']).'.'.$tcgext.'">';
}
echo '<br /><br />';

if( $item['itm_ecard'] == "None" ) {}
else
{
    echo '<h3>Event Cards</h3>
    <img src="'.$tcgcards.''.str_replace(", ", ".".$tcgext."\" title=\"\"> <img src=\"$tcgcards", $item['itm_ecard']).'.'.$tcgext.'">';
}
echo '<br /><br />';

if( $item['itm_milestone'] == "" ) {}
else
{
    echo '<h3>Milestone Cards</h3>
    <img src="'.$tcgcards.''.str_replace(", ", ".".$tcgext."\" title=\"\"> <img src=\"$tcgcards", $item['itm_milestone']).'.'.$tcgext.'">';
}
?>