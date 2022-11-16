<?php
/*******************************************************
 * Action:			Add Categories
 * Description:		Show page for adding card categories
 */


// Process add categories form
if( isset( $_POST['add'] ) )
{
	$name = $sanitize->for_db($_POST['name']);
	$insert = $database->query("INSERT INTO `tcg_cards_cat` (`cat_name`) VALUES ('$name')");

	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the category was not added. ".mysqli_error($insert)."";
	}

	else
	{
		$success[] = "The new category was successfully added to the database!";
	}
}


// Show add a category form
echo '<h1>Add a Card Category</h1>
<p>Use this form to add a new card category to the database.<br />
Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">edit</a> form to update information for an existing card category.</p>

<div class="alert alert-warning" role="alert"><b>Notice:</b> Please take note that the ID will be the numerical value of your deck categories. So if you just need to rename one, edit the existing category before adding a new one.</div>

<center>';
if( isset( $error ) ) {
	foreach( $error as $msg )
	{
		echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
	}
}

if( isset( $success ) ) {
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
			<span class="input-group-text"><b>Category Name:</b></span>
		</div>
		<input type="text" name="name" placeholder="e.g. Puzzle" class="form-control">
	</div>
</div>

<input type="submit" name="add" class="btn btn-success" value="Add Category" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>';
?>