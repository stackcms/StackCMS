<?php
/************************************************************
 * Page:			Members Main
 * Description:		Show main page of members list and status
 */


// Process mass hiatus form
if( isset( $_POST['mass-hiatus'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$hiatus = $database->query("UPDATE FROM `user_list` SET `usr_status`='Hiatus' WHERE `user_id`='$id'");
	}

	if( !$hiatus )
	{
		$error[] = "Sorry, there was an error and the members were not put to Hiatus. ".mysqli_error($hiatus)."";
	}

	else
	{
		$success[] = "The members were put to Hiatus successfully!";
	}
}

// Process mass inactive form
if( isset( $_POST['mass-inactive'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$inactive = $database->query("UPDATE FROM `user_list` SET `usr_status`='Inactive' WHERE `user_id`='$id'");
	}

	if( !$inactive )
	{
		$error[] = "Sorry, there was an error and the members were not put to Inactive. ".mysqli_error($inactive)."";
	}

	else
	{
		$success[] = "The members were put to Inactive successfully!";
	}
}

// Process mass retired form
if( isset( $_POST['mass-retired'] ) )
{
	$getID = $_POST['id'];
	$date = date("Y-m-d", strtotime("now"));
	foreach( $getID as $id )
	{
		// Fetch data first and add to retired list
		$sql = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='$id'");
		$insert = $database->query("INSERT INTO `user_list_quit` (`usr_name`,`usr_mcard`,`usr_joined`,`usr_quit`) VALUES ('".$sql['usr_name']."','mc-".$sql['usr_name']."','".$sql['usr_reg']."','$date'");

		// Delete necessary rows from user-dependent tables
		$database->query("DELETE FROM `user_items` WHERE `itm_name`='".$sql['usr_name']."'");
		$database->query("DELETE FROM `user_logs` WHERE `log_name`='".$sql['usr_name']."'");
		$database->query("DELETE FROM `user_trades` WHERE `trd_name`='".$sql['usr_name']."'");
		$database->query("DELETE FROM `user_trades_rec` WHERE `trd_name`='".$sql['usr_name']."'");

		// Delete from main user list table
		$retired = $database->query("DELETE FROM `user_list` WHERE `usr_id`='$id'");
	}

	if( !$insert && !$retired )
	{
		$error[] = "Sorry, there was an error and the members were not deleted. ".mysqli_error($insert)." ".mysqli_error($retired);
	}

	else
	{
		$success[] = "The members were deleted successfully and has been put to the Retired list!";
	}
}

// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `user_list` WHERE `usr_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the members were not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The members were deleted successfully!";
	}
}

echo '<h1>Members Administration</h1>
<p>&raquo; Need to email <a href="'.$tcgurl.'admin/people.php?mod=members&action=email-all">all members</a>?</p>

<center>';
if( isset( $error ) )
{
	foreach( $error as $msg )
	{
		echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
	}
}

if( isset( $success ) )
{
	foreach( $success as $msg )
	{
		echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<div class="box">
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" href="#active" data-toggle="tab" role="tab" aria-controls="active" aria-selected="true">Active</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#pending" data-toggle="tab" role="tab" aria-controls="pending" aria-selected="false">Pending</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#hiatus" data-toggle="tab" role="tab" aria-controls="hiatus" aria-selected="false">Hiatus</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#inactive" data-toggle="tab" role="tab" aria-controls="inactive" aria-selected="false">Inactive</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#retired" data-toggle="tab" role="tab" aria-controls="retired" aria-selected="false">Retired</a>
		</li>
	</ul>

	<div class="tab-content" id="myTabContent">
		<div id="active" class="tab-pane fade show active" role="tabpanel" aria-labelledby="active-tab">';
			@include($tcgpath.'admin/vendor/people/tabs/active.tab.php');
		echo '</div><!-- #active -->

		<div id="pending" class="tab-pane fade" role="tabpanel" aria-labelledby="pending-tab">';
			@include($tcgpath.'admin/vendor/people/tabs/pending.tab.php');
		echo '</div><!-- #pending -->

		<div id="hiatus" class="tab-pane fade" role="tabpanel" aria-labelledby="hiatus-tab">';
			@include($tcgpath.'admin/vendor/people/tabs/hiatus.tab.php');
		echo '</div><!-- #hiatus -->

		<div id="inactive" class="tab-pane fade" role="tabpanel" aria-labelledby="inactive-tab">';
			@include($tcgpath.'admin/vendor/people/tabs/inactive.tab.php');
		echo '</div><!-- #inactive -->

		<div id="retired" class="tab-pane fade" role="tabpanel" aria-labelledby="retired-tab">';
			@include($tcgpath.'admin/vendor/people/tabs/retired.tab.php');
		echo '</div><!-- #retired -->
	</div><!-- tab-content -->
</div><!-- box -->';
?>