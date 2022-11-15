<?php
ob_start();
session_set_cookie_params(86300,"/");
session_start();

$database = new Database;
$sanitize = new Sanitize;
$settings = new Settings;
$count = new Count;

/* CHANGE TO YOUR OWN TIMEZONE */
date_default_timezone_set( $settings->getValue('tcg_timezone') );

$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");

if( empty( $login ) )
{
	$uid = ''; $player = ''; $memrole = '';
}
else
{
	$uid = isset($row['usr_id']) ? $row['usr_id'] : null;
	$player = isset($row['usr_name']) ? $row['usr_name'] : null;
	$memrole = isset($row['usr_role']) ? $row['usr_role'] : null;
}

if( $settings->getValue( 'tcg_status' ) == "Upcoming" ) { $prefix = 'prejoin'; }
else { $prefix = 'tcg'; }
?>


<!-- BEGIN HTML CODE HERE -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo $settings->getValue( 'tcg_name' ); ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link href="<?php echo $tcgurl; ?>themes/delta/style.css" rel="stylesheet">
	<link href="<?php echo $tcgurl; ?>themes/delta/mobile.css" rel="stylesheet">
	<link href="<?php echo $tcgurl; ?>themes/general.css" rel="stylesheet">
	<link rel="icon" type="image/png" href="<?php echo $tcgurl; ?>themes/favicon.ico" />
	<link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,500i,600,600i,900,900i|Playfair+Display:400,400i,700,700i" rel="stylesheet">
	<script src="<?php echo $tcgurl; ?>themes/tabcontent.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>
	function insertext(text)
	{
		var TheTextBox = document.getElementById("comment");
		TheTextBox.value = TheTextBox.value + text;
	}

	$(function()
	{
		$(document).scroll(function ()
		{
			var $nav = $(".navbar-fixed-top");
			$nav.toggleClass('scrolled', $(this).scrollTop() > 180);
		});
	});
	</script>
</head>

<body onload="setBj(); stat();" onKeyPress="catchKeyCode();">
<div align="center">
	<div id="wrapper">

	<!-- BEGIN HEADERS -->
		<div id="logo">
			<a href="<?php echo $tcgurl; ?>" alt="<?php echo $tcgname; ?>"><?php echo $tcgname; ?></a>
		</div><!-- /#logo -->

		<div id="menu" class="navbar-fixed-top">
			<div class="pull-left">
				<nav id="primary_nav_wrap">
					<ul>
						<li><a href="javascript:void(0);" class="icon" onclick="myFunction()"><i class="fas fa-bars"></i></a></li>
						<li><a href="<?php echo $tcgurl; ?>">home</a></li>
						<li><a href="<?php echo $tcgurl; ?>about.php">about</a></li>
						<?php
						if( $settings->getValue( 'tcg_registration' ) == "0" || !empty($login) ) {}
						else
						{
							echo '<li><a href="'.$tcgurl.'members.php?page=join">join us</a></li>';
						}
						?>
						<li><a href="<?php echo $tcgurl; ?>cards.php">cards</a></li>
						<li><a href="<?php echo $tcgurl; ?>members.php">members</a></li>
						<li><a href="<?php echo $tcgurl; ?>games.php">games</a></li>
						<li><a href="<?php echo $tcgurl; ?>site.php">site</a></li>
						<?php
						if( empty($login) ) {}
						else
						{
							echo '<li><a href="'.$tcgurl.'account.php">account</a></li>';
						}

						if( !empty($login) && $memrole != "7" )
						{
							echo '<li><a href="'.$tcgurl.'admin/" target="_blank">admin</a></li>';
						}
						else {}

						$countNotif = $database->num_rows("SELECT * FROM `user_notices` WHERE `notif_name`='$player' AND `notif_read`='0'");
						if( empty($login) ) {}
						else
						{
						?>
						<li><a href="" class="notif"><span class="fas fa-bell" aria-hidden="true"><div class="count-notif"><?php echo $countNotif; ?></div></span></a>
							<ul>
								<?php
								$notif = $database->query("SELECT * FROM `user_notices` WHERE `notif_name`='$player' AND `notif_read`='0'");
								while( $ntf = mysqli_fetch_assoc( $notif ) )
								{
									echo '<li>'.$ntf['notif_message'].'</li>';
								}
								?>
							</ul>
						</li> 
						<?php
						}
						?>
					</ul>
				</nav>
			</div>

			<!-- OPTIONAL -->
			<div class="pull-right">
				<a href="<?php echo $tcgdiscord; ?>" target="_blank"><span class="fab fa-discord" aria-hidden="true"></span></a>
				<a href="<?php echo $tcgtwitter; ?>" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a>
				<a href="mailto:<?php echo $tcgemail; ?>"><span class="fas fa-envelope" aria-hidden="true"></span></a>
			</div>
		</div><!-- /#menu -->
	<!-- END HEADERS -->


	<!-- BEGIN CONTENT -->
		<div id="container">
			<div class="content">