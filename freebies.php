<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

// Check is user is logged in
if( empty( $login ) )
{
	header("Location: account.php?do=login");
}

$user = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
$free = $database->get_assoc("SELECT * FROM `user_freebies` WHERE `free_id`='$id'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='".$user['usr_name']."' AND `title`='Freebies #".$free['free_id']."' AND `subtitle`='(".$free['free_date'].")'");


// Begin module inclusions
if( empty( $go ) )
{
	@include($tcgpath.'modules/pulls/freebies.main.inc.php');
}

else
{
	@include($tcgpath.'modules/pulls/freebies.'.$go.'.inc.php');
}


@include($footer);
?>