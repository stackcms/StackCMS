<?php
/****************************************************
 * Action:			Add Level Badges
 * Description:		Show page for adding level badges
 */


$img = (isset($_FILES['img']) ? $_FILES['img'] : null);
$file = (isset($_GET['name']) ? $_GET['name'] : null);

if( isset( $_POST['submit'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$levels = $sanitize->for_db($_POST['levels']);
	$height = $sanitize->for_db($_POST['height']);
	$width = $sanitize->for_db($_POST['width']);
	$feat = $sanitize->for_db($_POST['feature']);
		
	if( $_POST['setnum'] < 10 )
	{
		$num = "0".$_POST['setnum'];
	}

	else
	{
		$num = $_POST['setnum'];
	}

	$set = $_POST['set'].''.$num;
	$img_desc = $upload->reArrayFiles($img);
	$upload->folderPath('images','badges');

	$insert = $database->query("INSERT INTO `tcg_levels_badge` (`badge_name`,`badge_set`,`badge_level`,`badge_width`,`badge_height`,`badge_feature`) VALUES ('$name','$set','$levels','$width','$height','$feat')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the level badge was not added. ".mysqli_error($insert);
	}

	else
	{
		$success[] = "The new level badge was successfully added to the database!";
	}
}

echo '<h1>Add a Level Badge</h1>
<p>Use this form to add a new level badge to the database.<br />
Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for an existing level badge.</p>

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

<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'" multipart="" enctype="multipart/form-data">
<div class="box" style="width: 600px;">
	<div class="row">
		<div class="col-4"><b>Donator:</b></div>
		<div class="col">
			<select name="name" class="form-control">
				<option>-- Select Player --</option>';
				$sql = $database->query("SELECT * FROM `user_list` WHERE `usr_status`='Active' ORDER BY `usr_name`");
				while( $row = mysqli_fetch_assoc( $sql ) )
				{
					echo '<option value="'.$row['usr_name'].'">'.$row['usr_name'].'</option>';
				}
			echo '</select><br />
			<small><i>Badge set which includes the donator\'s name and the iteration of donated set.</i></small>
			<div class="input-group mb-3">
				<select name="set" class="form-control">
					<option>-- Select Player --</option>';
					$sql = $database->query("SELECT * FROM `user_list` WHERE `usr_status`='Active' ORDER BY `usr_name`");
					while( $row = mysqli_fetch_assoc( $sql ) )
					{
						$name = strtolower($row['usr_name']);
						echo '<option value="'.$name.'">'.$name.'</option>';
					}
				echo '</select>
				<input type="number" name="setnum" min="1" max="10" placeholder="1" class="form-control" />
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-4"><b>Featuring:</b></div>
		<div class="col"><input type="text" name="feature" placeholder="e.g. Mugiwara Pirates" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Badge Size:</b></div>
		<div class="col">
			<div class="input-group mb-3">
				<input type="text" name="width" placeholder="width in pixels" class="form-control" />
				<input type="text" name="height" placeholder="height in pixels" class="form-control" />
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-4"><b>Levels:</b></div>
		<div class="col"><input type="text" name="levels" placeholder="max donated level" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Upload badges:</b></div>
		<div class="col">
			<div class="input-group mb-3">
				<div class="custom-file">
					<input type="file" name="img[]" class="custom-file-input" id="inputGroupFile01" multiple>
					<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
				</div>
			</div>
		</div>
	</div>

	<input type="submit" name="submit" class="btn btn-success" value="Add Level Badge" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</div><!-- box -->
</form>';

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