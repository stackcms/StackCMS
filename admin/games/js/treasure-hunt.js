/*
 * Treasure Hunt Game
 * File Name - treasurehunt.js
 * Author - No Starch Press
 * Website - http://nostarch.com/
 * All rights reserved.
 */

var getRandomNumber = function (size)
{
	return Math.floor(Math.random() * size);
}

// Calculate distance between click event and target
var getDistance = function (event, target)
{
	var diffX = event.offsetX - target.x;
	var diffY = event.offsetY - target.y;
	return Math.sqrt(diffX * diffX + diffY * diffY);
};

// Get a string representing the distance
var getDistanceHint = function (distance)
{
	//add if statement here to tell the person how close they are!
	if( distance > 10 )
	{
		$("#distance").text("You are " + distance + " miles away!");
	}

	else
	{
		$("#distance").text("You are " + distance + " steps away!");
	}
};

// Set up our vaariables
var width = 400;
var height = 350;
var clicks = 0;

// Create a random target location
var target =
{
	x: getRandomNumber(width),
	y: getRandomNumber(height)
};

// Add a click handler to the img element
$("#map").click(function (event)
{
	clicks++;

	// Get distance between click event and target
	var distance = getDistance(event, target);

	// Convert distance to a hint
	var distanceHint = getDistanceHint(distance);

	// Update the #distance element with the new hint
	$("#distance").text(distanceHint);

	// If the click was close enough, tell them they won
	if( distance < 8 )
	{
		if( window.confirm("Found the treasure in " + clicks + " digs!") )
		{
			document.location="/games.php?play=treasure-hunt&go=prize";
		}
	}
});