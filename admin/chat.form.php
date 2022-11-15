<?php
ob_start();
session_set_cookie_params(86400,"/");
session_start();

@include('class.lib.php');

date_default_timezone_set( $settings->getValue( 'tcg_timezone' ) );

$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
$player = isset($row['usr_name']) ? $row['user_name'] : null;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title> <?php echo $tcgname; ?> </title>
<meta name="author" content="<?php echo $tcgowner; ?> (c) <?php echo date('Y'); ?>" />
<meta name="copyright" content="<?php echo $tcgurl; ?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i|Work+Sans:400,700" rel="stylesheet">
<style type="text/css">
body
{
    font: 400 12px 'Segoe UI', sans-serif;
    margin: 0;
}
input, textarea, select, submit, reset
{
	font: 400 12px 'Segoe UI', sans-serif;
	border: 1px solid #cccccc;
	background-color: #ffffff;
	border-radius: 5px;
	padding: 5px 10px;
	color: #808080;
	margin: 2px;
}
</style>
</head>

<body>

<?php
// Process form
if( isset( $_POST['submit'] ) )
{
	$check->Value();
	$name = $sanitize->for_db($_POST['name']);
	$url = $sanitize->for_db($_POST['url']);
	$chat = $_POST['chat'];

	$date = date("Y-m-d H:i:s", strtotime("now"));
	$chat = str_replace("'", "\'", $chat);
	$chat = nl2br($chat);

	// Insert acquired data
	$insert = $database->query("INSERT INTO `tcg_chatbox` (`chat_name`,`chat_url`,`chat_msg`,`chat_date`) VALUES ('$name','$url','$chat','$date')") or print("Can't insert into table tcg_chatbox.<br />" . $result . "<br />Error:" . mysqli_connect_error());
	if( !$insert )
	{
		echo '<center>There was an error and your chat was not recorded.</center>';
	}
	else
	{
		header("Location: /admin/chat.form.php");
	}
}

// Show form
if( empty( $login ) )
{
	echo '<center>Please login to your account to use the chat box!</center>';
}

else
{
	echo '<form method="post" action="/admin/chat.form.php">
	<input type="hidden" name="name" value="'.$player.'" />
	<input type="hidden" name="url" value="'.$row['usr_url'].'" />
	<input type="text" name="chat" placeholder="Enter message here..." style="width:61%;" /> 
	<input type="submit" name="submit" id="submit" value=" Chat !" />
	</form>';
}
?>

</body>
</html>
