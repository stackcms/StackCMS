<?php
/*
 * Class libraries for connections
 */

$db_server = 'localhost';							// The database server, usually localhost
$db_user = 'user';								// The username for your database
$db_password = 'password';							// The password for your database
$db_database = 'stack';								// The database name



/********************************************************
 * Description:		Set database connection for the TCG
 */
define('DB_SERVER',$db_server);                    // Defining a named constant, DB_SERVER
define('DB_USER',$db_user);                        // Defining a named constant, DB_USER
define('DB_PASSWORD',$db_password);                // Defining a named constant, DB_PASSWORD
define('DB_DATABASE',$db_database);                // Defining a named constant, DB_DATABASE


/********************************************************
** DO NOT EDIT BELOW UNLESS YOU KNOW WHAT YOU'RE DOING **
** YOU CAN ONLY EDIT THE COMMENTED PART IF YOU NEED TO **
 -------------------------------------------------------
 * Include class library files
 */
define('VALID_INC', TRUE);
include('class/database.class.php');
include('class/check.class.php');
include('class/count.class.php');
include('class/games.class.php');
include('class/general.class.php');
include('class/plugins.class.php');
include('class/settings.class.php');
include('class/uploads.class.php');
include('class/admin.class.php');
include('class/forms.class.php');


// Shortened variables for settings
$database = new Database;
$sanitize = new Sanitize;
$general = new General;
$upload = new Uploads;
$plugin = new Plugins;
$count = new Count;
$games = new Games;
$check = new Check;
$admin = new Admin;
$field = new Forms;

// Check if any tables exists in the database
$db_name = $db_database;
$sql = $database->num_rows("SHOW TABLES FROM $db_name LIKE 'tcg_settings'");
if( $sql == 0 ) {}
else
{
	$settings = new Settings;

	$header = $settings->getValue( 'file_path_header' );
	$footer = $settings->getValue( 'file_path_footer' );
	$tcgurl = $settings->getValue( 'tcg_url' );
	$tcgname = $settings->getValue( 'tcg_name' );
	$tcgemail = $settings->getValue( 'tcg_email' );
	$tcgowner = $settings->getValue( 'tcg_owner' );
	$tcgcards = $settings->getValue( 'file_path_cards' );
	$tcgext = $settings->getValue( 'cards_file_type' );
	$tcgimg = $settings->getValue( 'file_path_img' );
	$tcgpath = $settings->getValue( 'file_path_absolute' );
	$tcgdiscord = $settings->getValue( 'tcg_discord' );
	$tcgtwitter = $settings->getValue( 'tcg_twitter' );
	
	$latest = file_get_contents('https://stackcms.dev/sites/stack/version.txt');
	$current = $settings->getValue( 'script_version' );
	if( $latest != $current )
	{
		$version = $current;
		$upgradeNotice = '<div class="alert alert-danger" role="alert">You are currently using Stack '.$current.'. Please upgrade it to the latest version <u>'.$latest.'</u>.</div>';
	}
	else
	{
		$version = $latest;
		$upgradeNotice = '';
	}
	$credits = '&copy; '.date('Y').' '.$tcgname.' by <a href="mailto:'.$tcgemail.'">'.$tcgowner.'</a> &bull; Online TCG concept &copy; Calico &bull; Powered by <a href="https://stackcms.dev/" target="_blank">Stack '.$version.'</a>';
}


// Set page strings for dynamic pages (DO NOT EDIT)
$id = (isset($_GET['id']) ? $_GET['id'] : null);
$do = (isset($_GET['do']) ? $_GET['do'] : null);
$go = (isset($_GET['go']) ? $_GET['go'] : null);
$set = (isset($_GET['set']) ? $_GET['set'] : null);
$sub = (isset($_GET['sub']) ? $_GET['sub'] : null);
$msg = (isset($_GET['msg']) ? $_GET['msg'] : null);
$mod = (isset($_GET['mod']) ? $_GET['mod'] : null);
$name = (isset($_GET['name']) ? $_GET['name'] : null);
$item = (isset($_GET['item']) ? $_GET['item'] : null);
$form = (isset($_GET['form']) ? $_GET['form'] : null);
$deck = (isset($_GET['deck']) ? $_GET['deck'] : null);
$view = (isset($_GET['view']) ? $_GET['view'] : null);
$page = (isset($_GET['page']) ? $_GET['page'] : null);
$stat = (isset($_GET['stat']) ? $_GET['stat'] : null);
$play = (isset($_GET['play']) ? $_GET['play'] : null);
$act = (isset($_GET['action']) ? $_GET['action'] : null);
$trace = (isset($_GET['trace']) ? $_GET['trace'] : null);
$login = (isset($_SESSION['USR_LOGIN']) ? $_SESSION['USR_LOGIN'] : null);
?>