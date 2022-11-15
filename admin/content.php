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
	if( empty( $mod ) )
	{
		@include($tcgpath.'admin/vendor/content/index.php');
	}

	else
	{
		// Condition if mod has a page
		if( empty( $page ) )
		{
			if( empty( $act ) )
			{
				@include($tcgpath.'admin/vendor/content/'.$mod.'.inc.php');
			}

			else
			{
				@include($tcgpath.'admin/vendor/content/action/'.$mod.'.'.$act.'.php');
			}
		}

		else
		{
			if( empty( $act ) )
			{
				@include($tcgpath.'admin/vendor/content/page/'.$mod.'.'.$page.'.php');
			}

			else
			{
				@include($tcgpath.'admin/vendor/content/action/'.$page.'.'.$act.'.php');
			}
		}
	}
}

@include($tcgpath.'admin/theme/footer.php');
?>