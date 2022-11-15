<?php
/**********************************************
 * Module:			Wishes Claim
 * Description:		Process user-claimed wishes
 */


if( !isset($_SERVER['HTTP_REFERER']) )
{
    echo $ForbiddenAccess;
}

else
{
	if( !isset( $_POST['submit'] ) || $_SERVER['REQUEST_METHOD'] != "POST" )
	{
		exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
	}

	else
	{
		$today = date("Y-m-d", strtotime("now"));
		$name = $sanitize->for_db($_POST['name']);
		$type = $sanitize->for_db($_POST['type']);
		$word = $sanitize->for_db($_POST['word']);
		$amount = $sanitize->for_db($_POST['amount']);

		$get = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_id`='$id'");

		echo '<h1>Wishes #'.$get['wish_id'].' ('.$get['wish_date'].')</h1>
		<p>Your wish pulls has been logged on your permanent logs, make sure to log it on your trade post as well.</p>

		<center>';

		// Generate cards according to wish type
		if( $type == 1 )
		{
			$amount = strlen($word);
			for( $i = 0; $i < $amount; $i++ )
			{
				$cardImg = "card$i";
				$cardImg2 = "num$i";
				echo '<img src="'.$tcgcards.''.$_POST[$cardImg].''.$_POST[$cardImg2].'.png" />';
				$pulled .= $_POST[$cardImg].$_POST[$cardImg2].", ";
			}
		}

		else if( ($type == 2) || ($type == 3) || ($type == 4) )
		{
			for( $i = 1; $i <= $amount; $i++ )
			{
				$cardImg = "card$i";
				$cardImg2 = "num$i";
				echo '<img src="'.$tcgcards.''.$_POST[$cardImg].''.$_POST[$cardImg2].'.png" />';
				$pulled .= $_POST[$cardImg].$_POST[$cardImg2].", ";
			}
		}
		$rewards = substr_replace($pulled,"",-2);
		echo '<br /><strong>Wishes #'.$get['wish_id'].' ('.$get['wish_date'].'):</strong> '.$rewards;
		echo '</center>';

		// Insert acquired data
		$title = "Wishes #".$get['wish_id'];
		$text = '<strong>Wishes #'.$get['wish_id'].' ('.$get['wish_date'].'):</strong> '.$rewards;
		$database->query("UPDATE `user_items` SET `itm_cards`=itm_cards+'$amount' WHERE `itm_name`='$name'");
		$database->query("INSERT INTO `user_logs` (`log_name`,`log_type`,`log_title`,`log_subtitle`,`log_rewards`,`log_date`) VALUES ('$name','Pulls','$title','(".$get['wish_date'].")','$rewards','$today')");
		
		// Check if user has already commented
		$postSQL = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_type`='post' AND `post_date`='".$get['wish_date']."'");
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