<?php
/******************************************************
 * Action:			Approve User Wishes
 * Description:		Show page for approving user wishes
 */


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo "<p>This page shouldn't be accessed directly! Please go back and try something else.</p>";
}

else
{
	echo '<h1>Approve a User Wish</h1>';
	date_default_timezone_set( $settings->getValue('tcg_timezone') );
	$timestamp = date('Y-m-d');

	$update = $database->query("UPDATE `user_wishes` SET `wish_date`='$timestamp', `wish_status`='Granted' WHERE `wish_id`='$id'");

	if( !$update )
	{
		echo '<p>Sorry, there was an error and the wish was not granted.</p> '.mysqli_error($update);
	}

	else
	{
		$get = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_id`='$id'");
		echo '<p>You have just granted a wish submitted by '.$get['wish_name'].'.';
	}
}
?>