<?php
/**************************************************
 * Page:			Freebies Main
 * Description:		Show main page of freebies list
 */


// Process mass deletion form
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `user_freebies` WHERE `free_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the freebies were not deleted from the database. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The freebies has been deleted from the database!";
	}
}


// Show deletion form
$select = $database->query("SELECT * FROM `user_freebies` ORDER BY `free_date`");

echo '<h1>Freebies</h1>
<p>This page gives you, as an Admin of your TCG, an opportunity to give out freebies to your players which is different from the user wishes. Although it has the same structure as the user wishes, at least this one is personally from you.</p>
<p>&raquo; Do you want to <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add a freebie</a>?</p>

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
<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'">
<table id="admin-membersfreebies" class="table table-bordered table-hover">
<thead class="thead-dark">
<tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="9%">Type</th>
	<th scope="col" align="center" width="15%">Word</th>
	<th scope="col" align="center" width="10%">Category</th>
	<th scope="col" align="center" width="5%">Amount</th>
	<th scope="col" align="center" width="8%">Date</th>
	<th scope="col" align="center" width="8%">Action</th>
</tr>
</thead>
<tbody>';

while( $row = mysqli_fetch_assoc( $select ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['free_id'].'" /></td>
	<td align="center">'.$row['free_id'].'</td>
	<td align="center">';
		if( $row['free_type'] == 1 ) { echo 'Spell Choice'; }
		elseif( $row['free_type'] == 2 ) { echo 'Choice Pack'; }
		elseif( $row['free_type'] == 3 ) { echo 'Random Pack'; }
		elseif( $row['free_type'] == 4 ) { echo 'Category Choice'; }
	echo '</td>
	<td align="center">'.$row['free_word'].'</td>
	<td align="center">';
		$row['free_cat'];
		if( $row['free_cat'] == 0 ) { echo 'Not applicable'; }
		else
		{
			$c = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['free_cat']."'");
			echo $c['cat_name'];
		}
	echo '</td>
	<td align="center">'.$row['free_amount'].'</td>
	<td align="center">'.$row['free_date'].'</td>
	<td align="center">
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['free_id'].'\';" class="btn btn-success" title="Edit this freebie" data-toggle="tooltip" data-placement="bottom"><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['free_id'].'\';" class="btn btn-danger" title="Delete this freebie" data-toggle="tooltip" data-placement="bottom"><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="7">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected freebies" data-toggle="tooltip" data-placement="bottom" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';
?>