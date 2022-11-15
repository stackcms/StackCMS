<?php
ob_start();
session_set_cookie_params(86400,"/");
session_start();

$database = new Database;
$sanitize = new Sanitize;
$general = new General;
$plugin = new Plugins;
$count = new Count;

$login = isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null;
$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_email`='$login'");
$player = $row['usr_name'];
if( $row['usr_role'] == 7 )
{
	header('Location: '.$tcgurl.'account.php');
}

date_default_timezone_set( $settings->getValue('tcg_timezone') );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title> STACK &nbsp;&nbsp; | &nbsp;&nbsp; an online TCG management system </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=iso-8859-1" />
<meta name="description" content="A content management system that you can use for your online TCG." />
<meta name="author" content="Aki (c) 2016" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="Language" content="English" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="Global" />
<meta name="copyright" content="https://stackcms.dev/" />
<meta name="robots" content="Index,Follow" />
<meta name="rating" content="General" />
<meta name="revisit-after" content="1 day" />
<link href="/theme/icon.png" rel="icon" type="image/x-icon" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/pure-min.css" integrity="sha384-oAOxQR6DkCoMliIh8yFnu25d7Eq/PHS21PClpwjOTeU2jRSq11vu66rf90/cZr47" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/grids-responsive-min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">

<!-- Main CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo $tcgurl; ?>admin/theme/<?php echo $settings->getValue('admin_skin'); ?>/<?php echo $settings->getValue('admin_skin'); ?>.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $tcgurl; ?>admin/theme/general.css" />
<link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" type="text/css">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;800&family=Poppins:wght@400;500;600;700&family=Inconsolata:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- Javascripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="<?php echo $tcgurl; ?>admin/theme/jquery.js"></script>
<script type="text/javascript">
function startTime()
{
    var today=new Date().toLocaleTimeString();
    document.getElementById('txt').innerHTML=today;
    t=setTimeout('startTime()',500);
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
</head>

<body onload="startTime()">
<div class="container-fluid">
	<?php @include($tcgpath.'admin/theme/sidebar.php'); ?>

	<div id="container">
		<a name="Home"></a>
		<div class="topBar">
			<div class="pull-left">
				<!-- <div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id=""><i class="bi-search" role="img"></i></span>
					</div>
					<input type="text" class="form-control" placeholder="Search..." aria-label="Search...">
					<div class="input-group-append">
						<button class="btn btn-primary" type="button">Search</button>
					</div>
				</div> -->
			</div>

			<div class="pull-right">
				<a href="<?php echo $tcgurl; ?>"><span class="fas fa-home" aria-hidden="true"></span> <?php echo $tcgname; ?></a>
				<a href="" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img src="<?php echo $tcgcards; ?>mc-<?php echo $player; ?>.png" border="0" class="member-card" /> <?php echo $player; ?>
				</a>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<small>Welcome!</small>
					<a class="dropdown-item" href="<?php echo $tcgurl; ?>account.php"><i class="bi-person-circle" role="img"></i> My Account</a>
					<a class="dropdown-item" href="<?php echo $tcgurl; ?>account.php?do=logout"><i class="bi-box-arrow-right" role="img"></i> Logout</a>
				</div>
			</div>
		</div>

		<div class="content">
			<div class="breadCrumbs">
				<a href="<?php echo $tcgurl; ?>admin/">Dashboard</a>
				<?php
				if( empty( $mod ) ) {}
				else
				{
					if( empty( $page ) )
					{
						if( empty( $act ) )
						{
							echo ' <span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'">'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)).'</a> 
							<span class="spacer">/</span> '.ucfirst(str_replace("-"," ",$mod));
						}

						else
						{
							echo ' <span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'">'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)).'</a> 
							<span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'?mod='.$mod.'">'.ucfirst($mod).'</a> 
							<span class="spacer">/</span> '.ucfirst(str_replace("-"," ",$act));
						}
					}

					else
					{
						if( empty( $act ) )
						{
							echo ' <span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'">'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)).'</a> 
							<span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'?mod='.$mod.'">'.ucfirst($mod).'</a> 
							<span class="spacer">/</span> '.ucfirst(str_replace("-"," ",$page));
						}

						else
						{
							echo ' <span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'">'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)).'</a> 
							<span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'?mod='.$mod.'">'.ucfirst($mod).'</a> 
							<span class="spacer">/</span> <a href="'.$_SERVER['PHP_SELF'].'?mod='.$mod.'&page='.$page.'">'.ucfirst(str_replace("-"," ",$page)).'</a> 
							<span class="spacer">/</span> '.ucfirst(str_replace("-"," ",$act));
						}
					}
				}
				?>

				<div class="date">
					Today is <?php echo date("l, jS F Y"); ?> at <span id="txt"></span>
				</div>
			</div><!-- .breadcrumbs -->