<?php
/*********************************************
 * Module:			Freebies Main
 * Description:		Display freebies main page
 */


if( ($logChk['log_title'] == "Freebies #".$free['free_id']) && ($logChk['log_subtitle'] == "(".$free['free_date'].")") )
{
	echo '<h1>Freebies #'.$free['free_id'].' ('.$free['free_date'].') : Halt!</h1>
	<p>You have already claimed this freebie! If you missed your claims, here they are:</p>
	<center><b>'.$logChk['log_title'].' '.$logChk['log_subtitle'].':</b> '.$logChk['log_rewards'].'</center>';
}

else
{
	echo '<h1>Freebies #'.$free['free_id'].': '.$free['free_date'].'</h1>';
	echo '<blockquote class="wish">
	<strong><span class="fas fa-quote-left" aria-hidden="true" style="margin-right: 20px;"></span></strong>';
	if( $free['free_type'] == 1 )
	{
		echo 'Take choice cards spelling <b>'.$free['free_word'].'</b>!';
	}

	else if( $free['free_type'] == 2 )
	{
		echo 'Take a total of <b>'.$free['free_amount'].'</b> choice pack from any deck!';
	}

	else if( $free['free_type'] == 3 )
	{
		echo 'Take a total of <b>'.$free['free_amount'].'</b> random pack from any deck!';
	}

	else if( $free['free_type'] == 4 )
	{
		echo 'Take a total of 3 choice cards from any <b>'.$free['free_cat'].'</b> decks!';
	}

	echo '<strong><span class="fas fa-quote-right" aria-hidden="true" style="margin-left: 20px;"></span></strong><br />
		<div class="notice">
			<b>You can only submit once!</b> Make sure to check your choices first before submitting.
		</div>
	</blockquote>

	<center>
	<form method="post" action="'.$tcgurl.'freebies.php?id='.$id.'&go=claimed">
	<input type="hidden" name="name" value="'.$user['usr_name'].'">
	<input type="hidden" name="type" value="'.$free['free_type'].'">';
	if( $free['free_type'] == 1 )
	{
		$w = $free['free_word'];
		$trim = str_replace(" ", "", $w);
		$length = strlen($trim);
		echo '<input type="hidden" name="word" value="'.$trim.'">
		<table width="100%" cellspacing="3" class="border">';
		for( $i=0; $i<$length; $i++ )
		{
			$word = $trim[$i];
			echo '<tr>
				<td width="10%" class="headLine">'.$word.'</td>
				<td width="90%" class="tableBody">
					<select name="card'.$i.'" style="width:85%;">';
			if( is_numeric( $word ) )
			{
				// Query your database here for all released cards you want when the "word" is a number
				$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_released` <= '".$free['free_date']."' AND `card_status`='Active' ORDER BY `card_filename` ASC");
				while( $row = mysqli_fetch_assoc( $query ) )
				{
					$filename = stripslashes($row['card_filename']);
					echo '<option value="'.$filename.'">'.$row['card_deckname'].' ('.$filename.')</option>';
				}
				echo '</select><select name="num'.$i.'">';
				for( $j=0; $j<=20; $j++ )
				{
					$j = str_pad($j,2,"0",STR_PAD_LEFT);
					if( (substr($j, 0, 1) == $word) || (substr($j, 1, 2) == $word) )
					{
						echo '<option value="'.$j.'">'.$j.'</option>';
					}
				}
				echo '</select></td></tr>';
			}

			else
			{
				$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_released` <= '".$free['free_date']."' AND `card_status`='Active' AND (`card_filename` LIKE '$word%' OR `card_filename` LIKE '%$word%' OR `card_filename` LIKE '%$word') ORDER BY `card_filename` ASC");

				// Start dropdown for each letter
				while( $row = mysqli_fetch_assoc( $query ) )
				{
					$filename = stripslashes($row['card_filename']);
					echo '<option value="'.$filename.'">'.$row['card_deckname'].' ('.$filename.')</option>';
				}
				echo '</select><input type="text" name="num'.$i.'" placeholder="00" size="1"></td>
				</tr>';
			}
		}
		echo '<tr>
			<td class="tableBody" colspan="2" align="center">
				<input type="submit" name="submit" class="btn-success" value="Claim Freebies" /> 
				<input type="reset" name="reset" class="btn-cancel" value="Reset" />
			</td>
		</tr>
		</table>
	</form>
	</center>';
	}

	else if( $free['free_type'] == 2 )
	{
		echo '<input type="hidden" name="amount" value="'.$free['free_amount'].'">
		<table width="90%" cellspacing="3" class="border">';
		$c = $free['free_amount'];
		for( $i=1; $i<=$c; $i++ )
		{
			echo '<tr>
			<td width="10%" class="headLine">Choice #'.$i.'</td>
			<td width="90%" class="tableBody">
				<select name="card'.$i.'" style="width:85%;">';
			$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_released` <= '".$free['free_date']."' AND `card_status`='Active' ORDER BY `card_filename` ASC");
			while( $row = mysqli_fetch_assoc( $query ) )
			{
				$filename = stripslashes($row['card_filename']);
				echo '<option value="'.$filename.'">'.$row['card_deckname'].' ('.$filename.')</option>';
			}
			echo '</select><input type="text" name="num'.$i.'" placeholder="00" size="1"></td>';
			echo '</tr>
			<tr>
				<td class="tableBody" colspan="2" align="center">
					<input type="submit" name="submit" class="btn-success" value="Claim Freebies" /> 
					<input type="reset" name="reset" class="btn-cancel" value="Reset" />
				</td>
			</tr>
			</table>
		</form>
		</center>';
		}
	}

	else if( $free['free_type'] == 3 )
	{
		echo '<input type="hidden" name="amount" value="'.$free['free_amount'].'">';
		$c = $free['amount'];
		for( $i=1; $i<=$c; $i++ )
		{
			echo '<input type="hidden" name="card'.$i.'" value="'; $general->randtype('Active'); echo '" />';
		}
		echo '<table width="90%" cellspacing="3" class="border">
		<tr>
			<td class="tableBody" colspan="2" align="center">
				<input type="submit" name="submit" class="btn-success" value="Claim Freebies" />
			</td>
		</tr>
		</table>
	</form>
	</center>';
	}

	else if( $free['free_type'] == 4 )
	{
		echo '<input type="hidden" name="amount" value="3">
		<table width="90%" cellspacing="3" class="border">';
		for( $i=1; $i<=3; $i++ )
		{
			echo '<tr>
				<td width="10%" class="headLine">Choice #'.$i.'</td>
				<td width="90%" class="tableBody">
					<select name="card'.$i.'" style="width:85%;">';
			$query = $database->query("SELECT * FROM `tcg_cards` WHERE `card_released` <= '".$free['free_date']."' AND `card_status`='Active' AND `card_cat`='".$free['free_cat']."' ORDER BY `card_filename` ASC");
			while( $row = mysqli_fetch_assoc( $query ) )
			{
				$filename = stripslashes($row['card_filename']);
				echo '<option value="'.$filename.'">'.$row['card_deckname'].' ('.$filename.')</option>';
			}
			echo '</select><input type="text" name="num'.$i.'" placeholder="00" size="1"></td>';
			echo '</tr>
			<tr>
				<td class="tableBody" colspan="2" align="center">
					<input type="submit" name="submit" class="btn-success" value="Claim Freebies" /> 
					<input type="reset" name="reset" class="btn-cancel" value="Reset" />
				</td>
			</tr>
		</table>
	</form>
	</center>';
		}
	}
}
?>