<?php
/************************************************
 * Tab:				User Masteries
 * Description:		Display user's mastered decks
 */


echo '<h2>Mastered Decks</h2>
<center>';
if( $general->getItem( 'itm_masteries' ) == "None" )
{
	echo '<i>You haven\'t mastered any decks yet.</i>';
}

else
{
	// Check if master badge have a post-fix
	$cards = explode(', ', $general->getItem('itm_masteries'));
	foreach( $cards as $card )
	{
		$name = $card.'-master';
		$file = glob("images/cards/" . $name . "*");
		if( count( $file ) > 0 )
		{
			$img = $card.'-master';
			echo '<img src="'.$tcgcards.''.$img.'.'.$tcgext.'" title="'.$img.'" />';
		}

		else
		{
			$img = $card;
			echo '<img src="'.$tcgcards.''.$img.'.'.$tcgext.'" title="'.$img.'" />';
		}

	}
}
echo '</center>';
?>