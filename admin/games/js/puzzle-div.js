const PUZZLE_DIFFICULTY = 5; // Define here the level of your puzzle. In this case is 4x4
const PUZZLE_HOVER_TINT = '#009900'; // Chose the color of the hover tint, to indicate where the piece is going to be placed

var _stage;
var _canvas;

var _img;
var _pieces;
var _puzzleWidth;
var _puzzleHeight;
var _pieceWidth;
var _pieceHeight;
var _currentPiece;
var _currentDropPiece;

var _mouse;

function init()
{
	_img = new Image();
	_img.addEventListener('load',onImage,false);
	_img.src = "/admin/games/rounds/puzzle<?php echo $Puzzle; ?>.jpg"; // Image location, $Puzzle variable is to call random puzzle images.
}

function onImage( e )
{
	_pieceWidth = Math.floor(_img.width / PUZZLE_DIFFICULTY)
	_pieceHeight = Math.floor(_img.height / PUZZLE_DIFFICULTY)
	_puzzleWidth = _pieceWidth * PUZZLE_DIFFICULTY;
	_puzzleHeight = _pieceHeight * PUZZLE_DIFFICULTY;
	setCanvas();
	initPuzzle();
}

function setCanvas()
{
	_canvas = document.getElementById('canvas');
	_stage = _canvas.getContext('2d');
	_canvas.width = _puzzleWidth;
	_canvas.height = _puzzleHeight;
	_canvas.style.border = "1px solid white"; // You can change or remove the border of the whole puzzle here
}

function initPuzzle()
{
	_pieces = [];
	_mouse = {x:0,y:0};
	_currentPiece = null;
	_currentDropPiece = null;
	_stage.drawImage(_img, 0, 0, _puzzleWidth, _puzzleHeight, 0, 0, _puzzleWidth, _puzzleHeight);
	createTitle("Click to Start Puzzle");
	buildPieces();
}

function createTitle( msg )
{
	_stage.fillStyle = "#FFFFFF";
	_stage.globalAlpha = .4;
	_stage.fillRect(100,_puzzleHeight - 40,_puzzleWidth - 200,40);
	_stage.fillStyle = "#FFFFFF";
	_stage.globalAlpha = 1;
	_stage.textAlign = "center";
	_stage.textBaseline = "middle";
	_stage.font = "20px Arial";
	_stage.fillText(msg,_puzzleWidth / 2,_puzzleHeight - 20);
}

function buildPieces()
{
	var i;
	var piece;
	var xPos = 0;
	var yPos = 0;
	for( i = 0; i < PUZZLE_DIFFICULTY * PUZZLE_DIFFICULTY; i++ )
	{
		piece = {};
		piece.sx = xPos;
		piece.sy = yPos;
		_pieces.push(piece);
		xPos += _pieceWidth;
		if( xPos >= _puzzleWidth )
		{
			xPos = 0;
			yPos += _pieceHeight;
		}
	}
	document.onmousedown = shufflePuzzle;
}

function shufflePuzzle()
{
	_pieces = shuffleArray(_pieces);
	_stage.clearRect(0,0,_puzzleWidth,_puzzleHeight);
	var i;
	var piece;
	var xPos = 0;
	var yPos = 0;
	for( i = 0; i < _pieces.length; i++ )
	{
		piece = _pieces[i];
		piece.xPos = xPos;
		piece.yPos = yPos;
		_stage.drawImage(_img, piece.sx, piece.sy, _pieceWidth, _pieceHeight, xPos, yPos, _pieceWidth, _pieceHeight);
		_stage.strokeStyle = "transparent";  // You can change the border of the puzzle PIECES here. Put any color or leave it transparent
		_stage.strokeRect(xPos, yPos, _pieceWidth,_pieceHeight);
		xPos += _pieceWidth;
		if( xPos >= _puzzleWidth )
		{
			xPos = 0;
			yPos += _pieceHeight;
		}
	}
	document.onmousedown = onPuzzleClick;
}

function onPuzzleClick( e )
{
	if( e.pageX || e.pageX == 0 )
	{
		_mouse.x = e.pageX - _canvas.offsetLeft;
		_mouse.y = e.pageY - _canvas.offsetTop;
	}

	else if( e.offsetX || e.offsetX == 0 )
	{
		_mouse.x = e.offsetX;
		_mouse.y = e.offsetY;
	}

	_currentPiece = checkPieceClicked();

	if( _currentPiece != null )
	{
		_stage.clearRect(_currentPiece.xPos,_currentPiece.yPos,_pieceWidth,_pieceHeight);
		_stage.save();
		_stage.globalAlpha = .9;
		_stage.drawImage(_img, _currentPiece.sx, _currentPiece.sy, _pieceWidth, _pieceHeight, _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth, _pieceHeight);
		_stage.restore();
		document.onmousemove = updatePuzzle;
		document.onmouseup = pieceDropped;
	}
}

function checkPieceClicked()
{
	var i;
	var piece;
	for( i = 0; i < _pieces.length; i++ )
	{
		piece = _pieces[i];
		if(_mouse.x < piece.xPos || _mouse.x > (piece.xPos + _pieceWidth) || _mouse.y < piece.yPos || _mouse.y > (piece.yPos + _pieceHeight))
		{
		}

		else
		{
			return piece;
		}
	}
	return null;
}

function updatePuzzle( e )
{
	_currentDropPiece = null;
	if( e.pageX || e.pageX == 0 )
	{
		_mouse.x = e.pageX - _canvas.offsetLeft;
		_mouse.y = e.pageY - _canvas.offsetTop;
	}

	else if( e.offsetX || e.offsetX == 0 )
	{
		_mouse.x = e.offsetX;
		_mouse.y = e.offsetY;
	}

	_stage.clearRect(0,0,_puzzleWidth,_puzzleHeight);
	var i;
	var piece;

	for( i = 0; i < _pieces.length; i++)
	{
		piece = _pieces[i];
		if( piece == _currentPiece )
		{
			continue;
		}

		_stage.drawImage(_img, piece.sx, piece.sy, _pieceWidth, _pieceHeight, piece.xPos, piece.yPos, _pieceWidth, _pieceHeight);
		_stage.strokeRect(piece.xPos, piece.yPos, _pieceWidth,_pieceHeight);

		if( _currentDropPiece == null )
		{
			if(_mouse.x < piece.xPos || _mouse.x > (piece.xPos + _pieceWidth) || _mouse.y < piece.yPos || _mouse.y > (piece.yPos + _pieceHeight))
			{
			}

			else
			{
				_currentDropPiece = piece;
				_stage.save();
				_stage.globalAlpha = .4;
				_stage.fillStyle = PUZZLE_HOVER_TINT;
				_stage.fillRect(_currentDropPiece.xPos,_currentDropPiece.yPos,_pieceWidth, _pieceHeight);
				_stage.restore();
			}
		}
	}
	_stage.save();
	_stage.globalAlpha = .6;
	_stage.drawImage(_img, _currentPiece.sx, _currentPiece.sy, _pieceWidth, _pieceHeight, _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth, _pieceHeight);
	_stage.restore();
	_stage.strokeRect( _mouse.x - (_pieceWidth / 2), _mouse.y - (_pieceHeight / 2), _pieceWidth,_pieceHeight);
}

function pieceDropped( e )
{
	document.onmousemove = null;
	document.onmouseup = null;
	if( _currentDropPiece != null )
	{
		var tmp = {xPos:_currentPiece.xPos,yPos:_currentPiece.yPos};
		_currentPiece.xPos = _currentDropPiece.xPos;
		_currentPiece.yPos = _currentDropPiece.yPos;
		_currentDropPiece.xPos = tmp.xPos;
		_currentDropPiece.yPos = tmp.yPos;
	}
	resetPuzzleAndCheckWin();
}

function resetPuzzleAndCheckWin()
{
	_stage.clearRect(0,0,_puzzleWidth,_puzzleHeight);
	var gameWin = true;
	var i;
	var piece;
	for( i = 0; i < _pieces.length; i++ )
	{
		piece = _pieces[i];
		_stage.drawImage(_img, piece.sx, piece.sy, _pieceWidth, _pieceHeight, piece.xPos, piece.yPos, _pieceWidth, _pieceHeight);
		_stage.strokeRect(piece.xPos, piece.yPos, _pieceWidth,_pieceHeight);
		if( piece.xPos != piece.sx || piece.yPos != piece.sy )
		{
			gameWin = false;
		}
	}

	if( gameWin )
	{
		setTimeout(gameOver,500);
	}
}

function gameOver()
{
	document.onmousedown = null;
	document.onmousemove = null;
	document.onmouseup = null;
	document.location="/games.php?play=puzzle&go=prize"; // Load prize
}

function shuffleArray( o )
{
	for( var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x );
	return o;
}

init();