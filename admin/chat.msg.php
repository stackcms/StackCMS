<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i|Work+Sans:400,700" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/theme/general.css">
<style type="text/css">
body
{
	background-color: transparent !important;
	background-image: none !important;
	color: #999999 !important;
}
a:link, a:active, a:visited
{
	-webkit-transition: all 0.6s ease;
	-moz-transition: all 0.6s ease;
	-o-transition: all 0.6s ease;
	transition: all 0.6s ease;
	text-decoration: none;
	font-weight: 600;
	color: #dbada3;
}
a:hover
{
	color: #e36973;
}
</style>

<?php
@include('class.lib.php');
$database = new Database;
$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
$qry = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
$sql = $database->query("SELECT * FROM `tcg_chatbox` ORDER BY `chat_date` DESC");
$count = $database->num_rows("SELECT * FROM `tcg_chatbox`");

if( $count == 0 )
{
	echo '<div align="center" style="font-family: Segoe UI; font-size: 12px;">
		Your chatbox is empty!<br />
		Be the first one to chat.
	</div>';
}

else
{
	if( empty( $action ) )
	{
		echo '<table width="100%" class="table table-bordered table-striped" border="0">';
		while( $row = mysqli_fetch_assoc( $sql ) )
		{
			echo '<tr>
			<td width="100%" style="font-family: Segoe UI; font-size: 12px;">
				<div align="right" style="font-size:10px;">'.
					date("F d, Y", strtotime($row['chat_date'])).' at '.date("h:i A", strtotime($row['chat_date']));
					if( isset( $qry['usr_role'] ) == "1" )
					{
						echo ' [<a href="chat.msg.php?action=delete&id='.$row['chat_id'].'">Delete</a>]';
					}
				echo '</div>
				<b>';
				if( $row['chat_url'] == "" )
				{
					echo $row['chat_name'];
				}
				else
				{
					echo '<a href="'.$row['chat_url'].'" target="_blank">'.$row['chat_name'].'</a>';
				}
				echo ':</b> '.stripslashes($row['chat_msg']).'
			</td>
			</tr>';
		}
		echo '</table>';
	}
	
	else
	{
		if( empty( $id ) )
		{
			echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
		}
		else
		{
			$database->query("DELETE FROM `tcg_chatbox` WHERE `chat_id`='$id'");
			header("Location: chat.msg.php");
		}
	}
}
?>