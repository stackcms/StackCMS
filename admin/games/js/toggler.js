// Flooble.com toggler start
// http://www.flooble.com/scripts/toggler.php

var max = 3;
var score = 0;
var moves = 0;

function press( form, button )
{
	name = button.name;
	x = name.substring(0,1);
	y = name.substring(2,3);
	play(form, (x-1+1), (y-1+1));
}

function play( form, x, y )
{
	moves++;
	toggle(form, x, y);
	toggle(form, x-1, y);
	toggle(form, x+1, y);
	toggle(form, x, y-1);
	toggle(form, x, y+1);
	if( score == 16 )
	{
		document.location="games.php?play=toggler&go=prize";
	}
}

function showrules()
{
	rules = 'Toggler - from flooble.com \n\n'
		+ 'The goal of the game is to turn \n' 
		+ 'all the buttons from [X] to [.]. \n'
		+ 'This is done by clicking buttons. \n'
		+ 'When a button is clicked, its state is \n'
		+ 'toggeled, but so is the state of four \n'
		+ 'buttons around it, so plan carefully! \n';
	alert(rules);
}

function resetboard( form )
{
	var i,j,button;
	for(i = 0; i < 4; i++ )
	{
		for( j = 0; j < 4; j++ )
		{
			button = getelement(form, i + '_' + j);	
			button.value='X';
		}
	}
	score = 0;
	moves = 0;
}

function getelement( form, name )
{
	var k;
	var elements = form.elements;
	for( k = 0; k < elements.length; k++ )
	{
		if (elements[k].name == name) return elements[k];
	}
}

function toggle( form, x, y )
{
	if( x < 0 || y < 0 || x > max || y > max )
	{
		//alert('Ignore (' + x + ',' + y + ')');
		return;
	}
	name = x + '_' + y;
	button = getelement(form, name);
	a = button.value;
	button.value = '!!!';
	//alert(a + '  (' + x + ',' + y + ')') ;
	button.value = a;
	if( button.value == 'X' )
	{
		button.value = '.';
		score ++;
	}

	else
	{
		button.value = 'X';
		score --;
	}
}