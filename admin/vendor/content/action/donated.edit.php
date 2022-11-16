<?php
/*******************************************************
 * Action:			Edit Donated Decks
 * Description:		Show page for editing a donated deck
 */


// Process edit a donated deck form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$donator = $sanitize->for_db($_POST['donator']);
	$maker = $sanitize->for_db($_POST['maker']);
	$filename = $sanitize->for_db($_POST['filename']);
	$deckname = $sanitize->for_db($_POST['deckname']);
	$cat = $sanitize->for_db($_POST['category']);
	$set = $sanitize->for_db($_POST['set']);
	$url = $sanitize->for_db($_POST['url']);

	$update = $database->query("UPDATE `tcg_donations` SET `deck_donator`='$donator', `deck_maker`='$maker', `deck_filename`='$filename', `deck_feature`='$deckname', `deck_cat`='$cat', `deck_set`='$set', `deck_url`='$url' WHERE `deck_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the donated deck was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The donated deck has been updated from the database!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit a donated deck form
else
{
	$row = $database->get_assoc("SELECT * FROM `tcg_donations` WHERE `deck_id`='$id'");
	echo '<p>Use this form to edit an existing donated deck.<br />
	If you want to claim a donated deck to make, use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">claim form</a> instead.</p>

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

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col">
			<div class="box">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="bi-card-image" role="image"></i></span>
					</div>
					<input type="text" name="deckname" class="form-control" value="'.$row['deck_feature'].'">
					<input type="text" name="filename" class="form-control" value="'.$row['deck_filename'].'">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Download URL</b></span>
					</div>
					<input type="text" name="url" class="form-control" value="'.$row['deck_url'].'">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="bi-people-fill" role="image"></i></span>
					</div>
					<input type="text" name="maker" class="form-control" value="'.$row['deck_maker'].'">
					<input type="text" name="donator" class="form-control" value="'.$row['deck_donator'].'">
				</div>

				<div class="row">
					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="category"><b>Category</b></span>
							</div>
							<select name="category" class="form-control" aria-label="Category" aria-describedby="category">';
							$c = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['deck_cat']."'");
							echo '<option value="'.$c['cat_id'].'">Current: '.$c['cat_name'].'</option>';
							$cat = $database->query("SELECT * FROM `tcg_cards_cat` ORDER BY `cat_name` ASC");
							while( $get = mysqli_fetch_assoc( $cat ) )
							{
								echo '<option value="'.$get['cat_id'].'">'.$get['cat_name'].'</option>';
							}
							echo '</select>
						</div>
					</div><!-- .col -->

					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="set"><b>Set/Series</b></span>
							</div>
							<select name="set" class="form-control" aria-label="Set/Series" aria-describedby="set">';
							$s = $database->get_assoc("SELECT * FROM `tcg_cards_set` WHERE `set_name`='".$row['deck_set']."'");
							echo '<option value="'.$s['set_name'].'">Current: '.$row['deck_set'].'</option>';
							$set = $database->query("SELECT * FROM `tcg_cards_set` ORDER BY `set_name` ASC");
							while( $get = mysqli_fetch_assoc( $set ) )
							{
								echo '<option value="'.$get['set_id'].'">'.$get['set_name'].'</option>';
							}
							echo '</select>
						</div>
					</div><!-- .col -->
				</div><!-- .row -->

				<input type="submit" name="update" class="btn btn-success" value="Update Donation" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div><!-- .box -->
		</div><!-- .col -->
	</div><!-- .row -->
	</form>';
}
?>