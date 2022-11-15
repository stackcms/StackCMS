<?php
@include('class.lib.php');
@include($tcgpath.'admin/theme/header.php');

// Check if user is logged in
if( empty( $login ) )
{
	header('Location: '.$tcgurl.'account.php?do=login');
}


// Check user role before proceeding with module inclusions
if( $row['usr_role'] == 7 )
{
	header('Location: '.$tcgurl.'account.php');
}

else
{
	if( empty( $mod ) ) {
		@include($tcgpath.'admin/vendor/shoppe/index.php');
	}

	else {
		@include($tcgpath.'admin/vendor/shoppe/'.$mod.'.inc.php');
	}
}

@include($tcgpath.'admin/theme/footer.php');
?>