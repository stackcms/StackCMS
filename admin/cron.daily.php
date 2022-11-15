<?php
@include('class.lib.php');

$database = new Database;
$sanitize = new Sanitize;

// SET USER TO INACTIVE AFTER XX DAYS (default is 60 days)
$database->query("UPDATE `user_list` SET `usr_status`='Inactive' WHERE `usr_sess` < CURDATE() - INTERVAL 60 DAY");

// PUBLISH ANY SCHEDULED POSTS
$count = $database->num_rows("SELECT *,DATE(post_date) FROM `tcg_blog` WHERE DATE(`post_date`) = CURDATE()");
$sql = $database->get_assoc("SELECT *,DATE(post_Date) FROM `tcg_blog` WHERE DATE(`post_date`) = CURDATE()");
if( $count != 0 )
{
	$database->query("UPDATE `tcg_blog` SET `post_status`='Published' WHERE `post_id` = '".$sql['post_id']."'");
}
?>
