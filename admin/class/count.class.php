<?php
/*
 * Class library for counting functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Count
 * Description:		Functions to count data from database
 */
class Count
{
	function numCards( $stat, $worth )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$stat = $sanitize->for_db($stat);
		$worth = $sanitize->for_db($worth);

		if( empty( $stat ) )
		{
			$result = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_worth`='$worth'");
		}

		else if( empty( $worth ) )
		{
			$result = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_status`='$stat'");
		}

		else
		{
			$result = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_status`='$stat' AND `card_worth`='$worth'");
		}
		echo $result;
	}


	function countCards()
	{
		$database = new Database;
		$sanitize = new Sanitize;

		$result = $database->get_assoc("SELECT `card_worth`, SUM(card_count) FROM `tcg_cards`");
		$cardcount = $result['SUM(card_count)'];

		if( !$cardcount )
		{
			echo '0';
		}

		else
		{
			echo $cardcount;
		}
	}


	function numAll( $table, $stat, $prefix, $type )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$prefix = $sanitize->for_db($prefix);
		$table = $sanitize->for_db($table);
		$stat = $sanitize->for_db($stat);
		$type = $sanitize->for_db($type);

		if( empty( $stat ) )
		{
			$result = $database->num_rows("SELECT * FROM `$table`");
		}

		else if( empty( $type ) )
		{
			$result = $database->num_rows("SELECT * FROM `$table` WHERE `".$prefix."_status`='$stat'");
		}

		else
		{
			$result = $database->num_rows("SELECT * FROM `$table` WHERE `".$prefix."_status`='$stat' AND `".$prefix."_type`='$type'");
		}
		echo $result;
	}


	function numClaimed ( $type, $prefix )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$type = $sanitize->for_db($type);
		$prefix = $sanitize->for_db($prefix);

		$result = $database->num_rows("SELECT * FROM `".$prefix."_donations` WHERE `deck_type`='$type'");
		echo $result;
	}


	function numItems ( $item )
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$item = $sanitize->for_db($item);

		$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
		$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
		$player = $row['name'];

		$query = $database->query("SELECT * FROM `user_items` WHERE `itm_name` = '$player'");
		$row = mysqli_fetch_assoc($query);
		return $row[$item];
	}


	function numRewards()
	{
		$database = new Database;
		$sanitize = new Sanitize;

		$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
		$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
		$player = isset($row['usr_name']) ? $row['usr_name'] : null;

		$result = $database->num_rows("SELECT * FROM `user_rewards` WHERE `rwd_name`='$player'");

		if( $result == '0' )
		{
			echo '0';
		}

		else
		{
			echo $result;
		}
	}


	function numMail()
	{
		$database = new Database;
		$sanitize = new Sanitize;

		$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
		$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
		$player = isset($row['usr_name']) ? $row['usr_name'] : null;

		$result = $database->num_rows("SELECT * FROM `user_mbox` WHERE `msg_recipient`='$player' AND `msg_see_to`=1");

		if( $result == '0' )
		{
			echo '0';
		}

		else
		{
			echo $result;
		}
	}
}
?>