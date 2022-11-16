<?php
/********************************************************
 * Page:			Released Decks
 * Description:		Show main page of released decks list
 */


// Process mass withhold decks
if( isset( $_POST['withhold-deck'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$withhold = $database->query("UPDATE `tcg_cards` SET `card_status`='Upcoming' WHERE `card_id`='$id'");

		// Delete activity log
		$sql = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_id`='$id'");
		$activity = $database->query("DELETE FROM `tcg_activities` WHERE `act_type`='released' AND `act_slug`='".$sql['card_filename']."'");
	}

	if( !$withhold && !$activity )
	{
		$error[] = "Sorry, there was an error and the card deck(s) was not moved back to the upcoming list. ".mysqli_error($withhold)." ".mysqli_error($activity);
	}

	else
	{
		$success[] = "The card deck(s) was successfully moved back to the upcoming list!";
	}
}


// Process mass regular deck types of upcoming decks
if( isset( $_POST['mass-regular'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$regular = $database->query("UPDATE `tcg_cards` SET `card_worth`='1' WHERE `card_id`='$id'");
	}

	if( !$regular )
	{
		$error[] = "Sorry, there was an error and the card decks were not set to regular deck type. ".mysqli_error($regular)."";
	}

	else
	{
		$success[] = "The decks has been set to regular decks successfully!";
	}
}

// Process mass special deck types of upcoming decks
if( isset( $_POST['mass-special'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$special = $database->query("UPDATE `tcg_cards` SET `card_worth`='2' WHERE `card_id`='$id'");
	}

	if( !$special )
	{
		$error[] = "Sorry, there was an error and the decks were not set to special deck type. ".mysqli_error($special)."";
	}

	else
	{
		$success[] = "The decks has been set to special decks successfully!";
	}
}

// Process mass rare deck types of upcoming decks
if( isset( $_POST['mass-rare'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$rare = $database->query("UPDATE `tcg_cards` SET `card_worth`='3' WHERE `card_id`='$id'");
	}

	if( !$rare )
	{
		$error[] = "Sorry, there was an error and the decks were not set to rare deck type. ".mysqli_error($rare)."";
	}

	else
	{
		$success[] = "The decks has been set to rare decks successfully!";
	}
}


// Process mass delete decks
if( isset( $_POST['delete-deck'] ) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$delete = $database->query("DELETE FROM `tcg_cards` WHERE `card_id`='$id'");

		// Delete activity log
		$sql = $database->get_assoc("SELECT * FROM `tcg_cards` WHERE `card_id`='$id'");
		$activity = $database->query("DELETE FROM `tcg_activities` WHERE `act_type`='upcoming' AND `act_slug`='".$sql['card_filename']."'");
	}

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the card deck(s) was not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The card deck(s) was successfully deleted from the database!";
	}
}


// Show main cards list
echo '<h1>Cards Administration</h1>
<p>Do you want to <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page=upcoming&action=add">add an upcoming deck</a>?</p>
<center>
	<button type="button" class="btn btn-primary" onclick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page=upcoming\';">View Upcoming Decks?</button> 
	<button type="button" class="btn btn-primary" onclick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page=donated\';">View Donated Decks?</button> 
	<button type="button" class="btn btn-primary" onclick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page=event\';">View Event Cards?</button>';

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

$sql = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' ORDER BY `card_filename`");
echo '</center>

<div class="box">
    <p>Please be careful when using the buttons below for mass withhold and mass delete when the checkboxes are selected. <b>This action can not be undone!</b></p>
    
    <form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'">
	<table width="100%" id="admin-cardsreleased" class="table table-bordered table-hover">
	<thead class="thead-dark"><tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="25%">Filename</th>
		<th scope="col" align="center" width="9%">Category</th>
		<th scope="col" align="center" width="10%">Made/Donated by</th>
		<th scope="col" align="center" width="8%">Released</th>
		<th scope="col" align="center" width="5%"># / $</th>
		<th scope="col" align="center" width="8%">Action</th>
	</tr></thead>
	<tbody>';

	while( $row = mysqli_fetch_assoc( $sql ) )
	{
        $cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$row['card_cat']."'");
		echo '<tr>
		<td align="center"><div class="form-check"><input class="form-check-input" type="checkbox" name="id[]" value="'.$row['card_id'].'" /></div></td>
		<td align="center">'.$row['card_id'].'</td>
		<td align="center"><a href="'.$tcgurl.'cards.php?view=released&deck='.$row['card_filename'].'" target="_blank">'.$row['card_deckname'].'</a> ('.$row['card_filename'].')</td>
		<td align="center">'.$cat['cat_name'].'</td>
		<td align="center">'.$row['card_maker'].' / '.$row['card_donator'].'</td>
		<td align="center">'.$row['card_released'].'</td>
		<td align="center">'.$row['card_count'].'/'.$row['card_worth'].'</td>
		<td align="center">
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page=released&action=edit&id='.$row['card_id'].'\';" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit this deck"><i class="bi-gear" role="image"></i></button> 
			<button type="button" onClick="window.location.href=\''.$tcgurl.'admin/content.php?mod='.$mod.'&page=released&action=delete&id='.$row['card_id'].'\';" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete this deck"><i class="bi-trash3" role="image"></i></button>
		</td>
		</tr>';
	}

	echo '</tbody>

	<tfoot>
	<tr>
		<td align="center"><span class="arrow-right">↳</span></td>
		<td colspan="7">With selected: 
			<input type="submit" name="withhold-deck" value="Withhold" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Withhold selected decks" /> ';
			// Run arrays of card worth from admin settings
			$cardWorth = explode(", ", $settings->getValue( 'cards_total_worth' ));
			$array_count = count($cardWorth);
			for( $i = 0; $i <= ($array_count -1); $i++ )
			{
				isset( $cardWorth[$i] );
				if( $i == '0' ) { $type = 'Regular'; $toolTip = 'Make selected decks regular'; }
				elseif( $i == '1' ) { $type = 'Special'; $toolTip = 'Make selected decks special'; }
				else { $type = 'Rare'; $toolTip = 'Make selected decks rare'; }
				echo '<input type="submit" name="mass-'.strtolower($type).'" value="'.$type.'" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="'.$toolTip.'" /> ';
			}
			echo '<input type="submit" name="delete-deck" value="Delete" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete selected decks" />
		</td>
	</tr>
	</tfoot>
	</table>
	</form>
</div>';

?>