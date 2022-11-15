<?php
ob_start();
session_set_cookie_params(86300,"/");
session_start();

$database = new Database;
$sanitize = new Sanitize;
$settings = new Settings;
$count = new Count;

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
	<link href="<?php echo $tcgurl; ?>themes/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $tcgurl; ?>themes/gamma/style.css" rel="stylesheet">
	<link href="<?php echo $tcgurl; ?>themes/gamma/mobile.css" rel="stylesheet">
	<link href="<?php echo $tcgurl; ?>themes/general.css" rel="stylesheet">
	<link rel="icon" type="image/png" href="<?php echo $tcgurl; ?>themes/favicon.ico" />
	<link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600|Open+Sans:400,600,700|Roboto+Slab:300,300i,400,400i,600,600i|Libre+Baskerville:400,400i" rel="stylesheet">
	<script src="<?php echo $tcgurl; ?>themes/tabcontent.js" type="text/javascript"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script>
	function insertext(text) {
		var TheTextBox = document.getElementById("comment");
		TheTextBox.value = TheTextBox.value + text;
	}

	$(function ()
	{
		$(document).scroll(function ()
		{
			var $nav = $(".navbar-fixed-top");
			$nav.toggleClass('scrolled', $(this).scrollTop() > 200);
		});
	});
	</script>
</head>

<body onload="setBj(); stat();" onKeyPress="catchKeyCode();">
	<!-- BEGIN HEADERS -->
	<div id="header">
		<div class="menu">
			<nav id="primary_nav_wrap">
				<ul>
					<li><a href="<?php echo $tcgurl; ?>">Home</a></li>
					<li><a href="<?php echo $tcgurl; ?>about.php">About</a>
						<ul>
							<?php
							$aboutSQL = $database->query("SELECT * FROM `tcg_post` WHERE `post_type`='page' AND `post_parent`='2'");
							while( $row = mysqli_fetch_assoc( $aboutSQL ) )
							{
								echo '<li><a href="'.$tcgurl.'about.php?p='.$row['post_slug'].'">'.$row['post_title'].'</a></li>';
							}
							?>
						</ul>
					</li>
					<?php
					if( $settings->getValue( 'tcg_registration' ) == "0" || !empty($login) ) {}
					else
					{
						echo '<li><a href="'.$tcgurl.'members.php?page=join">Join Us</a></li>';
					}
					?>
					<li><a href="<?php echo $tcgurl; ?>cards.php">Cards</a>
						<ul>
							<li><a href="<?php echo $tcgurl; ?>cards.php?view=released">Released</a></li>
							<li><a href="<?php echo $tcgurl; ?>cards.php?view=upcoming">Upcoming</a></li>
						</ul>
					</li>
					<li><a href="<?php echo $tcgurl; ?>members.php">Members</a>
						<ul>
							<?php
							if( empty( $login ) ) {}
							else
							{
								echo '<li><a href="'.$tcgurl.'account.php">My Account</a></li>
								<li><a href="'.$tcgurl.'games.php">Interactive</a></li>';
							}
							?>
							<li><a href="<?php echo $tcgurl; ?>site.php?page=level-badges">Level Badges</a></li>
						</ul>
					</li>
					<?php
					$countNotif = $database->num_rows("SELECT * FROM `user_notices` WHERE `notif_name`='$player' AND `notif_read`='0'");
					if( empty( $login ) || $countNotif == "0" ) {}
					else
					{
						echo '<li><a href="">Notices ('.$countNotif.')</a>
						<ul>';
						$notif = $database->query("SELECT * FROM `user_notices` WHERE `notif_name`='$player' AND `notif_read`='0'");
						while( $row = mysqli_fetch_assoc( $notif ) )
						{
							echo '<li>'.$ntf['notif_message'].'</li>';
						}
						echo '</ul>
						</li>';
					}
					?>
					<li><a href="<?php echo $tcgurl; ?>site.php">Site</a></li>
					<?php
					if( !empty( $login ) && $memrole != "7" )
					{
						echo '<li><a href="'.$tcgurl.'admin/" target="_blank"><span class="fas fa-cogs" aria-hidden="true"></span></a></li>';
					}
					else {}

					// List active social media accounts
					if( empty( $settings->getValue( 'tcg_discord' ) ) ) {}
					else
					{
						echo '<li><a href="'.$tcgdiscord.'" target="_blank"><span class="fab fa-discord" aria-hidden="true"></span></a></li>';
					}

					if( empty( $settings->getValue( 'tcg_twitter' ) ) ) {}
					else
					{
						echo '<li><a href="'.$tcgtwitter.'" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a></li>';
					}
					?>
				</ul>
			</nav>
		</div><!-- .menu -->

		<div class="title">
			<?php
			// Change to either TCG name or TCG header image
			// Uncomment the following to show TCG name
			echo $tcgname;

			// Uncomment the following to show TCG header image
			// echo '<img src="'.$tcgurl.'themes/gamma/images/header.png" />';
			?>
		</div><!-- .title -->

		<div class="welcome">
			<?php
			$b = $database->get_assoc("SELECT * FROM `tcg_post` WHERE `post_type`='post' ORDER BY `post_date` DESC");
			$blimit = $b['post_amount'];
			if( $blimit == 0 ) {}
			else
			{
				echo '<div>
				<h2>Newest Decks</h2>';
				$deckSQL = $database->query("SELECT * FROM `tcg_cards` WHERE `card_status`='Active' AND `card_released`='".$b['post_date']."' ORDER BY `card_released` DESC LIMIT $blimit");
				while( $row = mysqli_fetch_assoc( $deckSQL ) )
				{
					$digits = rand(01, $row['card_count']);
					if( $digits < 10 )
					{
						$digit = "0$digits";
					}
					else
					{
						$digit = $digits;
					}
					$card = $row['card_filename'].''.$digit;
					echo '<a href="'.$tcgurl.'cards.php?view=released&deck='.$row['card_filename'].'"><img src="'.$tcgcards.''.$card.'.'.$tcgext.'"></a>';
				}
				echo '</div>';
			}
			?>
			
			<div>
				<h2>Welcome Message</h2>
				<!-- Change to your own welcome message -->
				Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam.
			</div>

			<div>
				<h2>Statistics</h2>
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="26%" align="right"><b>Owner:</b></td>
						<td><?php echo $tcgowner; ?></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td align="right"><b>Status:</b></td>
						<td>Upcoming</td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td align="right"><b>Prejoin:</b></td>
						<td>Month 00, 0000</td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td align="right"><b>Opened:</b></td>
						<td>Month 00, 0000</td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td align="right"><b>Members:</b></td>
						<td><?php echo $count->numAll('user_list','Active','usr',''); ?><i>a</i> ( <?php echo $count->numAll('user_list','Pending','usr',''); ?><i>p</i> / <?php echo $count->numAll('user_list','Hiatus','usr',''); ?><i>h</i> / <?php echo $count->numAll('user_list','Inactive','usr',''); ?><i>i</i> / <?php echo $count->numAll('user_list','Retired','usr',''); ?><i>r</i> )</td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td align="right"><b># of Decks:</b></td>
						<td><?php echo $count->numCards('Active','1'); ?> (+<?php echo $count->numCards('Upcoming',''); ?> upcoming)</td>
					</tr>
				</table>
			</div>

			<div>
				<h2>Latest News</h2>
				<?php
				$postSQL = $database->query("SELECT * FROM `tcg_post` WHERE `post_type`='post' AND `post_status`='Published' ORDER BY `post_date` DESC LIMIT 5");
				while( $row = mysqli_fetch_assoc( $postSQL ) )
				{
					$postTITLE = mb_strimwidth($row['post_title'], 0, 20, "...");
					$postDATE = date("Y/m/d", strtotime( $row['post_date'] ));
					echo '<span class="fas fa-file-alt" aria-hidden="true"></span> <span class="date">'.$postDATE.'</span> <a href="'.$tcgurl.'index.php?id='.$row['post_id'].'">'.$postTITLE.'</a><hr>';
				}
				?>
			</div>
		</div><!-- .welcome -->
	</div><!-- #header -->
	<!-- END HEADERS -->


	<!-- BEGIN CONTENT -->
	<div id="container">
		<div class="content">