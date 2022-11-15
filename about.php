<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);

$p = isset( $_GET['p'] ) ? $_GET['p'] : null;

if( empty( $p ) )
{
	$sql = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_id`='2' AND `post_status`='Published' AND `post_type`='page'");
	if( empty( $sql ) )
	{
		echo '<h1>Missing Content!</h1>
		<p>It appears that you haven\'t created a content for this page yet. You can add a simple information page from the admin panel via Admin > New Page Content.</p>';
	}
	
	else
	{
		echo '<h1>'.$sql['post_title'].'</h1>';
		$con = $sql['post_content'];
		eval('?>'.$con.'');
	}
}

else
{
	$sql = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_slug`='$p' AND `post_parent`='2' AND `post_status`='Published' AND `post_type`='page'");
	if( empty( $sql ) )
	{
		echo '<h1>Missing Content!</h1>
		<p>It appears that you haven\'t created a content for this page yet. You can add a simple information page from the admin panel via Admin > New Page Content.</p>';
	}
	
	else
	{
		echo '<h1>'.$sql['post_title'].'</h1>';
		$con = $sql['post_content'];
		eval('?>'.$con.'');
	}
}

@include($footer);
?>