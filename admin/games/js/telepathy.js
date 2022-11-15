var guessme = Math.round(Math.random()*(99)+1);
var speech = 'Guess my number (from 1 to 100)!';

function process( mystery )
{
	var guess=document.forms.guessquiz.guess.value;
	var speech='"'+guess+ '" doesn\'t make sense . . .';
	document.forms.guessquiz.guess.value='';

	if( guess == mystery )
	{
		document.forms.guessquiz.prompt.value='Congratulations! '+mystery+' is correct!';
		alert ('That is correct! The number is '+mystery+'!');
		speech='';
		document.location="/games.php?play=telepathy&go=prize";
	}

	if( mystery < guess )
	{
		speech='No, it\'s less than '+ guess;
	}

	if( mystery > guess )
	{
		speech='No, it\'s more than '+ guess;
	}

	if( guess == '' )
	{
		speech='You didn\'t guess anything!'
	}

	document.forms.guessquiz.prompt.value=speech; document.forms.guessquiz.guess.focus();
}

// end hide -->