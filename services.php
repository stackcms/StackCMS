<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);
@include($tcgpath.'themes/headers/acct-header.php');


// Check is user is logged in
if( empty( $login ) ) {
	header("Location: account.php?do=login");
}


// Begin module inclusions
if( empty( $form ) ) {
	header("Location: account.php");
}

else {
	$result = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_worth`='1'") or die("Unable to select from database.");
	@include($tcgpath.'modules/services/'.$form.'.inc.php');
}


@include($tcgpath.'themes/headers/acct-footer.php');
@include ($footer);
?>