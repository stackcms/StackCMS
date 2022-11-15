<?php
@include('admin/class.lib.php');

// Check if script has been installed or not
$db_name = $db_database;
$sql = $database->num_rows("SHOW TABLES FROM $db_name LIKE 'tcg_settings'");
if( $sql == 0 )
{
	header("Location: install/index.php");
	exit;
}



@include($header);

// Begin index page
$blog_id = (isset($_GET['id']) ? $_GET['id'] : null);
$action = (isset($_GET['action']) ? $_GET['action'] : null);
$blog_postnumber = $settings->getValue('post_per_page');

if( !isset($_GET['page']) )
{
	$page = 1;
}

else
{
	$page = (int)$_GET['page'];
}


$from = (($page * $blog_postnumber) - $blog_postnumber);
$sql = $database->query("SELECT * FROM `tcg_post` WHERE `post_status`='Published' AND `post_type`='post' ORDER BY `post_id` DESC LIMIT $from, $blog_postnumber");
if( empty( $blog_id ) )
{
	while( $row = mysqli_fetch_array( $sql ) )
	{
		$mon = date("F", strtotime($row['post_date']));
		$day = date("d", strtotime($row['post_date']));
		$year = date("Y", strtotime($row['post_date']));
		$today2 = date("Y-m-01", strtotime($row['post_date']));
		$today = $row['post_date'];
		$id = $row['post_id'];
		$title = stripslashes($row['post_title']);
		$mem = stripslashes($row['post_member']);
		$mas = stripslashes($row['post_master']);
		$lvl = stripslashes($row['post_level']);
		$refer = stripslashes($row['post_referral']);
		$aff = stripslashes($row['post_affiliate']);
		$games = stripslashes($row['post_game']);
		$amount = stripslashes($row['post_amount']);
		$auth = stripslashes($row['post_auth']);
		$icon = stripslashes($row['post_icon']);
		$entry = stripslashes($row['post_content']);

		$comm = $database->num_rows("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='$id'");

		echo '<h3 class="post-info"><span class="line-center">
			<span class="fas fa-calendar-alt" aria-hidden="true"></span> '.$mon.' '.$day.', '.$year.'
			<span class="fas fa-pencil-alt" aria-hidden="true"></span> <a href="mailto:'.$tcgemail.'">'.$tcgowner.'</a>
			<span class="fas fa-comment-dots" aria-hidden="true"></span> <a href="'.$tcgurl.'index.php?id='.$id.'">'.$comm.' Comments</a>
		</span></h3>
	
		<h1><a href="'.$tcgurl.'index.php?id='.$id.'">'.$title.'</a></h1>
		<table width="100%" class="table table-sliced table-striped"><tbody>';
		if( $row['post_member'] == "None" )
		{
			echo '<tr>
			<td width="15%" align="right"><b>Members:</b></td>
			<td><i>There are currently no new members.</i></td>
			</tr>';
		}
		
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Members:</b></td>
			<td>'.$mem.' &mdash; <i>Welcome to the TCG!</i></td>
			</tr>';
		}

		if( $row['post_master'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Masteries:</b></td>
			<td>'.$mas.' &mdash; <i>Keep up the good work!</i></td>
			</tr>';
		}

		if( $row['post_level'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Level Ups:</b></td>
			<td>'.$lvl.' &mdash; <i>Good job, congrats!</td>
			</tr>';
		}

		if( $row['post_referral'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Referrals:</b></td>
			<td>'.$refer.' &mdash; <i>Thank you for promoting '.$tcgname.'!</i></td>
			</tr>';
		}

		if( $row['post_affiliate'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Affiliates:</b></td>
			<td>'.$aff.' &mdash; <i>Check out our neighbors!</i></td>
			</tr>';
		}

		if( $row['post_game'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Games:</b></td>
			<td>'.$games.'</td>
			</tr>';
		}
		echo '</tbody></table>';

		if( $entry == "" ) {}
		else
		{
			echo '<p><img src="'.$tcgimg.'icons/'.$icon.'" align="left" class="post-icon" /></p>';
			echo $entry;
		}

		if( $row['post_wish'] == "None" ) {}
		else
		{
			$wish = $database->query("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='$today'");
			echo '<h2>Wishing Stars</h2>
			<p>Kindly take a total max of <u>2 cards per deck</u> if there are no restrictions indicated.</p>
			<ol>';
			while( $rowish = mysqli_fetch_array( $wish ) )
			{
				$c = $database->num_rows("SELECT * FROM `tcg_cards_cat`");
				for( $i = 1; $i <= $c; $i++ )
				{
					$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$rowish['wish_cat']."'");
				}

				if( $rowish['wish_type'] == 1 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for choice cards spelling <b>'.$rowish['wish_word'].'</b>!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 2 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>'.$rowish['wish_amount'].'</b> choice pack from any deck!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 3 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>'.$rowish['wish_amount'].'</b> random pack from any deck!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 4 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for 3 choice cards from any <b>'.$cat['cat_name'].'</b> decks!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 5 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>double deck release</b>!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; And everything multiplies~</li>';
				}

				else
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>double game rewards</b> from the '.$rowish['wish_set'].' set!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; No need to refresh the rewards page!</li>';
				}
			} // end while
			echo '</ol>';
		} // end wishes block

		if( $row['post_deck'] == "None" ) {}
		else
		{
			echo '<h2>New Releases</h2><center>';
			$width = $settings->getValue('cards_size_width') * $row['post_amount'];
			$decks = $row['post_deck'];
			$array = explode(', ',$decks);
			$array_count = count($array);
			echo '<div style="width:'.$width.'px">';
			for( $i = 0; $i <= ($array_count -1); $i++ )
			{
				$digits = rand(01,20);
				if($digits < 10)
				{
					$digit = "0$digits";
				}

				else
				{
					$digit = $digits;
				}
				echo '<a href="'.$tcgurl.'cards.php?view=released&deck='.$array[$i].'"><img src="'.$tcgcards.''.$array[$i].''.$digit.'.'.$tcgext.'" border="0" /></a>';
			}
			echo '</div><br />
			<a href="'.$tcgurl.'releases.php?date='.$row['post_date'].'">Click here</a>  for your deck release pulls.</center>';
		}
		$comm = $database->num_rows("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='$id'");
		echo '<div class="comment"><a href="'.$tcgurl.'index.php?id='.$id.'">Add a Comment ('.$comm.')</a></div>';
	} // end while

	echo '<br />';

	// Show blog pagination
	$total_results = mysqli_fetch_array( $database->query("SELECT COUNT(*) as num FROM `tcg_post` WHERE `post_type`='post'") );
	if( ( isset( $_GET['page'] ) ) && ($_GET['page'] != "") )
	{
		$page_no = $_GET['page'];
	}
	else
	{
		$page_no = 1;
	}

	// Change 1 to number of desired post displayed
	$total_records_per_page = $settings->getValue( 'post_per_page' );

	$offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2";

	$result_count = $database->query("SELECT COUNT(*) AS total_records FROM `tcg_post` WHERE `post_type`='post'");
	$total_records = mysqli_fetch_array($result_count);
	$total_records = $total_records['total_records'];
	$total_no_of_pages = ceil($total_records / $total_records_per_page);
	$second_last = $total_no_of_pages - 1; // total pages minus 1

	echo '<div align="center">
		<ul class="pagination">
			<li class="disabled"><a href="">Page '.$page_no.' of '.$total_no_of_pages.'</a></li>';
			if( $page_no <= 1 ) {}
			if( $page_no > 1 )
			{
				echo '<li><a href="'.$tcgurl.'index.php?page='.$previous_page.'">Previous</a></li>';
			}

			if( $total_no_of_pages <= 5 )
			{
				for( $counter = 1; $counter <= $total_no_of_pages; $counter++ )
				{
					if( $counter == $page_no )
					{
						echo '<li class="active"><a>'.$counter.'</a></li>';
					}

					else
					{
						echo '<li><a href="'.$tcgurl.'index.php?page='.$counter.'">'.$counter.'</a></li>';
					}
				}
			}

			else if( $total_no_of_pages > 5 )
			{
				if( $page_no <= 4 )
				{
					for( $counter = 1; $counter < 6; $counter++ )
					{
						if( $counter == $page_no )
						{
							echo '<li class="active"><a>'.$counter.'</a></li>';
						}

						else
						{
							echo '<li><a href="'.$tcgurl.'index.php?page='.$counter.'">'.$counter.'</a></li>';
						}
					}
					echo '<li><a>...</a></li>';
					echo '<li><a href="'.$tcgurl.'index.php?page='.$second_last.'">'.$second_last.'</a></li>';
					echo '<li><a href="'.$tcgurl.'index.php?page='.$total_no_of_pages.'">'.$total_no_of_pages.'</a></li>';
				}

				else if( $page_no > 4 && $page_no < $total_no_of_pages - 4 )
				{
					echo '<li><a href="'.$tcgurl.'index.php?page=1">1</a></li>';
					echo '<li><a href="'.$tcgurl.'index.php?page=2">2</a></li>';
					echo '<li><a>...</a></li>';
					for( $counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++ )
					{
						if( $counter == $page_no )
						{
							echo '<li class="active"><a>'.$counter.'</a></li>';
						}

						else
						{
							echo '<li><a href="'.$tcgurl.'index.php?page='.$counter.'">'.$counter.'</a></li>';
						}
					}
					echo '<li><a>...</a></li>';
					echo '<li><a href="'.$tcgurl.'index.php?page='.$second_last.'">'.$second_last.'</a></li>';
					echo '<li><a href="'.$tcgurl.'index.php?page='.$total_no_of_pages.'">'.$total_no_of_pages.'</a></li>';
				}

				else
				{
					echo '<li><a href="'.$tcgurl.'index.php?page=1">1</a></li>';
					echo '<li><a href="'.$tcgurl.'index.php?page=2">2</a></li>';
					echo '<li><a>...</a></li>';
					for( $counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++ )
					{
						if( $counter == $page_no )
						{
							echo '<li class="active"><a>'.$counter.'</a></li>';
						}

						else
						{
							echo '<li><a href="'.$tcgurl.'index.php?page='.$counter.'">'.$counter.'</a></li>';
						}
					}
				}
			}
			echo '<li ';
			if( $page_no >= $total_no_of_pages ) {}
			if( $page_no < $total_no_of_pages )
			{
				echo '<li><a href="'.$tcgurl.'index.php?page='.$next_page.'">Next</a></li>';
			}
			if( $page_no < $total_no_of_pages )
			{
				echo '<li><a href="'.$tcgurl.'index.php?page='.$total_no_of_pages.'">Last &rsaquo;&rsaquo;</a></li>';
			}
			echo '<li><a href="'.$tcgurl.'index.php?id=archive">Archive</a></li>
		</ul>
	</div>';
} // end empty blog ID


// Display post archives page
else if( $blog_id == "archive" )
{
	echo '<div class="box"><h1>News Archive</h1>
	<p>Below is the complete list of our news archive sorted by the recent one.</p>
	<table width="100%" class="table table-sliced table-striped">
	<thead><tr>
		<td width="20%"><b>Published on:</b></td>
		<td width="60%"><b>Title:</b></td>
		<td width="20%"><b>Comments:</b></td>
		</tr></thead>
	<tbody>';
	$sql = $database->query("SELECT * FROM `tcg_post` WHERE `post_status`='Published' AND `post_type`='post' ORDER BY `post_date` DESC");
	while( $row = mysqli_fetch_assoc( $sql ) )
	{
		echo '<tr><td>'.date("F d, Y", strtotime($row['post_date'])).'</td><td>'.$row['post_title'].'</td><td>';
		$comm = $database->num_rows("SELECT `comm_post` FROM `tcg_post_comm` WHERE `comm_post`='".$row['post_id']."'");
		if( $comm != 0 )
		{
			echo '<a href="'.$tcgurl.'index.php?id='.$row['post_id'].'">'.$comm.' Comments</a>';
		}

		else{
			echo '<a href="'.$tcgurl.'index.php?id='.$row['post_id'].'">Leave a comment?</a>';
		}
		echo '</td></tr>';
	}
	echo '</tbody>
	</table></div>';
} // end archive page


// Display full post page with comments
else
{
	if( $action == "add" )
	{
		if( empty($_POST['comment']) )
		{
			die("You didn't post anything on the comment field! Please make sure to fill up the form before hitting submit.");
		}

		$id = htmlspecialchars(strip_tags($_POST['id']));
		$name = htmlspecialchars(strip_tags($_POST['name']));
		$comment = $_POST['comment'];
		$comment = nl2br($comment);
		$comment = str_replace("'","\'",$comment);
		$date = date("Y-m-d", strtotime("now"));

		$insert = $database->query("INSERT INTO `tcg_post_comm` (`comm_post`,`comm_name`,`comm_text`,`comm_date`) VALUES ('$id','$name','$comment','$date')");

		if( !$insert )
		{
			echo '<div class="box"><h1>Error!</h1>
			<p>There was an error while processing your form and your comment was not added to the database.</p></div>';
		}

		else
		{
			$last_id = $database->query("SELECT `comm_id` FROM `tcg_post_comm` ORDER BY `comm_id` DESC LIMIT 0 , 1" );
			$getC = mysqli_fetch_assoc($last_id);
			$uu = $database->query("SELECT `usr_name` FROM `user_list`");
			while( $row = mysqli_fetch_assoc( $uu ) )
			{
				$ux = $row['usr_name'].', ';
				$uux = substr_replace($ux,"",-2);

				$ux1 = explode(", ", $uux);
				for( $u = 1; $u <= count($ux1); $u++ )
				{
					$uname[$u] = '@'.$row['usr_name'];
					$text = '<a href="'.$tcgurl.'index.php?id='.$id.'#notif-'.$getC['comm_id'].'">You have been mentioned by '.$name.' in an update.</a>';
					$date = date("Y-m-d H:i:s", strtotime("now"));

					if( strpos( $comment, $uname[$u] ) !== false )
					{
						$database->query("INSERT INTO `user_notices` (`notif_name`,`notif_comm`,`notif_message`,`notif_read`,`notif_date`) VALUES ('".$row['usr_name']."','".$getC['comm_id']."','$text','0','$date')");
					}
				}
			}
			header("Location: index.php?id=" . $id);
		}
	} // end add form process

	else
	{
		if( ( !isset( $_GET['id'] ) ) || ( !is_numeric( $_GET['id'] ) ) )
		{
			die("Invalid ID specified.");
		}

		$id = (int)$_GET['id'];
		$row = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_id`='$id' AND `post_status`='Published' LIMIT 1") or print ("Can't select entry from table tcg_post.<br />" . $sql . "<br />" . mysqli_connect_error());

		$mon = date("F", strtotime($row['post_date']));
		$day = date("d", strtotime($row['post_date']));
		$year = date("Y", strtotime($row['post_date']));
		$today2 = date("Y-m-01", strtotime($row['post_date']));
		$id = $row['post_id'];
		$today = $row['post_date'];
		$title = stripslashes($row['post_title']);
		$mem = stripslashes($row['post_member']);
		$mas = stripslashes($row['post_master']);
		$lvl = stripslashes($row['post_level']);
		$refer = stripslashes($row['post_referral']);
		$aff = stripslashes($row['post_affiliate']);
		$games = stripslashes($row['post_game']);
		$amount = stripslashes($row['post_amount']);
		$auth = stripslashes($row['post_auth']);
		$icon = stripslashes($row['post_icon']);
		$entry = stripslashes($row['post_content']);

		$comm = $database->num_rows("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='$id'");

		echo '<h3 class="post-info"><span class="line-center">
			<span class="fas fa-calendar-alt" aria-hidden="true"></span> '.$mon.' '.$day.', '.$year.'
			<span class="fas fa-pencil-alt" aria-hidden="true"></span> <a href="mailto:'.$tcgemail.'">'.$tcgowner.'</a>
			<span class="fas fa-comment-dots" aria-hidden="true"></span> <a href="'.$tcgurl.'index.php?id='.$id.'">'.$comm.' Comments</a>
		</span></h3>
		
		<h1><a href="'.$tcgurl.'index.php?id='.$id.'">'.$title.'</a><div class="update">Posted on '.$mon.' '.$day.', '.$year.'</div></h1>
		<table width="100%" class="table table-sliced table-striped"><tbody>';
		if( $row['post_member'] == "None" )
		{
			echo '<tr>
			<td width="15%" align="right"><b>Members:</b></td>
			<td><i>There are currently no new members.</i></td>
			</tr>';
		}

		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Members:</b></td>
			<td>'.$mem.' &mdash; <i>Welcome to the TCG!</i></td>
			</tr>';
		}

		if( $row['post_master'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Masteries:</b></td>
			<td>'.$mas.' &mdash; <i>Keep up the good work!</i></td>
			</tr>';
		}

		if( $row['post_level'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Level Ups:</b></td>
			<td>'.$lvl.' &mdash; <i>Good job, congrats!</i></td>
			</tr>';
		}

		if( $row['post_referral'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Referrals:</b></td>
			<td>'.$refer.' &mdash; <i>Thank you for promoting '.$tcgname.'!</i></td>
			</tr>';
		}

		if( $row['post_affiliate'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Affiliates:</b></td>
			<td>'.$aff.' &mdash; <i>Check out our neighbors!</i></td>
			</tr>';
		}

		if( $row['post_game'] == "None" ) {}
		else
		{
			echo '<tr>
			<td width="15%" align="right"><b>Games:</b></td>
			<td>'.$games.'</td>
			</tr>';
		}
		echo '</tbody></table>';

		if( $entry == "" ) {}
		else
		{
			echo '<p><img src="'.$tcgimg.'icons/'.$icon.'" align="left" class="post-icon" /></p>';
			echo $entry;
		}

		if( $row['post_wish'] == "None" ) {}
		else
		{
			$wish = $database->query("SELECT * FROM `user_wishes` WHERE `wish_status`='Granted' AND `wish_date`='$today'");
			echo '<h2>Wishing Stars</h2>
			<p>Kindly take a total max of <u>2 cards per deck</u> if there are no restrictions indicated.</p>
			<ol>';

			while( $rowish = mysqli_fetch_array( $wish ) )
			{
				$c = $database->num_rows("SELECT * FROM `tcg_cards_cat`");
				for( $i=1; $i<=$c; $i++ )
				{
					$cat = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='".$rowish['wish_cat']."'");
				}

				if( $rowish['wish_type'] == 1 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for choice cards spelling <b>'.$rowish['wish_word'].'</b>!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 2 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>'.$rowish['wish_amount'].'</b> choice pack from any deck!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 3 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>'.$rowish['wish_amount'].'</b> random pack from any deck!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 4 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for 3 choice cards from any <b>'.$cat['cat_name'].'</b> decks!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; <a href="'.$tcgurl.'wishes.php?id='.$rowish['wish_id'].'"><span class="fas fa-external-link-alt" aria-hidden="true"></span></a></li>';
				}

				else if( $rowish['wish_type'] == 5 )
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>double deck release</b>!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; And everything multiplies~</li>';
				}

				else
				{
					echo '<li><b>'.$rowish['wish_name'].'</b> &mdash; <span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span>I wish for <b>double game rewards</b> from the '.$rowish['wish_set'].' set!<span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span> &nbsp;&nbsp; No need to refresh the rewards page!</li>';
				}
			} // end while
			echo '</ol>';
		}

		if( $row['post_deck'] == "None" ) {}
		else
		{
			echo '<h2>New Releases</h2><center>';
			$width = $settings->getValue('cards_size_width') * $row['post_amount'];
			$decks = $row['post_deck'];
			$array = explode(', ',$decks);
			$array_count = count($array);
			echo '<div style="width:'.$width.'px;">';

			for( $i=0; $i<=($array_count -1); $i++ )
			{
				$digits = rand(01,20);
				if($digits < 10)
				{
					$digit = "0$digits";
				}

				else
				{
					$digit = $digits;
				}
				echo '<a href="'.$tcgurl.'cards.php?view=released&deck='.$array[$i].'"><img src="'.$tcgcards.''.$array[$i].''.$digit.'.'.$tcgext.'" border="0" /></a>';
			}
			echo '</div><br />
			<a href="'.$tcgurl.'releases.php?date='.$row['post_date'].'">Click here</a>  for your deck release pulls.</center>';
		}

		// Show comments
		if( $comm < 2 )
		{
			$txt = 'Comment';
		}
		else
		{
			$txt = 'Comments';
		}
		echo '<h2>'.$comm.' '.$txt.' to <i>'.$row['post_title'].'</i></h2>';

		if( isset( $_POST['edit-comment'] ) )
		{
			$post_id = $sanitize->for_db($_POST['post-id']);
			$comm_id = $sanitize->for_db($_POST['comment-id']);
			$comment = $_POST['edit-'.$comm_id];
			$comment = nl2br($comment);
			$comment = str_replace("'","\'",$comment);
			$update = $database->query("UPDATE `tcg_post_comm` SET `comm_text`='$comment' WHERE `comm_id`='$comm_id'");

			if( !$update )
			{
				echo '<h3>Error!</h3>
				<p>There was an error while processing your edit form and your comment was not edited from the database.</p>';
			}

			else
			{
				header("Location: index.php?id=" . $id);
			}
		}

		$comment = $database->query("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='$id' ORDER BY `comm_id` DESC");
		$formID = $database->get_assoc("SELECT * FROM `tcg_post_comm` WHERE `comm_post`='$id'");
		while( $comm = mysqli_fetch_assoc( $comment ) )
		{
			$get = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_name`='".$comm['comm_name']."'");
			?>

			<script>
				$(document).ready(
					function() {
					$("#edit<?php echo $comm['comm_id']; ?>").click(function() {
						$("#edit_form<?php echo $comm['comm_id']; ?>").fadeToggle();
					});
				});
			</script>

			<?php
			echo '<li class="comment">
				<div class="commTitle"><a name="notif-'.$comm['comm_id'].'">';
					$database->query("UPDATE `user_notices` SET `notif_read`='1' WHERE `notif_comm`='".$comm['comm_id']."' AND `notif_name`='$player'");
					echo '<span class="fas fa-calendar-alt" aria-hidden="true"></span> '.date("ymd", strtotime($comm['comm_date'])).' <a href="'.isset($get['usr_url']).'" target="_blank">'.$comm['comm_name'].'</a>
					<div style="text-align: right; float: right; width: 50%;"><div id="edit'.$comm['comm_id'].'">';
					if( $comm['comm_name'] == $player )
					{
						echo '<a href="#comment-'.$comm['comm_id'].'">Edit my comment</a>';
					}

					$role = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
					if( isset( $role['usr_role'] ) == 1 || isset( $role['usr_role'] ) == 2 )
					{
						echo ' / <a href="#comment-'.$comm['comm_id'].'">Edit this comment</a>';
					}
					echo '</div></div>
				</div>
				<div class="tableBody">'.$comm['comm_text'].'<br /><br />
				<div id="edit_form'.$comm['comm_id'].'" name="comment-'.$comm['comm_id'].'" style="display: none;">
					<form method="post" action="index.php?id='.$id.'">
					<input type="hidden" name="comment-id" value="'.$comm['comm_id'].'" />
					<input type="hidden" name="post-id" value="'.$id.'" />
					<textarea name="edit-'.$comm['comm_id'].'" rows="4" style="width:95%;">'.$comm['comm_text'].'</textarea><br />
					<input type="submit" name="edit-comment" value="Edit my comment" class="btn-success" />
					</form>
				</div><!-- #edit_form -->
				</div>
			</li>';
			echo "\n\n";
		}

		// Check if user is logged in or not
		if( empty( $login ) )
		{
			echo '<div class="box"><h2>Login</h2>
			<p>Kindly please login to your account in able to post a comment on our updates. <b>This is only for current members.</b></p></div>';
		}

		else
		{
			$rowmem = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'") or print ("Can't select member from table user_list.<br />" . $sqlmem . "<br />" . mysqli_connect_error());
			echo '<br /><div class="box"><h2>Add a comment</h2>
			<form method="post" action="'.$tcgurl.'index.php?id='.$id.'&action=add">
				<input type="hidden" name="id" id="id" value="'.$id.'" />
				<input type="hidden" name="name" id="name" value="'.$rowmem['usr_name'].'" />
				<textarea style="width: 98%" rows="5" name="comment" id="comment"></textarea><br />
				<input type="submit" name="submit_comment" id="submit_comment" class="btn-success" value="Add Comment" />
			</form></div>';
		}
	}
} // end view full blog

@include($footer);
?>