<?php
/******************************************************
 * Action:			Edit Level Badges
 * Description:		Show page for editing a level badge
 */


// Process edit a level badge form
if( isset( $_POST['edit'] ) )
{
	$id = $sanitize->for_db($_POST['id']);
	$name = $sanitize->for_db($_POST['name']);
	$levels = $sanitize->for_db($_POST['levels']);
	$height = $sanitize->for_db($_POST['height']);
	$width = $sanitize->for_db($_POST['width']);
	$feat = $sanitize->for_db($_POST['feature']);
	$set = $sanitize->for_db($_POST['set']);

	$update = $database->query("UPDATE `tcg_levels_badge` SET `badge_name`='$name', `badge_set`='$set', `badge_level`='$levels', `badge_width`='$width', `badge_height`='$height', `badge_feature`='$feat' WHERE `badge_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the level badge was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The level badges was successfully updated.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit a level badge form
else
{
	echo '<h1>Edit a Level Badge</h1>
	<p>Use this form to edit a level badge in the database.<br />
	Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add new level badges.</p>

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

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />';

	$row = $database->get_assoc("SELECT * FROM `tcg_levels_badge` WHERE `badge_id`='$id'");
	echo '<div class="box" style="width: 600px;">
		<div class="row">
			<div class="col-4"><b>Donator:</b></div>
			<div class="col">
				<select name="name" class="form-control">
					<option value="'.$row['badge_name'].'">Current: '.$row['badge_name'].'</option>
					<option>----- Select player -----</option>';
					$sql = $database->query("SELECT `usr_name` FROM `user_list` WHERE `usr_status`='Active' ORDER BY `usr_name`");
					while( $row1 = mysqli_fetch_assoc( $sql ) )
					{
						echo '<option value="'.$row1['usr_name'].'">'.$row1['usr_name'].'</option>';
					}
				echo '</select><br />
				<small><i>Badge set which includes the donator\'s name and the iteration of donated set.</i></small><br />
				<input type="text" name="set" value="'.$row['badge_set'].'" class="form-control" />
			</div>
		</div><br />

		<div class="row">
			<div class="col-4"><b>Featuring:</b></div>
			<div class="col"><input type="text" name="feature" value="'.$row['badge_feature'].'" class="form-control" /></div>
		</div><br />

		<div class="row">
			<div class="col-4"><b>Badge size:</b></div>
			<div class="col">
				<div class="input-group mb-3">
					<input type="text" name="width" value="'.$row['badge_width'].'" class="form-control" />
					<input type="text" name="height" value="'.$row['badge_height'].'" class="form-control" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-4"><b>Levels:</b></div>
			<div class="col"><input type="text" name="levels" value="'.$row['badge_level'].'" class="form-control" /></div>
		</div>

		<input type="submit" name="edit" class="btn btn-success" value="Edit Badge" />
	</div><!-- box -->
	</form>';
}
?>