<?php
/*
 * Class library for games functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Games
 * Description:		Functions to use for calling game values
 */
class Games
{
	function gameTitle( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_title` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_title'] ?? '';
	} // END GET GAME TITLE


	function gameSub( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_subtitle` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_subtitle'] ?? '';
	} // END GET GAME SUBTITLE


	function gameSet( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_set` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_set'] ?? '';
	} // END GET GAME SET


	function gameType( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_type` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_type'];
	} // END GET GAME TYPE


	function gameExcerpt( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_excerpt` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_excerpt'];
	} // END GET GAME EXCERPT


	function gameBlurb( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_desc` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_desc'];
	} // END GET GAME DESCRIPTION


	function gameChoiceArr( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_choice_array` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_choice_array'];
	} // END GET GAME CHOICE CARDS


	function gameRandArr( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_random_array` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_random_array'];
	} // END GET GAME RANDOM CARDS


	function gameCurArr( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_currency_array` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_currency_array'];
	} // END GET GAME CURRENCIES AMOUNT


	function gameClueArr( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_clue_array` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_clue_array'];
	} // END GET GAME CLUE ARRAY


	function gameQuesArr( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_ques_array` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_ques_array'];
	} // END GET GAME QUESTION ARRAY


	function gamePassArr( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_pass_array` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_pass_array'];
	} // END GET GAME PASSWORD ARRAY


	function gameCurrentArr( $value )
	{
		try
		{
			$pdo = new PDO("mysql:host=$GLOBALS[db_server];dbname=$GLOBALS[db_database]", $GLOBALS['db_user'], $GLOBALS['db_password']);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch( PDOException $e )
		{
			echo "Error: " . $e->getMessage();
		}

		$result = $pdo->prepare("SELECT `game_current_array` FROM `tcg_games` WHERE `game_slug` = :game");
		$result->bindParam(':game', $value, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['game_current_array'];
	} // END GET GAME CURRENT ARRAY
}
?>