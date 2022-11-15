<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);
@include($tcgpath.'themes/headers/msg-header.php');

$to = isset($_GET['to']) ? $_GET['to'] : null;

// Check if user is logged in
if( empty( $login ) )
{
	header("Location: account.php?do=login");
}


// Check if user directly accesses a page
if( empty( $id ) )
{
	echo '<h1>Oops?</h1>
	<p>It seems like you\'re trying to access a page directly! Please go back and click the correct link.</p>';
}

// Begin module inclusions
else
{
	if( empty( $page ) )
	{
		@include($tcgpath.'modules/members/messages.main.inc.php');
	}

	else {
		@include($tcgpath.'modules/members/messages.'.$page.'.inc.php');
	}
}

@include($tcgpath.'themes/headers/msg-footer.php');
@include($footer);
?>