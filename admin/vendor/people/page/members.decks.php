<?php
/*****************************************************
 * Page:			User Member Deck Main
 * Description:		Show main page of user member deck
 */


// Check if user is accessing the page directly
if( empty( $name ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show member deck
else
{
	// Process task approval form
	if( isset( $_POST['submit'] ) )
	{
		$uid = $sanitize->for_db($_POST['uid']);
		$card = $sanitize->for_db($_POST['approve']);

		$userinfo = $database->get_assoc("SELECT * FROM `tcg_cards_user` WHERE `ud_id`='$uid'");

		if( $card !== '' )
		{
			$card = explode('; ',$card);
			function adddeck(&$value,$key)
			{
				$value = trim($value);
				$value = ''.$value.'';
			}
			array_walk($card,'adddeck');

			if ( empty( $userinfo['ud_cards'] ) )
			{
				$c = implode('; ',$card);
			}

			else
			{
				$card = implode('; ',$card);
				$c = $userinfo['ud_cards'].'; '.$card;
			}
		}

		$result = $database->query("UPDATE `tcg_cards_user` SET `ud_cards`='$c' WHERE `ud_id`='$uid' LIMIT 1");
		if( !$result )
		{
			$error[] = "Failed to update the member deck. ".mysqli_error($result);
		}

		else
		{
			$success[] = "The member deck has been updated and the card has been activated!";
		}
	} // end form process


	$row = $database->get_assoc("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='$name' AND `ud_completed`='0'");

	// Check if user have a member deck
	$sql = $database->num_rows("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='".$row['ud_name']."'");
	if( $sql == 0 )
	{
		echo '<h1>'.$name.'\'s Member Deck</h1>
		<p>This member hasn\'t created their member decks yet.</p>';
	}

	else
	{
		echo '<h1>'.$row['ud_name'].'\'s Member Deck</h1>
		<p>Please make sure to approve the tasks according to how the form is being sorted. Otherwise, the card numbers will not be able to match the task numbers.</p>
		<p><b>Example:</b> If the form is listed as Task #4, Task #1, Task #8, Task #3, it must be approved by this order and not as #1, #3, #4, #8.</p>
		<center>';
		if( isset( $error ) )
		{
			foreach( $error as $msg )
			{
				echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div>';
			}
		}

		if( isset( $success ) )
		{
			foreach( $success as $msg )
			{
				echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div>';
			}
		}
		echo '</center>';

		function trim_value(&$value) { $value = trim($value); }
		$mdeck = $database->query("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='".$row['ud_name']."' AND `ud_completed`='0' ORDER BY `ud_finished`");
		$mcount = $database->num_rows("SELECT * FROM `tcg_cards_user` WHERE `ud_name`='".$row['ud_name']."'");

		while( $col = mysqli_fetch_assoc( $mdeck ) )
		{
			$data = array();
			$cards = array();
			$tasks = array();

			if( $col['ud_cards'] != '' )
			{
				$cards = explode(';', $col['ud_cards']);
				array_walk($cards, 'trim_value');
				$count = count($cards);

				$deck = explode(',', $col['ud_deck']);
				$image = explode(';', $col['ud_cards']);
				foreach( $cards as $key => $card )
				{
					$data[$card] = array(
						'img' => trim($image[$key])
					);
				}
			}

			echo '<center><h1>'.$col['ud_deck'].' (';
			if( empty( $col['ud_cards'] ) )
			{
				echo '0';
			}

			else
			{
				echo $count;
			}
			echo ' / '.$col['ud_count'].')</h1>';
			?>

			<script>
				$(document).ready(
					function() {
					$("#edit<?php echo $col['ud_id']; ?>").click(function() {
						$("#edit_form<?php echo $col['ud_id']; ?>").fadeToggle();
					});
				});
			</script>

			<?php
			echo '<div id="edit'.$col['ud_id'].'">Do you want to <a href="#deckname-'.$col['ud_id'].'">edit this deck\'s name</a>?<br /></div>
			<div id="edit_form'.$col['ud_id'].'" name="deckname-'.$col['ud_id'].'" style="display: none;">
			<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'">
			<input type="hidden" name="deckID" value="'.$col['ud_id'].'" />
			<input type="text" name="new-deckname" value="'.$col['ud_deck'].'" /> 
			<input type="submit" name="edit-deckname" value="Edit Deckname" class="btn btn-success" />
			</form>
			</div><!-- edit deck name -->

			<table width="505" cellspacing="0" cellpadding="0" border="0"><tr>';
			for( $i = 1; $i <= $col['ud_count']; $i++ )
			{
				if( $i < 10 )
				{
					$digit = "0".$i;
				}
				else
				{
					$digit = $i;
				}

				if( in_array($i, $cards) )
				{
					echo '<td width="101" align="center" height="101"><img src="'.$tcgcards.''.$col['ud_deck'].''.$digit.'.'.$tcgext.'" /></td>';
				}
				else
				{
					echo '<td width="101" align="center" height="101"><img src="'.$tcgcards.'filler.'.$tcgext.'" /></td>';
				}

				if( $col['ud_break'] !== '0' && $i % $col['ud_break'] == 0 )
				echo '</tr>';
			}
			echo '</table></center>';

			// List down tasks for approval
			if( $col['ud_task_id'] != '' )
			{
				function cmp( $a, $b )
				{
					if( $a == $b )
					{
						return 0;
					}
					return ($a < $b) ? -1 : 1;
				}

				$tasks = explode('; ', $col['ud_task_id']);
				$proof = explode('; ', $col['ud_proof_logs']);
				array_walk($tasks, 'trim_value');
				array_walk($proof, 'trim_value');

				$arr = $col['ud_task_id']; // insert tasks
				$var = explode('; ', $arr);
				$num = count($var);

				uasort($var, 'cmp'); // Huh?

				$t = explode('; ', $col['ud_task_id']);
				$p = explode('; ', $col['ud_proof_logs']);
				$c = explode('; ', $col['ud_cards']);
				foreach( $tasks as $key => $task )
				{
					$data[$task] = array(
						'task_id' => trim($t[$key]),
						'proof_logs' => trim($p[$key]),
						'cards' => trim($c[$key])
					);
				}
			}

			echo '<center><form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'">
			<input type="hidden" name="uid" value="'.$col['ud_id'].'">
			<table width="60%" cellspacing="3">';
			foreach( $tasks as $key => $tid )
			{
				if( in_array($tid, $tasks) )
				{
					if( $data[$tid]['cards'] != $data[$tid]['task_id'] )
					{
						$taskSQL = $database->get_assoc("SELECT * FROM `user_decks` WHERE `task_id`='".$data[$tid]['task_id']."'");
						echo '<tr>
						<td width="20%"><b>Task:</b></td>
						<td width="80%">#'.$data[$tid]['task_id'].' - '.$taskSQL['task_name'].'</td>
						</tr>
						<tr>
						<td><b>Proof:</b></td>
						<td><div style="border:1px solid #ccc;border-radius:3px;padding:10px;height:100px;overflow:auto;">'.$data[$tid]['proof_logs'].'</div></td>
						</tr>
						<tr>
						<td></td>
						<td>Check the checkbox first before clicking the button: <input type="checkbox" name="approve" value="'.$tid.'" /> <input type="submit" name="submit" class="btn btn-success" value="Approve" /></td>
						</tr>
						<tr><td colspan="2"><hr></td></tr>';
					}
					else {}
				}
			}
			echo '</table>
			</form></center>';
		}
	}
}
?>