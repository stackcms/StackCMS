<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

// Check is user is logged in
if( empty( $login ) ) {
	header("Location: account.php?do=login");
}

$date = isset($_GET['date']) ? $_GET['date'] : null;
$user = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
$logChk = $database->get_assoc("SELECT * FROM `user_logs` WHERE `log_name`='$player' AND `log_type`='Releases' AND `log_subtitle`='($date)'");


// Begin module inclusions
if( empty( $go ) ) {
	@include($tcgpath.'modules/pulls/releases.main.inc.php');
}

else {
	@include($tcgpath.'modules/pulls/releases.'.$go.'.inc.php');
}


@include($footer);
?>