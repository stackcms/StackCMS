<?php
/****************************************************
 * Module:			Activities
 * Description:		Show main page of activities list
 */


// Process mass deletion of activities
if( isset($_POST['delete']) )
{
	$delete = $database->query("DELETE FROM `tcg_activities` WHERE `act_date` < DATE_SUB(NOW(), INTERVAL 14 DAY)");
	
	if( !$delete )
	{
		$error[] = "Sorry, there was an error and activity data was not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The activity data from the past 30 days has been deleted!";
	}
}

// Process deletion of selected activities
if( isset($_POST['selection']) )
{
	$getID = $_POST['id'];
	foreach( $getID as $id )
	{
		$selection = $database->query("DELETE FROM `tcg_activities` WHERE `act_id`='$id'");
	}

	if( !$selection )
	{
		$error[] = "Sorry, there was an error and the selected activity datas were not deleted. ".mysqli_error($selection)."";
	}

	else
	{
		$success[] = "The selected activity data has been deleted!";
	}
}

$activity = $settings->getValue( 'item_per_page' );
if( !isset( $_GET['p'] ) )
{
	$p = 1;
}

else
{
	$p = (int)$_GET['p'];
}

$from = (($p * $activity) - $activity);

echo '<h1>TCG Activities</h1>
<p>Below is the complete list of the TCG\'s activities from the admins down to the members that you can check, cap\'n!</p>

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
<table width="100%" cellspacing="0" border="0" class="table table-bordered table-hover">
<thead class="thead-dark">
	<tr>
		<th scope="col" align="center" width="5%"></th>
		<th scope="col" align="center" width="5%">ID</th>
		<th scope="col" align="center" width="75%">Activity</th>
		<th scope="col" align="center" width="15%">Date</th>
	</tr>
</thead>
<tbody>';

$sql = $database->query("SELECT * FROM `tcg_activities` ORDER BY `act_id` DESC LIMIT $from, $activity");
while( $row = mysqli_fetch_assoc( $sql ) )
{
	echo '<tr>
	<td align="center"><input type="checkbox" name="id[]" value="'.$row['act_id'].'" /></td>
	<td align="center">'.$row['act_id'].'</td>
	<td>'.$row['act_rec'].'</td>
	<td align="center">'.date("Y/m/d", strtotime($row['act_date'])).'</td>
	</tr>';
}

echo '</tbody>

<tfoot>
<tr>
	<td align="center"><span class="arrow-right">↳</span></td>
	<td colspan="3">With selected: <input type="submit" name="selection" class="btn btn-danger" value="Delete" title="Delete selected activities" data-toggle="tooltip" data-placement="bottom" /></td>
</tr>
</tfoot>
</table>
</form>
</div><br />';



// Show activity pagination
$total_results = mysqli_fetch_array($database->query("SELECT COUNT(*) as num FROM `tcg_activities`"));
if( isset($_GET['p']) && $_GET['p'] != "" )
{
	$page_no = $_GET['p'];
}

else
{
	$page_no = 1;
}

$total_records_per_page = $settings->getValue( 'item_per_page' );

$offset = ($page_no-1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";

$result_count = $database->query("SELECT COUNT(*) AS total_records FROM `tcg_activities`");
$total_records = mysqli_fetch_array($result_count);
$total_records = $total_records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total pages minus 1

echo '<nav aria-label="Page navigation example">
<ul class="pagination">
	<li class="page-item disabled"><a class="page-link" href="">Page '.$page_no.' of '.$total_no_of_pages.'</a></li>';
	if( $page_no <= 1 ) {}
	if( $page_no > 1 )
	{
		echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$previous_page.'">Previous</a></li>';
	}

	if( $total_no_of_pages <= 10 )
	{
		for( $counter = 1; $counter <= $total_no_of_pages; $counter++ )
		{
			if( $counter == $page_no )
			{
				echo '<li class="page-item active"><a class="page-link">'.$counter.'</a></li>';
			}

			else
			{
				echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$counter.'">'.$counter.'</a></li>';
			}
		}
	}

	elseif( $total_no_of_pages > 10 )
	{
		if( $page_no <= 4 )
		{
			for( $counter = 1; $counter < 11; $counter++ )
			{
				if( $counter == $page_no )
				{
					echo '<li class="page-item active"><a class="page-link">'.$counter.'</a></li>';
				}

				else
				{
					echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$counter.'">'.$counter.'</a></li>';
				}
			}

			echo '<li class="page-item"><a class="page-link">...</a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$second_last.'">'.$second_last.'</a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$total_no_of_pages.'">'.$total_no_of_pages.'</a></li>';
		}

		elseif( $page_no > 4 && $page_no < $total_no_of_pages - 4 )
		{
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p=1">1</a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p=2">2</a></li>';
			echo '<li class="page-item"><a class="page-link">...</a></li>';
			for( $counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++ )
			{
				if( $counter == $page_no )
				{
					echo '<li class="page-item active"><a class="page-link">'.$counter.'</a></li>';
				}

				else
				{
					echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$counter.'">'.$counter.'</a></li>';
				}
			}

			echo '<li class="page-item"><a class="page-link">...</a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$second_last.'">'.$second_last.'</a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$total_no_of_pages.'">'.$total_no_of_pages.'</a></li>';
		}

		else
		{
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p=1">1</a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p=2">2</a></li>';
			echo '<li class="page-item"><a class="page-link">...</a></li>';
			for( $counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++ )
			{
				if( $counter == $page_no )
				{
					echo '<li class="page-item active"><a class="page-link">'.$counter.'</a></li>';
				}

				else
				{
					echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$counter.'">'.$counter.'</a></li>';
				}
			}
		}
	}

	echo '<li ';
	if($page_no >= $total_no_of_pages)
	{
		echo 'class="page-item disabled"';
	}
	echo '><a class="page-link" ';
	if($page_no < $total_no_of_pages)
	{
		echo 'href="'.$tcgurl.'?mod='.$mod.'&p='.$next_page;
	}
	echo '">Next</a></li>';
	
	if( $page_no < $total_no_of_pages )
	{
		echo '<li class="page-item"><a class="page-link" href="'.$tcgurl.'admin/content.php?mod='.$mod.'&p='.$total_no_of_pages.'">Last &rsaquo;&rsaquo;</a></li>';
	}
echo '</ul>
</nav>

<p>Do you wish to delete all activity data? If you do so, any new quick updates such as masteries and level ups will be removed from the last 14 days. This cannot be undone!</p>
<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'">
<p><input type="submit" name="delete" class="btn btn-danger" value="Mass Data Deletion" title="Delete all activities from the last 14 days" data-toggle="tooltip" data-placement="bottom"></p>
</form>';
?>