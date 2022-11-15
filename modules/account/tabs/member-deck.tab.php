<?php
/**********************************************
 * Tab:				Member Deck
 * Description:		Display user's member decks
 */


echo '<h2>Member Decks</h2>
<center>';
function trim_value(&$value) { $value = trim($value); }
$mdeck = $database->query("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$player' AND `ud_completed`='0' ORDER BY `ud_finished`");
$counts = $database->num_rows("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$player'");
    
if( $counts == 0 )
{
	echo '<h1>Member Decks</h1>
	<p>You don\'t have an active member deck at the moment. If you haven\'t yet, you can <a href="'.$tcgurl.'services.php?form=member-deck">activate</a> your first member deck!</p>';
}

else
{
	while( $col = mysqli_fetch_assoc( $mdeck ) )
	{
		$data = array();
		$cards = array();

		if( $col['ud_cards'] != '' )
		{
			$cards = explode(';', $col['ud_cards']);
			array_walk($cards, 'trim_value');
			$count2 = count($cards);

			$deck = explode(',', $col['ud_deck']);
			$image = explode(';', $col['ud_cards']);
			foreach( $cards as $key => $card )
			{
				$data[$card] = array(
					'img' => trim($image[$key])
				);
			}
		}

		echo '<center><h1>'.$col['ud_deck'].' (';
		if( empty( $col['ud_cards'] ) )
		{
			echo '0';
		}

		else
		{
			echo $count2;
		}
		echo ' / '.$col['ud_count'].')</h1>

		<table width="505" cellspacing="0" cellpadding="0" border="0"><tr>';
		for( $i = 1; $i <= $col['ud_count']; $i++ )
		{
			if( $i < 10 )
			{
				$digit = "0".$i;
			}
			else
			{
				$digit = $i;
			}

			if( in_array($i, $cards) )
			{
				echo '<td width="101" align="center" height="101"><img src="'.$tcgcards.''.$col['ud_deck'].''.$digit.'.'.$tcgext.'" /></td>';
			}
			else
			{
				echo '<td width="101" align="center" height="101"><img src="'.$tcgcards.'filler.'.$tcgext.'" /></td>';
			}

			if( $col['ud_break'] !== '0' && $i % $col['ud_break'] == 0 )
				echo '</tr>';
		}
		echo '</table></center>';
	}
}
echo '</center>';
?>