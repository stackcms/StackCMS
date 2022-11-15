// Suggestion by:  mi-cul@boya-kasha.co.uk
// Based on code by:  Maximilian Stocker (maxstocker@reallyusefulcomputing.com)

// Original:  Ronnie T. Moore
// Web Site:  The JavaScript Source

// This script and many more are available free online at
// The JavaScript Source!! http://javascript.internet.com

// Begin
function playGame( choice )
{
	with( document.game )
	{
		comp = Math.round((Math.random() * 1) + 1); 
		var choicename;
		if( comp == 1 ) choicename = "heads";
		if( comp == 2 ) choicename = "tails";
		msg.value = 'It is ' + choicename + '.  ';

		switch( choice )
		{ // calculates score
			case 1 : // heads
				if( comp == 1 )
				{
					msg.value += 'You won!';
					document.location="games.php?play=coin-flip&go=prize";
					break;
				}

				if( comp == 2 ){
					msg.value += 'You lost!';
					document.location="games.php?play=coin-flip&go=lost";
					break;
				}

				case 2 : // tails
				if( comp == 1 )
				{
					msg.value += 'You lost!';
					document.location="games.php?play=coin-flip&go=lost";
					break;
				}

				if( comp == 2 )
				{
					msg.value += 'You won!';
					document.location="games.php?play=coin-flip&go=prize";
					break;
				}
		}

		msg.value += ' ';
	}
}