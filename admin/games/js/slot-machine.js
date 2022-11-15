// This is a different version of the slots game.
// This is literally a slot machine game using tokens.
// Unlike the simple slots game that uses an image.

// Array of images you have for this game, numbered accordingly:
slotitem = new Array('1','2','3','4','5','6','7','8','9','10');
document.slots.bet.focus();

starttoken=25;
document.slots.token.value=starttoken;

function stopplay()
{
	if( document.slots.token.value < starttoken ) 
	{
		alert("You lost "+ (starttoken-document.slots.token.value) +" tokens.");
		return;
	}

	if( document.slots.token.value-starttoken < 30 )
	{
		alert("You only gained "+ (document.slots.token.value-starttoken) +" tokens.");
		return;
	}

	else
	{
		confirm("You gained "+ (document.slots.token.value-starttoken) +" tokens. Redeem your winnings?");
		(window.location="/games.php?play=slot-machine&go="+ (document.slots.token.value-starttoken) +"");
	}
}


function rollem()
{
	if( document.slots.bet.value < 1 || document.slots.bet.value == "" ){
		alert("You cannot bet less that 1.   ");
		return;
	}

	if( Math.floor(document.slots.token.value) < Math.floor(document.slots.bet.value) )
	{
		alert("Your bet "+document.slots.bet.value+" is larger than your remaining tokens "+document.slots.token.value+".  ");
		return;
	}

	if( document.slots.bet.value > 1 )
	{
		document.slots.banner.value="Bet is "+document.slots.bet.value+" tokens.";
	}

	else
	{
		document.slots.banner.value="Bet is "+document.slots.bet.value+" tokens.";
	}
	counter=0;
	spinem();
}


function spinem()
{
	turns1=10+Math.floor((Math.random() * 10))
	for( a=0; a<turns1; a++ )
	{
		document.slots.slot1.src="/admin/games/images/sm0"+slotitem[a % 6]+".png";
	}

	turns2=10+Math.floor((Math.random() * 10))
	for( b=0; b<turns2; b++ )
	{
		document.slots.slot2.src="/admin/games/images/sm0"+slotitem[b % 6]+".png";
	}

	turns3=10+Math.floor((Math.random() * 10))
	for( c=0; c<turns3; c++ )
	{
		document.slots.slot3.src="/admin/games/images/sm0"+slotitem[c % 6]+".png";
	}
	counter++;
	if( counter < 20 ){
		setTimeout("spinem(counter);",50);
	}

	else
	{
		checkmatch();
	}
}


function checkmatch()
{
	if( (document.slots.slot1.src == document.slots.slot2.src) && (document.slots.slot1.src == document.slots.slot3.src) ) 
	{
		document.slots.banner.value="3 of a kind - You won "+Math.floor(document.slots.bet.value*10)+" tokens.";
		document.slots.token.value=Math.floor(document.slots.token.value)+Math.floor(document.slots.bet.value*10);
	}

	else if( (document.slots.slot1.src == document.slots.slot2.src) ||
	(document.slots.slot1.src == document.slots.slot3.src) ||
	(document.slots.slot2.src == document.slots.slot3.src) )
	{
		document.slots.banner.value="A pair - You won "+Math.floor(document.slots.bet.value*2)+" tokens.";
		document.slots.token.value = Math.floor(document.slots.bet.value*2) + Math.floor(document.slots.token.value);
	}

	else{
		document.slots.token.value=document.slots.token.value-document.slots.bet.value; 
		document.slots.banner.value="No match - You lost "+document.slots.bet.value+" tokens.";
	}
}
