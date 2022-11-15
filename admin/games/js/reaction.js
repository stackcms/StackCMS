// Copyright (c) Torben Wolf
// http://webmasterweb.de

x = 0;
x2 = 0;
x3 = 0;
function tempo3()
{
	x3 = 0;
	window.setTimeout("tempo()",1500);
}

function tempo()
{
	document.ausgabe.versuche.value = x3;
	x = 0;
	x2 = 0;
	y = Math.random();
	if( y <= 0.5 )
	{
		x = "1";
	}

	if( y >= 0.5 )
	{
		x = "2";
	}

	if( x == "1" )
	{
		document.ausgabe.text.value = "left";
	}

	if( x == "2" )
	{
		document.ausgabe.text.value = "right";
	}
	window.setTimeout("tempo2()",750);
}

function tempo2()
{
	if( x2 == x )
	{
		document.ausgabe.text.value = "";
		x3 = x3+1;
		window.setTimeout("tempo()",200);
  
		if( x3 == 5 )
		{
			document.location="games.php?play=reaction&go=prize";
		}
	}

	else
		if( x2 != x )
		{
			document.ausgabe.text.value = "Too slow !";
			if( x3 == 4 )
			{
				document.location="games.php?play=reaction&go=four";
			}

			if( x3 == 3 )
			{
				document.location="games.php?play=reaction&go=three";
			}

			if( x3 == 2 )
			{
				document.location="games.php?play=reaction&go=two";
			}

			if( x3 == 1 )
			{
				document.location="games.php?play=reaction&go=one";
			}

			if( x3 == 0 )
			{
				document.location="games.php?play=reaction&go=zero";
			}
		}
}

function gas()
{
	x2 = "1";
}

function bremsen()
{
	x2 = "2";
}