<?php
/*
 * Class library for database functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Database
 * Description:		Fetch database queries
 */
class Database
{
	function connect()
	{
		$link = @mysqli_connect( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE )
		or die( "Couldn't connect to MYSQL: ".mysqli_error($link) );
		return $link;
	}


	function query( $query )
	{
		$link = $this->connect();
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		return $result;
	}


	function get_assoc( $query )
	{
		$link = $this->connect();
		$result = mysqli_query($link, $query);

		if( !$result )
		{
			die ( "Couldn't process query: ".mysqli_error($link) );
		}

		$assoc = mysqli_fetch_assoc($result);
		return $assoc;
	}


	function get_array( $query )
	{
		$link = $this->connect();
		$result = mysqli_query($link, $query);

		if( !$result )
		{
			die ( "Couldn't process query: ".mysqli_error($link) );
		}

		$array = mysqli_fetch_array($result);
		return $array;
	}


	function num_rows( $query )
	{
		$link = $this->connect();
		$result = mysqli_query($link, $query);

		if( !$result )
		{
			die ( "Couldn't process query: ".mysqli_error($link) );
		}

		$num_rows = mysqli_num_rows($result);
		return $num_rows;
	}


	function escape( $query )
    {
        $link = $this->connect();
        $escape = mysqli_real_escape_string( $link, $query );
        return $escape;
    }
}


/********************************************************
 * Class:			Sanitize
 * Description:		Sanitize form values before DB insertion
 */
class Sanitize
{
	function clean( $data )
	{
		$data = stripslashes($data);
		$data = trim(htmlentities(strip_tags($data)));
		return $data;
	}


	function for_db( $data )
	{
		$database = new Database;
		$link = $database->connect();

		$data = $this->clean($data);
		$data = mysqli_real_escape_string($link, $data);
		return $data;
	}
}
?>