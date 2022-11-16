<?php
/******************************************************
 * Action:			Reactivate Members
 * Description:		Show page for reactivating a member
 */


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Process reactivation form
else {
	$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
	$activate = $database->query("UPDATE `user_list` SET `usr_status`='Active' WHERE `usr_id`='$id'");

	if( $activate )
    {
		echo '<p>'.$row['usr_name'].' has been successfully reactivated in the database.</p>';
	}

	else
    {
		echo '<p>Sorry, there was an error and '.$row['usr_name'].'\'s status was not updated in the database. Please send them an email to let them know they have been reactivated and use the edit form from the <a href="'.$tcgurl.'admin/members.php">members</a> page to update their status.</p>';
	}
}
?>