<?php
/****************************************************
 * Page:			Affiliates
 * Description:		Show main page of affiliates list
 */


// Mass hiatus affiliates
if( isset( $_POST['mass-hiatus'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$hiatus = $database->query("UPDATE `tcg_affiliates` SET `aff_status`='Hiatus' WHERE `aff_id`='$id'");
	}

	if( !$hiatus )
	{
		$error[] = "Sorry, there was an error and the selected affiliates were not set to Hiatus. ".mysqli_error($hiatus)."";
	}

	else
	{
		$success[] = "The selected affiliates has been set to Hiatus!";
	}
}


// Mass inactive affiliates
if( isset( $_POST['mass-inactive'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$inactive = $database->query("UPDATE `tcg_affiliates` SET `aff_status`='Inactive' WHERE `aff_id`='$id'");
	}

	if( !$inactive )
	{
		$error[] = "Sorry, there was an error and the selected affiliates were not set to Inactive. ".mysqli_error($inactive)."";
	}

	else
	{
		$success[] = "The selected affiliates has been set to Inactive!";
	}
}


// Mass close affiliates
if( isset( $_POST['mass-closed'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$closed = $database->query("UPDATE `tcg_affiliates` SET `aff_status`='Closed' WHERE `aff_id`='$id'");
	}

	if( !$closed )
	{
		$error[] = "Sorry, there was an error and the selected affiliates were not set to Closed. ".mysqli_error($closed)."";
	}

	else
	{
		$success[] = "The selected affiliates has been set to Closed!";
	}
}


// Mass delete affiliates
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_affiliates` WHERE `aff_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the selected affiliates were not deleted from the database. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The selected affiliates has been deleted successfully!";
	}
}


// Mass approve affiliates
if( isset( $_POST['mass-approve'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$row = $database->get_assoc("SELECT * FROM `tcg_affiliates` WHERE `aff_id`=$id");
		$update = $database->query("UPDATE `tcg_affiliates` SET `aff_status`='Active' WHERE `aff_id`='$id'");

		// Send email if all queries are correct
		if( $update === TRUE )
		{
			if( function_exists( 'mail' ) )
			{
				$recipient = $row['aff_email'];
				$subject = $tcgname.': Affiliate Approved!';

				$message = "Thank you for affiliating with $tcgname! Your application has been approved.\n\n";
				$message .= "-- $tcgowner\n";
				$message .= "$tcgname: $tcgurl\n";
					
				$headers = "From: $tcgname <$tcgemail> \n";
				$headers .= "Reply-To: $tcgname <$tcgemail>";

				if( mail($recipient,$subject,$message,$headers) )
				{
					$activity = '<span class="fas fa-globe" aria-hidden="true"></span> <a href="'.$row['aff_url'].'" target="_blank">'.$row['aff_subject'].' TCG</a> has been added as '.$tcgname.'\'s new affiliate.';
					
					$date = date("Y-m-d", strtotime("now"));
					$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_date`) VALUES ('$name','$activity','$date')");

					$success[] = "The selected affiliates have been successfully emailed and has been updated in the database.";
				}

				else
				{
					$error[] = "Sorry, there was an error and the email could not be sent to the selected affiliates. However, they have been updated in the database. Please send them an email to let them know they have been approved and use the edit form from the <a href=\"".$tcgurl."admin/affiliates.php\">affiliates</a> page to update their status.";
				}
			}

			else
			{
				$activity = '<span class="fas fa-globe" aria-hidden="true"></span> <a href="'.$row['aff_url'].'" target="_blank">'.$row['aff_subject'].' TCG</a> has been added as '.$tcgname.'\'s new affiliate.';
					
				$date = date("Y-m-d", strtotime("now"));
				$database->query("INSERT INTO `tcg_activities` (`act_name`,`act_rec`,`act_date`) VALUES ('$name','$activity','$date')");

				$email = $row['aff_email'];
				$name = $row['aff_owner'];
				$subject = $tcgname.':. Affiliate Approved!';

				$message = "Thank you for affiliating with $tcgname! Your application has been approved.<br /><br />
					-- $tcgowner<br />
					$tcgname: $tcgurl";
				@include($tcgpath.'admin/mail/index.php');

				$success[] = "The selected affiliates have been successfully emailed and has been updated in the database.";
			}
		}

		else
		{
			$error[] = "The affiliates have been successfully emailed but has not been updated in the database. Please use the edit form from the <a href=\"".$tcgurl."admin/affiliates.php?\">affiliates</a> page to update their status.";
		}
	}
}


echo '<h1>Affiliates</h1>
<p>&raquo; Need to email <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&action=email-all">all affiliates</a>?</p>

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
		<a class="nav-link" href="#closed" data-toggle="tab" role="tab" aria-controls="closed" aria-selected="false">Closed</a>
	</li>
</ul>

<div class="tab-content" id="myTabContent">
	<div id="active" class="tab-pane fade show active" role="tabpanel" aria-labelledby="active-tab">
		<h2>Active Affiliates</h2>
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
		echo '</center>';

		$admin->affiliates('Active');
	echo '</div><!-- #active -->

	<div id="pending" class="tab-pane fade" role="tabpanel" aria-labelledby="pending-tab">
		<h2>Pending Affiliates</h2>
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
		echo '</center>';

		$admin->affiliates('Pending');
	echo '</div><!-- #pending -->

	<div id="hiatus" class="tab-pane fade" role="tabpanel" aria-labelledby="hiatus-tab">
		<h2>Hiatus Affiliates</h2>
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
		echo '</center>';

		$admin->affiliates('Hiatus');
	echo '</div><!-- #hiatus -->

	<div id="inactive" class="tab-pane fade" role="tabpanel" aria-labelledby="inactive-tab">
		<h2>Inactive Affiliates</h2>
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
		echo '</center>';

		$admin->affiliates('Inactive');
	echo '</div><!-- #inactive -->

	<div id="closed" class="tab-pane fade" role="tabpanel" aria-labelledby="closed-tab">
		<h2>Closed Affiliates</h2>
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
		echo '</center>';

		$admin->affiliates('Closed');
	echo '</div><!-- #closed -->
</div><!-- tab-content -->
</div><!-- box -->';
?>