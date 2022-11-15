<?php
/***********************************************
 * Module:			Releases Pull
 * Description:		Process user-pulled releases
 */


if( isset( $_POST['submit'] ) )
{
	$today = date("Y-m-d", strtotime("now"));
	$mamt = $sanitize->for_db($_POST['maker_amount']);
	$damt = $sanitize->for_db($_POST['donator_amount']);
	$get = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_type`='post' AND `post_date`='$date'");

	echo '<h1>Update Pulls ('.$date.')</h1>
	<p>Your pulls has been logged on your permanent logs, make sure to log it on your trade post as well.</p>
	<center>';

	for( $i = 1; $i <= $get['post_amount']; $i++ )
	{
		$pcard = "pull$i";
		$pcard2 = "pullnum$i";
		echo '<img src="'.$tcgcards.''.$_POST[$pcard].''.$_POST[$pcard2].'.png" />';
		$pulled .= $_POST[$pcard].$_POST[$pcard2].", ";
	}
	$pulled = substr_replace($pulled,"",-2);
	$text = '<strong>Deck Release ('.$date.'):</strong> '.$pulled;
	echo '<br /><strong>Deck Release ('.$date.'):</strong> '.$pulled;

	$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Releases','Deck Release','($date)','$pulled','$today')");
	$database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'".$get['post_amount']."' WHERE `itm_name`='$player'");

	// Check if user has already commented
	$commSQL = $database->num_rows("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='".$get['post_id']."' AND `comm_name`='$player'");
	if( $commSQL == 0 )
	{
		$database->query("INSERT INTO `tcg_post_comm` (`comm_post`,`comm_name`,`comm_text`,`comm_date`) VALUES ('".$get['post_id']."','$player','$text','$today')");
	}
	else
	{
		$commALTER = $database->get_assoc("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='".$get['post_id']."' AND `comm_name`='$player'");
		$newText = $commALTER['comm_text'].'<br /><br />'.$text;
		$database->query("UPDATE `tcg_post_comm` SET `comm_text`='$newText' WHERE `comm_post`='".$get['post_id']."' AND `comm_name`='$player'");
	} // end comment checking

	// Check for donator pulls
	if( !empty( $_POST['donator1'] ) )
	{
		echo '<br /><br />';
		for( $i = 1; $i <= $damt; $i++ )
		{
			$dcard = "donator$i";
			$dcard2 = "donatornum$i";
			echo '<img src="'.$tcgcards.''.$_POST[$dcard].''.$_POST[$dcard2].'.png" />';
			$donated .= $_POST[$dcard].$_POST[$dcard2].", ";
		}
		$donated = substr_replace($donated,"",-2);
		echo '<br /><strong>Donator Pull ('.$date.'):</strong> '.$donated;
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Pulls','Donator Pull','($date)','$donated','$today')");
		$database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'$damt' WHERE `itm_name`='$player'");
	} // end donator pull check

	// Check for maker pulls
	if( !empty( $_POST['maker1'] ) )
	{
		echo '<br /><br />';
		for( $i = 1; $i <= $mamt; $i++ )
		{
			$mcard = "maker$i";
			$mcard2 = "makernum$i";
			echo '<img src="'.$tcgcards.''.$_POST[$mcard].''.$_POST[$mcard2].'.png" />';
			$made .= $_POST[$mcard].$_POST[$mcard2].", ";
		}
		$made = substr_replace($made,"",-2);
		echo '<br /><strong>Maker Pull ('.$date.'):</strong> '.$made;
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$player','Pulls','Maker Pull','($date)','$made','$today')");
		$database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'$mamt' WHERE `itm_name`='$player'");
	} // end maker pull check
} // end form process
?>