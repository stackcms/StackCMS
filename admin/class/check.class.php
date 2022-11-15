<?php
/*
 * Class library for form checking functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Check
 * Description:		Functions for checking forms
 */
class Check
{
	function Value()
	{
		$database = new Database;
		$sanitize = new Sanitize;

		$exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i";
		$profanity = "/(beastial|bestial|blowjob|clit|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|fag|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|jism|jiz|kock|kondum|kum|kunilingus|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx)/i";
		$spamwords = "/(viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|texas|holdem|carisoprodol|ciara|ciprofloxacin|debt|dating|porn)/i";
		$bots = "/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer|Yandex)/i";

		if( preg_match($bots, $_SERVER['HTTP_USER_AGENT']) )
		{
			exit("<h1>Error</h1>\nKnown spam bots are not allowed.<br /><br />");
		}

		foreach( $_POST as $key => $value )
		{
			$value = trim($value);
			if( empty( $value ) )
			{
				exit("<h1>Error</h1>\nAll fields are required. Please go back and complete the form.");
			}

			else if( preg_match($exploits, $value) )
			{
				exit("<h1>Error</h1>\nExploits/malicious scripting attributes aren't allowed.");
			}

			else if( preg_match($profanity, $value) || preg_match($spamwords, $value) )
			{
				exit("<h1>Error</h1>\nThat kind of language is not allowed through this form.");
			}
			$_POST[$key] = stripslashes(strip_tags($value));
		}
		return true;
	}


	function Password() {
		$database = new Database;
		$sanitize = new Sanitize;

		$exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i";
		$profanity = "/(beastial|bestial|blowjob|clit|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|fag|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|jism|jiz|kock|kondum|kum|kunilingus|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx)/i";
		$spamwords = "/(viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|texas|holdem|carisoprodol|ciara|ciprofloxacin|debt|dating|porn)/i";
		$bots = "/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer|Yandex)/i";

		if( preg_match($bots, $_SERVER['HTTP_USER_AGENT']) )
		{
			exit("<h1>Error</h1>\nKnown spam bots are not allowed.<br /><br />");
		}

		foreach( $_POST as $key => $value )
		{
			$row = $database->get_assoc("SELECT * FROM `user_list` WHERE `usr_id`='".$_POST['id']."'");
			$value = trim($value);

			if( empty( $value ) )
			{
				exit("<h1>Error</h1>\nYou must fill out all fields. Please go back and fill in the form properly.<br /><br />");
			}

			else if( preg_match($exploits, $value) )
			{
				exit("<h1>Error</h1>\nExploits/malicious scripting attributes aren't allowed.<br /><br />");
			}

			else if( preg_match($profanity, $value) || preg_match($spamwords, $value) )
			{
				exit("<h1>Error</h1>\nThat kind of language is not allowed through our form.<br /><br />");
			}

			else if($_POST['password2'] != $_POST['password'])
			{
				exit("<h1>Error</h1>\nThe new passwords you entered do not match, please go back and make sure they are they same.");
			}

			else if( md5($_POST['current']) != $row['usr_pass'] )
			{
				exit("<h1>Error</h1>\nThe current password you entered does not match our records. Please go back and make sure you entered it correctly.");
			}

			$_POST[$key] = stripslashes(strip_tags($value));
		}
		return true;
	}


	function Member()
	{
		$database = new Database;
		$settings = new Settings;
		$tcgname = $settings->getValue( 'tcg_name' );

		$exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i";
		$profanity = "/(beastial|bestial|blowjob|clit|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|fag|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|jism|jiz|kock|kondum|kum|kunilingus|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx)/i";
		$spamwords = "/(viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|texas|holdem|carisoprodol|ciara|ciprofloxacin|debt|dating|porn|valerastar|Привет|bit.ly)/i";
		$bots = "/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer|Yandex)/i";

		if( preg_match($bots, $_SERVER['HTTP_USER_AGENT']) )
		{
			exit("<h1>Error</h1>\nKnown spam bots are not allowed.<br /><br />");
		}

		foreach( $_POST as $key => $value )
		{
			$value = trim($value);
			$num_check1 = $database->num_rows("SELECT * FROM `user_list` WHERE `usr_email`='$_POST[email]'");
			$num_check2 = $database->num_rows("SELECT * FROM `user_list` WHERE `usr_name`='$_POST[username]'");

			if( empty( $_POST['username'] ) || empty( $_POST['email'] ) || empty( $_POST['url'] ) || empty( $_POST['collecting'] ) )
			{
				exit("<h1>Error</h1>\n<p>You must provide your name, email, url, and collecting deck. Please go back and fill in the form properly.</p>");
			}

			else if( preg_match($exploits, $value) )
			{
				exit("<h1>Error</h1>\nExploits/malicious scripting attributes aren't allowed.<br /><br />");
			}

			else if( preg_match($profanity, $value) || preg_match($spamwords, $value) )
			{
				exit("<h1>Error</h1>\nThat kind of language is not allowed through our form.<br /><br />");
			}

			else if( $_POST['password2'] != $_POST['password'] )
			{
				exit("<h1>Error</h1>\nThe passwords you entered do not match, please go back and make sure they are they same.");
			}

			else if( $num_check1 != 0 )
			{
				exit("<h1>Error</h1>\nSomeone has already signed up with that email address. Please go back and use another email address. If you are a current member and have lost your password, please <a href=\"account.php?do=lostpass\">reset</a> your password.");
			}

			else if( $num_check2 != 0 )
			{
				exit("<h1>Error</h1>\nSomeone has already joined $tcgname with that name. Please go back and use another name.");
			}

			$_POST[$key] = stripslashes(strip_tags($value));
		}
		return true;
	}


	function Donation()
	{
		$database = new Database;
		$sanitize = new Sanitize;

		$exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i";
		$profanity = "/(beastial|bestial|blowjob|clit|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|fag|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|jism|jiz|kock|kondum|kum|kunilingus|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx)/i";
		$spamwords = "/(viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|texas|holdem|carisoprodol|ciara|ciprofloxacin|debt|dating|porn)/i";
		$bots = "/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer|Yandex)/i";

		if( preg_match($bots, $_SERVER['HTTP_USER_AGENT']) )
		{
			exit("<h1>Error</h1>\nKnown spam bots are not allowed.<br /><br />");
		}

		foreach( $_POST as $key => $value )
		{
			$num_chk = $database->num_rows("SELECT * FROM `tcg_donations` WHERE `deck_filename`='".$_POST['deckname']."'");
			$value = trim($value);

			if( preg_match($exploits, $value) )
			{
				exit("<h1>Error</h1>\n<p>Exploits/malicious scripting attributes aren't allowed.</p>");
			}

			else if( preg_match($profanity, $value) || preg_match($spamwords, $value) )
			{
				exit("<h1>Error</h1>\n<p>That kind of language is not allowed through this form.</p>");
			}

			else if( $num_chk != 0 )
			{
				exit("<h1>Error</h1>\n<p>Someone has already claimed that deck! Please choose another nature-related subject to claim, thank you!</p>");
			}

			$_POST[$key] = stripslashes(strip_tags($value));
		}
		return true;
	}
}
?>