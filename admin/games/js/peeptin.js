// Flooble.com peeptin start
// http://www.flooble.com/scripts/peeptin.php

var max = 3;
var score = 0;
var moves = 0;
var ex = 3;
var ey = 3;

function getElement15( form, name )
{
	var k;
	var elements = form.elements;
	for( k = 0; k < elements.length; k++ )
	{
		if( elements[k].name == name ) return elements[k];
	}
}

function press15( form, button )
{
	name = button.name;
	x = name.substring(0,1);
	y = name.substring(2,3);
	play15(form, (x-1+1), (y-1+1));
}

function shuffle15( form, num )
{
	for (i = 0; i < num; i++)
	{
		x = Math.floor(Math.random(4) * 4);
		if( x == 0 )
		{
			toggle15(form, ex, ey, ex + 1, ey);
		}

		else if( x == 1 )
		{
			toggle15(form, ex, ey, ex - 1, ey);
		}

		else if( x == 2 )
		{
			toggle15(form, ex, ey, ex, ey + 1);
		}

		else if( x == 3 )
		{
			toggle15(form, ex, ey, ex, ey - 1);
		}
	}
}

function play15( form, x, y )
{
	if( Math.abs(ex - x) + Math.abs(ey - y) == 1 )
	{
		done = toggle15(form, x, y, x+1, y);
		if( !done )
		{
			done = toggle15(form, x, y, x-1, y);
		}

		if( !done )
		{
			done = toggle15(form, x, y, x, y+1);
		}

		if( !done )
		{
			done = toggle15(form, x, y, x, y-1);
		}

		moves++;

		if( check15( form ) )
		{
			alert('You win with ' + moves + ' moves!');
			resetboard15(form);
			document.location="/games.php?play=peeptin&go=prize";
		}
	}
}

function showrules15()
{
	rules = 'Peeptin by flooble.com \n\n' 
		+ 'The goal of the game is to arrange \n' 
		+ 'the blocks from 1 to 15 in their \n'
		+ 'numeric order. Click a number next to\n'
		+ 'the empty cell to move it into that cell.\n'
		+ 'The game is won when all the numbers\n'
		+ 'are sorted, and the empty square is in the \n'
		+ 'lower-righthand corner.';
	alert(rules);
}

function resetboard15( form )
{
	for( i = 0; i < 4; i++ )
	{
		for( j = 0; j < 4; j++ )
		{
			val = 1 + i + (4*j);
			if( val == 16 )
			{
				getElement15(form,i + '_' + j).value = ' ';
			}

			else
			{
				getElement15(form,i + '_' + j).value = val;
			}
		}
	}
	score = 0;
	moves = 0;
	ex = 3;
	ey = 3;
}

function toggle15( form, x, y, x1, y1 )
{
	if( x < 0 || y < 0 || x > max || y > max )
	{
		return false;
	}

	if( x1 < 0 || y1 < 0 || x1 > max || y1 > max )
	{
		return false;
	}

	name = x + '_' + y;
	button = getElement15(form,name);
	name = x1 + '_' + y1;
	button1 = getElement15(form,name);
	if( button.value == ' ' || button1.value == ' ' )
	{
		tmp = button.value;
		button.value = button1.value;
		button1.value = tmp;
		if( button.value == ' ' )
		{
			ex = x;
			ey = y;
		}

		else
		{
			ex = x1;
			ey = y1;
		}

		return true;
	}

	return false;
}

function check15( form )
{
	score = 0;
	for( i = 0; i < 4; i++ )
	{
		for( j = 0; j < 4; j++ )
		{
			val = 1 + i + (4*j);
			if( val < 16 )
			{
				if( getElement15(form,i + '_' + j).value == val )
				{
					score++;
				}
			}
		}
	}

	return score == 15;
}