<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

// Check is user is logged in
if( empty( $login ) )
{
	header("Location: account.php?do=login");
}


// Begin module inclusions
$wish = $database->get_assoc("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_id`='$id'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='".$player."' AND `log_title`='Wishes #".$wish['wish_id']."' AND `log_subtitle`='(".$wish['wish_date'].")'");

if( empty( $go ) )
{
	@include($tcgpath.'modules/pulls/wishes.main.inc.php');
}

else
{
	@include($tcgpath.'modules/pulls/wishes.'.$go.'.inc.php');
}


@include($footer);
?>