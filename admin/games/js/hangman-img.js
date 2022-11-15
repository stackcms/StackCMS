// HangMan II script- By Chris Fortey (http://www.c-g-f.net/)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// Please keep notice intact
 
var can_play = true;

// Change the array of words according to yours below
// Add as much words as you need, separated by a comma:
var words = new Array("WORD01", "WORD02", "WORD03", "WORD04");

var to_guess = "";
var display_word = "";
var used_letters = "";
var wrong_guesses = 0;

function selectLetter( l )
{
	if( can_play == false )
	{
		return;
	}

	if( used_letters.indexOf(l) != -1 )
	{
		return;
	}

	used_letters += l;
	document.game.usedLetters.value = used_letters;

	if( to_guess.indexOf(l) != -1 )
	{
		// correct letter guess
		pos = 0;
		temp_mask = display_word;

		while( to_guess.indexOf(l, pos) != -1 )
		{
			pos = to_guess.indexOf(l, pos);                
			end = pos + 1;

			start_text = temp_mask.substring(0, pos);
			end_text = temp_mask.substring(end, temp_mask.length);

			temp_mask = start_text + l + end_text;
			pos = end;
		}

		display_word = temp_mask;
		document.game.displayWord.value = display_word;

		if( display_word.indexOf("*") == -1 )
		{
			// won
			alert("Congrats, you guessed the series and saved the stick man!");
			can_play = false;
			document.location="/games.php?play=hangman-img&go=nicesave";
		}
	}

	else
	{
		// incortect letter guess
		wrong_guesses += 1;
		eval("document.hm.src=\"/admin/games/images/hm" + wrong_guesses + ".png\"");

		if( wrong_guesses == 10 )
		{
			// lost
			alert("Sorry, you lost! Try again?");
			can_play = false;
		}
	}
}

function reset()
{
	selectWord();
	document.game.usedLetters.value = "";
	used_letters = "";
	wrong_guesses = 0;
	document.hm.src="/admin/games/images/hmstart.png";
}

function selectWord()
{
	can_play = true;
	random_number = Math.round(Math.random() * (words.length - 1));
	to_guess = words[random_number];
	//document.game.theWord.value = to_guess;

	// display masked word
	masked_word = createMask(to_guess);
	document.game.displayWord.value = masked_word;
	display_word = masked_word;
}

function createMask( m )
{
	mask = "";
	word_lenght = m.length;

	for( i = 0; i < word_lenght; i ++ )
	{
		mask += "*";
	}
	return mask;
}