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
		comp = Math.round((Math.random() * 2) + 1); 
		var choicename;
		if( comp == 1 ) choicename = "rock";
		if( comp == 2 ) choicename = "paper";
		if( comp == 3 ) choicename = "scissors";
		msg.value = 'TCG chose ' + choicename + '.  ';

		switch( choice )
		{ // calculates score
			case 1 : // rock
				if( comp == 1 )
				{
					msg.value += 'It is a draw.';
					document.location="games.php?play=jan-ken-pon&go=draw";
					break;
				}

				if( comp == 2 )
				{
					msg.value += 'You lost!';
					document.location="games.php?play=jan-ken-pon&go=lost";
					break;
				}

				if( comp == 3 )
				{
					msg.value += 'You won!';
					document.location="games.php?play=jan-ken-pon&go=won";
					break;
				}

			case 2 : // paper
				if( comp == 1 )
				{
					msg.value += 'You won!';
					document.location="games.php?play=jan-ken-pon&go=won";
					break;
				}

				if( comp == 2 )
				{
					msg.value += 'It is a draw.';
					document.location="games.php?play=jan-ken-pon&go=draw";
					break;
				}

				if( comp == 3 )
				{
					msg.value += 'You lost!';
					document.location="games.php?play=jan-ken-pon&go=lost";
					break;
				}

			case 3 : // scissors
				if( comp == 1 )
				{
					msg.value += 'You lost!';
					document.location="games.php?play=jan-ken-pon&go=lost";
					break;
				}

				if( comp == 2 )
				{
					msg.value += 'You won!';
					document.location="games.php?play=jan-ken-pon&go=won";
					break;
				}

				if( comp == 3 )
				{
					msg.value += 'It is a draw.';
					document.location="games.php?play=jan-ken-pon&go=draw";
					break;
				}
		}

		msg.value += '';
	}
}