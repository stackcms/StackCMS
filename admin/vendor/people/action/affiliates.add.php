<?php
/****************************************************
 * Action:			Add Affiliates
 * Description:		Show page for adding an affiliate
 */


// Process add affiliates form
if( isset($_POST['add']) ) {
	$upload->affiliates();
}

// Display form
echo '<h1>Add an affiliate</h1>
<p>Use this form to add an affiliate to the database. <b>If they have sent in a request, they are already in the database!</b><br />
Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&action=edit">edit</a> form to edit an affiliate\'s information.</p>

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
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&action='.$act.'" accept-charset="UTF-8" enctype="multipart/form-data">
<input type="hidden" name="status" value="Active" />
<div class="row">
	<div class="col-4"><b>TCG Owner:</b></div>
	<div class="col"><input type="text" name="owner" placeholder="Jane Doe" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>TCG Name:</b></div>
	<div class="col"><input type="text" name="subject" placeholder="Name of the TCG" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>TCG Email:</b></div>
	<div class="col"><input type="text" name="email" placeholder="username@domain.tld" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>TCG Website:</b></div>
	<div class="col"><input type="text" name="url" placeholder="http://" class="form-control" /></div>
</div><br />

<div class="row">
	<div class="col-4"><b>Upload Button:</b></div>
	<div class="col">
		<div class="input-group mb-3">
			<div class="custom-file">
			<input type="file" name="file" class="custom-file-input" id="inputGroupFile01">
			<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
			</div>
		</div>
	</div>
</div><br />

<input type="submit" name="add" class="btn btn-success" value="Add Affiliate" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>