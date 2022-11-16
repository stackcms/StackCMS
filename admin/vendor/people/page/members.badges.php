<?php
/*********************************************************
 * Page:			Level Badges Main
 * Description:		Show main page of members level badges
 */


// Process mass deletion of level badges
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_levels_badge` WHERE `badge_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the level badge wasn't deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The level badge was successfully deleted.";
	}
}


// Show level badges list and form
echo '<h1>Level Badges</h1>
<p>&raquo; Do you want to <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add a level badge</a>?</p>
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
<table width="100%" id="admin-membersbadges" class="table table-bordered table-hover">
<thead class="thead-dark">
<tr>
	<th scope="col" align="center" width="5%"></th>
	<th scope="col" align="center" width="5%">ID</th>
	<th scope="col" align="center" width="20%">Donator</th>
	<th scope="col" align="center" width="15%">Filename</th>
	<th scope="col" align="center" width="15%">Size</th>
	<th scope="col" align="center" width="25%">Featuring</th>
	<th scope="col" align="center" width="15%">Action</th>
</tr>
</thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_levels_badge` ORDER BY `badge_name`");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['badge_id'].'"></td>
	<td align="center">'.$row['badge_id'].'</td>
	<td align="center">'.$row['badge_name'].'</td>
	<td align="center">'.$row['badge_set'].'</td>
	<td align="center">'.$row['badge_width'].' x '.$row['badge_height'].' pixels</td>
	<td align="center">'.$row['badge_feature'].'</td>
	<td align="center">
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=edit&id='.$row['badge_id'].'\';" class="btn btn-success" title="Edit this level badge" data-toggle="tooltip" data-placement="bottom" /><i class="bi-gear" role="image"></i></button> 
		<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=delete&id='.$row['badge_id'].'\';" class="btn btn-danger" title="Delete this level badge" data-toggle="tooltip" data-placement="bottom" /><i class="bi-trash3" role="image"></i></button>
	</td></tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="6">With selected: <input type="submit" name="mass-delete" class="btn btn-danger" value="Delete" title="Delete selected level badges" data-toggle="tooltip" data-placement="bottom" /></td>
</tr>
</tfoot>
</table>
</form>
</div>';
?>