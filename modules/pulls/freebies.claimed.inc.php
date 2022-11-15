<?php
/************************************************
 * Module:			Freebies Claim
 * Description:		Process user-claimed freebies
 */


if( !isset($_SERVER['HTTP_REFERER']) )
{
	echo $ForbiddenAccess;
}

else
{
	if( !isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
	}

	else
	{
		$check->Value();
		$today = date("Y-m-d", strtotime("now"));
		$name = $sanitize->for_db($_POST['name']);
		$type = $sanitize->for_db($_POST['type']);
		$word = $sanitize->for_db($_POST['word']);
		$amount = $sanitize->for_db($_POST['amount']);

		$get = $database->get_assoc("SELECT * FROM `user_freebies` WHERE `free_id`='$id'");

		echo '<h1>Freebies #'.$get['free_id'].' ('.$get['free_date'].')</h1>
		<p>Your freebies pulls has been logged on your permanent logs, make sure to log it on your trade post as well.</p>
		<center>';

		// Do rewards depending on wish type
		if( $type == 1 )
		{
			$amount = strlen($word);
			for( $i = 0; $i < $amount; $i++ )
			{
				$card = "card$i";
				$card2 = "num$i";
				echo '<img src="'.$tcgcards.''.$_POST[$card].''.$_POST[$card2].'.'.$tcgext.'" />';
				$pulled .= $_POST[$card].$_POST[$card2].", ";
			}
		}

		else if( $type == 2 || $type == 3 || $type == 4 )
		{
			for( $i = 0; $i < $amount; $i++ )
			{
				$card = "card$i";
				$card2 = "num$i";
				echo '<img src="'.$tcgcards.''.$_POST[$card].''.$_POST[$card2].'.'.$tcgext.'" />';
				$pulled .= $_POST[$card].$_POST[$card2].", ";
			}
		}

		$rewards = substr_replace($pulled,"",-2);
		$text = '<strong>Freebies #'.$get['free_id'].' ('.$get['free_date'].'):</strong> '.$rewards;
		echo '<br /><strong>Freebies #'.$get['free_id'].' ('.$get['free_date'].'):</strong> '.$rewards;
		echo '</center>';

		$title = "Freebies #".$get['free_id'];
		$database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'$amount' WHERE `itm_name`='$name'");
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$name','Pulls','$title','(".$get['free_date'].")','$rewards','$today')");
		
		// Check if user has already commented
		$postSQL = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_type`='post' AND `post_date`='".$get['free_date']."'");
		$commSQL = $database->num_rows("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='".$postSQL['post_id']."' AND `comm_name`='$player'");
		if( $commSQL == 0 )
		{
			$database->query("INSERT INTO `tcg_post_comm` (`comm_post`,`comm_name`,`comm_text`,`comm_date`) VALUES ('".$postSQL['post_id']."','$player','$text','$today')");
		}
		else
		{
			$commALTER = $database->get_assoc("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='".$postSQL['post_id']."' AND `comm_name`='$player'");
			$newText = $commALTER['comm_text'].'<br /><br />'.$text;
			$database->query("UPDATE `tcg_post_comm` SET `comm_text`='$newText' WHERE `comm_post`='".$postSQL['post_id']."' AND `comm_name`='$player'");
		} // end comment checking
	}
}
?>