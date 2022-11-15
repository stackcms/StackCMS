<?php
/**************************************************
 * Tab:				Wishlist
 * Description:		Display user's wishlisted decks
 */


$wish = $database->query("SELECT * FROM `user_wishlist` WHERE `wlist_name`='".$row['usr_name']."' ORDER BY `wlist_deck` ASC");
$counts = mysqli_num_rows($wish);

if( $counts != 0 )
{
	$wishes = array();

	while( $row = mysqli_fetch_array( $wish ) )
	{
		// Check if master badge have a post-fix
		if( strpos($row['wlist_deck'], '-master') !== false ) {
			$img = $row['wlist_deck'].'-master';
		}

		else {
			$img = $row['wlist_deck'];
		}

		$wishes[] = '<a href="'.$tcgurl.'cards.php?view=released&deck='.$row['wlist_deck'].'"><img src="'.$tcgcards.''.$img.'.'.$tcgext.'" title="'.$row['wlist_deck'].'" /></a>';
	}

	echo implode(' ', $wishes);
}

else
{
	echo '<i>This user hasn\'t added any decks on their wishlist.</i>';
}
?>