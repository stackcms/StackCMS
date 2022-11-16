<?php
/*****************************************************
 * Module:			Uploads
 * Description:		Show main page of uploading images
 */


$img = (isset($_FILES['img']) ? $_FILES['img'] : null);
$img2 = (isset($_FILES['img']) ? $_FILES['img'] : null);
$file = (isset($_GET['name']) ? $_GET['name'] : null);

if( isset( $_POST['submit'] ) )
{
	if( !empty( $img ) )
	{
		$imgtype = $sanitize->for_db($_POST['upload']);
		$img_desc = $upload->reArrayFiles($img);
		$abpath = $settings->getValue('file_path_absolute');

		foreach( $img_desc as $val )
		{
			$newname = $file['name'];
			if ($imgtype=="cards") { $path = $abpath."images/cards/"; }
			else if ($imgtype=="shop-items") { $path = $abpath."shoppe/images/"; }
			else if ($imgtype=="badges") { $path = $abpath."images/badges/"; }
			else if ($imgtype=="affiliates") { $path = $abpath."images/aff/"; }
			else if ($imgtype=="images") { $path = $abpath."images/"; }
			else if ($imgtype=="game-rounds") { $path = $abpath."admin/games/rounds/"; }
			else if ($imgtype=="game-answer") { $path = $abpath."admin/games/answer/"; }
			else if (empty($imgtype)) {
				echo '<p>You did not select what type of custom image you are trying to upload.</p>';
			}
			move_uploaded_file($val['tmp_name'],$path.$val['name']);
		}
		$success[] = "Your image has been successfully uploaded to the website.";
	}

	else
	{
		$error[] = "Sorry, there was an error and the image was not uploaded.";
	}
}

echo '<h1>Upload Custom Images</h1>
<p>This page consists of sub categories regarding images that needs to be uploaded (either one image or per batch up to 20 files). Kindly choose which type of custom image you\'d like to upload or replace.</p>

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

<div class="box" style="width: 600px;">
<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'" multipart="" enctype="multipart/form-data">
	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Image Type</b></span>
		</div>
		<select name="upload" class="form-control">
			<option>----- Choose custom image type -----</option>
			<option value="cards">Cards / Mastery Badges</option>
			<option value="badges">Level Badges</option>
			<option value="affiliates">Affiliates</option>
			<option value="game-rounds">Game Clues</option>
			<option value="game-answer">Game Answers</option>
			<option value="shop-items">Shop Items</option>
			<option value="images">General Images</option>
		</select>
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Upload Image</b></span>
		</div>
		<div class="custom-file">
			<input type="file" name="img[]" class="custom-file-input" id="inputGroupFile01" multiple>
			<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
		</div>
	</div>

	<input type="submit" name="submit" class="btn btn-success" value="Upload Custom Images" />
</form>
</div>';

function reArrayFiles( $file )
{
	$file_ary = array();
	$file_count = count($file['name']);
	$file_key = array_keys($file);

	for( $i=0;$i<$file_count;$i++ )
	{
		foreach( $file_key as $val )
		{
			$file_ary[$i][$val] = $file[$val][$i];
		}
	}
	return $file_ary;
}
?>