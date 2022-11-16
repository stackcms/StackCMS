<?php
/**************************************************
 * Module:				Games Main
 * Description:			Show main tab of games list
 */


// Mass set games to weekly
if( isset( $_POST['mass-weekly'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$update = $database->query("UPDATE `tcg_games` SET `game_set`='Weekly' WHERE `game_id`='$id'");
	}

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the games were not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The games were successfully updated from the database.";
	}
}

// Mass set games to bi-weekly A
if( isset( $_POST['mass-set1'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$update = $database->query("UPDATE `tcg_games` SET `game_set`='Set A' WHERE `game_id`='$id'");
	}

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the games were not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The games were successfully updated from the database.";
	}
}

// Mass set games to bi-weekly B
if( isset( $_POST['mass-set2'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$update = $database->query("UPDATE `tcg_games` SET `game_set`='Set B' WHERE `game_id`='$id'");
	}

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the games were not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The games were successfully updated from the database.";
	}
}

// Mass set games to monthly
if( isset( $_POST['mass-monthy'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$update = $database->query("UPDATE `tcg_games` SET `game_set`='Monthly' WHERE `game_id`='$id'");
	}

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the games were not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The games were successfully updated from the database.";
	}
}

// Mass set games to special
if( isset( $_POST['mass-special'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$update = $database->query("UPDATE `tcg_games` SET `game_set`='Special' WHERE `game_id`='$id'");
	}

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the games were not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The games were successfully updated from the database.";
	}
}

// Mass delete games
if( isset( $_POST['mass-delete'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_games` WHERE `game_id`='$id'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the games were not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The games were successfully deleted from the database.";
	}
}

// Mass activate games
if( isset( $_POST['mass-activate'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$update = $database->query("UPDATE `tcg_games` SET `game_status`='Active' WHERE `game_id`='$id'");
	}

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the games were not deleted. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The games were successfully deleted from the database.";
	}
}

// Mass deactivate games
if( isset( $_POST['mass-deactivate'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$update = $database->query("UPDATE `tcg_games` SET `game_status`='Inactive' WHERE `game_id`='$id'");
	}

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the games were not deleted. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The games were successfully deleted from the database.";
	}
}


echo '<h1>Games</h1>
<p>Change your TCG game\'s settings through this page.<br />
If you are going to <u>add a password gate game</u>, <a href="'.$PHP_SELF.'?mod='.$mod.'&action=add">use this form</a> instead.</p>

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
<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'">
<table width="100%" id="admin-games" class="table table-bordered table-hover">
<thead class="thead-dark"><tr>
	<th scope="col" align="center" width="5"></th>
	<th scope="col" align="center" width="10%">Set</th>
	<th scope="col" align="center" width="18%">Game</th>
	<th scope="col" align="center" width="28%">Subtitle</th>
	<th scope="col" align="center" width="8%">Choice</th>
	<th scope="col" align="center" width="8%">Random</th>
	<th scope="col" align="center" width="8%">Currencies</th>
	<th scope="col" align="center" width="15%">Action</th>
</tr></thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_games` ORDER BY `game_set`, `game_slug`");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['game_id'].'" /></td>
	<td align="center">'.$row['game_set'].'</td>
	<td align="center">';

		if( $row['game_status'] == "Inactive" )
		{
			echo '<span style="color:red;" title="Inactive">'.$row['game_title'].'</span>';
		}

		else
		{
			echo $row['game_title'];
		}

	echo '</td>
	<td align="center">'.$row['game_subtitle'].'</td>
	<td align="center">'.$row['game_choice_array'].'</td>
	<td align="center">'.$row['game_random_array'].'</td>
	<td align="center">'.$row['game_currency_array'].'</td>
	<td align="center">';
		if( $row['game_status'] == "Inactive" )
		{
			echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=activate&id='.$row['game_id'].'\';" data-toggle="tooltip" data-placement="bottom" title="Activate this game" class="btn btn-primary"><i class="bi-toggle-on" role="image"></i></button> ';
		}

		else
		{
			echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=deactivate&id='.$row['game_id'].'\';" data-toggle="tooltip" data-placement="bottom" title="Deactivate this game" class="btn btn-warning"><i class="bi-toggle-off" role="image"></i></button> ';
		}

		if( empty( $row['game_pass_array'] ) )
		{
			echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=edit-auto&id='.$row['game_id'].'\';" data-toggle="tooltip" data-placement="bottom" title="Edit this game" class="btn btn-success"><i class="bi-gear" role="image"></i></button> ';
		}

		else
		{
			echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=edit&id='.$row['game_id'].'\';" data-toggle="tooltip" data-placement="bottom" title="Edit this game" class="btn btn-success"><i class="bi-gear" role="image"></i></button> ';
		}
		echo '<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&action=delete&id='.$row['game_id'].'\';" data-toggle="tooltip" data-placement="bottom" title="Delete this game" class="btn btn-danger"><i class="bi-trash3" role="image"></i></button>
	</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="7">With selected: 
		<input type="submit" name="mass-weekly" value="Weekly" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Set selected games to weekly" />
		<input type="submit" name="mass-set1" value="Set A" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Set selected games to bi-weekly A" />
		<input type="submit" name="mass-set2" value="Set B" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Set selected games to bi-weekly B" />
		<input type="submit" name="mass-monthly" value="Monthly" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Set selected games to monthly" />
		<input type="submit" name="mass-special" value="Special" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Set selected games to special" />
		<input type="submit" name="mass-activate" value="Activate" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Activate selected games" />
		<input type="submit" name="mass-deactivate" value="Deactivate" class="btn btn-warning" data-toggle="tooltip" data-placement="bottom" title="Deactivate selected games" />
		<input type="submit" name="mass-delete" value="Delete" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete selected games" />
	</td>
</tr>
</tfoot>
</table>
</form>
</div>';
?>