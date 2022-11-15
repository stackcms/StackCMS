<?php
/********************************************************
 * Tab:				Masteries
 * Description:		Display user's list of mastered decks
 */


if( $item['itm_masteries'] == "None" )
{
	echo '<i>This member haven\'t mastered any decks yet.</i>';
}

else
{
	// Check if master badge have a post-fix
	$cards = explode(', ', $item['itm_masteries']);
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
?>