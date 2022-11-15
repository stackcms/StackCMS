<?php
/******************************************
 * Module:			Edit Items
 * Description:		Process edit user items
 */


if( empty( $login ) )
{
	header( "Location: account.php?do=login" );
}

else
{
	if( isset($_POST['update']) )
	{
		$name = $sanitize->for_db($_POST['name']);
		$mcard = $sanitize->for_db($_POST['mcard']);
		$ecard = $sanitize->for_db($_POST['ecard']);
		$lvlb = $sanitize->for_db($_POST['lvlb']);

		function trim_value(&$value) { $value = trim($value); }
		$mcard = explode(',',$mcard);
		$ecard = explode(',',$ecard);

		array_walk($mcard, 'trim_value');
		array_walk($ecard, 'trim_value');

		usort($mcard, 'strnatcasecmp');
		sort($ecard);

		$mcard = implode(', ',$mcard);
		$ecard = implode(', ',$ecard);

		$update = $database->query("UPDATE `user_items` SET `itm_mcard`='$mcard', `itm_ecard`='$ecard', `itm_badge`='$lvlb' WHERE `itm_name`='$name'");

		if( !$update )
		{
			$error[] = "Sorry, there was an error and your items was not updated. ".mysqli_error($update)."";
		}
		else
		{
			$success[] = "Your items has been updated!";
		}
	}

	$row = $database->get_assoc("SELECT * FROM `user_items` WHERE `itm_name`='$player'");
	echo '<h1>Edit Your Items</h1>
	<p>Use this form to edit your items in the database. <b>You can only add or remove member and event cards via this form.</b> Please make sure to add only the cards you\'ve gained in a comma-separated format. Your mastered decks and milestones can only be edited by an administrator.</p>

	<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="box-error"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="box-success"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'account.php?do='.$do.'">
	<input type="hidden" name="name" value="'.$row['itm_name'].'" />
	<table width="100%" class="table table-sliced table-striped">
	<tbody>
	<tr>
		<td width="20%"><b>Member Cards:</b></td>
		<td width="80%"><textarea name="mcard" rows="5" style="width: 95%;">'.$row['itm_mcard'].'</textarea></td>
	</tr>
	<tr>
		<td><b>Event Cards:</b></td>
		<td><textarea name="ecard" rows="5" style="width: 95%;">'.$row['itm_ecard'].'</textarea></td>
	</tr>
	<tr>
		<td><b>Level Badge:</b></td>
		<td>
			<select name="lvlb" style="width:95%;">';
			if( $row['itm_badge'] == "" )
			{
				echo '<option value="">Select a Level Badge</option>';
			}
			else
			{
				echo '<option value="'.$row['itm_badge'].'">'.$row['itm_badge'].'</option>';
			}
			$num = $database->num_rows("SELECT * FROM `tcg_levels_badge`");
			for( $i=1; $i<=$num; $i++ )
			{
				$lb = $database->get_assoc("SELECT * FROM `tcg_levels_badge` WHERE `badge_id`='$i'");
				echo '<option value="'.$lb['badge_set'].'">'.$lb['badge_name'].' ('.$lb['badge_set'].')</option>';
			}
			echo '</select>
		</td>
	</tr>
	</tbody></table>
	<input type="submit" name="update" class="btn-success" value="Edit Items" />
	</form>';
}
?>