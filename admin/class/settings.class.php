<?php
/*
 * Class library for settings functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Settings
 * Description:		Functions to use for displaying admin settings
 */
 
class Settings
{
	function getValue( $setting )
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

		$result = $pdo->prepare("SELECT `conf_value` FROM `tcg_settings` WHERE `conf_settings` = :setting");
		$result->bindParam(':setting', $setting, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		if( !$row ) {}
		else
		{
			return $row['conf_value'];
		}
	} // end of getValue


	function getName( $setting )
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

		$result = $pdo->prepare("SELECT `conf_settings` FROM `tcg_settings` WHERE `conf_settings` = :setting");
		$result->bindParam(':setting', $setting, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['conf_settings'];
	} // end of getName


	function getDesc( $setting )
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

		$result = $pdo->prepare("SELECT `conf_desc` FROM `tcg_settings` WHERE `conf_settings` = :setting");
		$result->bindParam(':setting', $setting, PDO::PARAM_STR);
		$result->execute();
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		return $row['conf_desc'];
	} // end of getDesc


	function update_setting( $settings, $value )
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

		$query = "UPDATE `tcg_settings` SET `conf_value` = :value WHERE `conf_settings` = :settings";
		$result = $pdo->prepare($query);
		$result->bindParam(':value', $value, PDO::PARAM_STR);
		$result->bindParam(':settings', $settings, PDO::PARAM_STR);
		$result->execute();
		if( !$result )
		{
			$error = __FILE__ . ':' . __LINE__ . '<br />
			Error executing query: <i>' . $result->errorInfo()[2] . '<br />
			</i>; Query is: <code>' . $query . '</code>';
			die( $error );
		}
	} // end of update_setting


	function update_settings( $settings )
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

		foreach( $settings as $field => $value )
		{
			$query = "UPDATE `tcg_settings` SET `conf_value` = :value WHERE " . " `conf_settings` = '$field'";
			if( $query != '' )
			{
				$result = $pdo->prepare($query);
				$result->bindParam(':value', $value, PDO::PARAM_STR);
				$result->execute();
				if( !$result )
				{
					$error = __FILE__ . ':' . __LINE__ . '<br >
					Error executing query: <i>' . $result->errorInfo()[2] . '<br />
					</i>; Query is: <code>' . $query . '</code>';
					die( $error );
				}
			}
		}
	} // end of settings update function
}
?>