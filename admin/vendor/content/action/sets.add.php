<?php
/********************************************************
 * Action:			Add Set/Series
 * Description:		Show page for adding deck sets/series
 */


// Process addition of sets/series form
if ( isset( $_POST['add'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$insert = $database->query("INSERT INTO `tcg_cards_set` (`set_name`) VALUES ('$name')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the set/series was not added. ".mysqli_error($insert)."";
	}

	else
	{
		$success[] = "The new set/series was successfully added to the database!";
	}
}


// Show add a set/series form
echo '<h1>Add a Card Set/Series</h1>
<p>Use this form to add a new card set/series to the database.<br />
Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for an existing card set/series.</p>

<div class="alert alert-warning" role="alert"><b>Notice:</b> Please take note that the ID will be the numerical value of your deck sets/series. So if you just need to rename one, edit the existing set/series before adding a new one.</div>

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

<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action='.$act.'">
<div class="col" style="width: 600px;">
	<div class="input-group mb-3">
			<div class="input-group-prepend">
			<span class="input-group-text"><b>Set/Series:</b></span>
		</div>
		<input type="text" name="name" class="form-control" placeholder="e.g. Animalia or Final Fantasy">
	</div>

	<input type="submit" name="add" class="btn btn-success" value="Add Set/Series" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</div>
</form>';
?>