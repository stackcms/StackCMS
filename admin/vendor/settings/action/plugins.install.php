<?php
/****************************************************
 * Action:			Install Plugins
 * Description:		Show page for installing a plugin
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>Install a Plugin</h1>';

// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Process chatbox install
else if( $id == "chatbox" )
{
	if( isset( $_POST['install'] ) )
	{
		$create = $database->query("CREATE TABLE IF NOT EXISTS `tcg_chatbox` (
			`chat_id` int(11) NOT NULL AUTO_INCREMENT,
			`chat_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			`chat_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			`chat_msg` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
			`chat_date` date NOT NULL,
			PRIMARY KEY (`chat_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		if( !$create )
		{
			$error[] = "Sorry, there was an error and the chatbox table was not created. ".mysqli_error($create);
		}

		else
		{
			$success[] = "The chatbox table was successfully created.";
		}
	}

	echo '<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'admin/settings.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<p>Are you sure you want to install this plugin? You can uninstall it anytime.<br />
	Click on the button below to install the plugin:<br />
	<input type="submit" name="install" class="btn btn-success" value="Install"></p>
	</form>';
}

// Process MOTM install
else if( $id == "motm" )
{
	if( isset( $_POST['install'] ) )
	{
		$create = $database->query("CREATE TABLE IF NOT EXISTS `game_motm_logs` (
			`motm_id` int(11) NOT NULL AUTO_INCREMENT,
			`motm_user` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			`motm_votes` int(5) NOT NULL,
			`motm_scope` date NOT NULL,
			PRIMARY KEY (`motm_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		if( !$create )
		{
			$error[] = "Sorry, there was an error and the member feature table was not created. ".mysqli_error($create);
		}

		else
		{
			$user_motm = "CREATE TABLE IF NOT EXISTS `game_motm_list` (
				`motm_id` int(10) NOT NULL AUTO_INCREMENT,
				`motm_user` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
				`motm_votes` int(5) NOT NULL DEFAULT '0',
				PRIMARY KEY (`motm_id`)
			) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;";
			$database->query($user_motm);

			// Insert user list data to motm list
			$fetch = $database->query("SELECT * FROM `user_list` ORDER BY `usr_id` ASC");
			while( $row = mysqli_fetch_assoc( $fetch ) )
			{
				$database->query("INSERT INTO `game_motm_list` (`motm_user`) VALUES ('".$row['usr_name']."')");
			}
			$success[] = "The member feature table was successfully created.";
		}
	}

	echo '<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'admin/settings.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<p>Are you sure you want to install this plugin? You can uninstall it anytime.<br />
	Click on the button below to install the plugin:<br />
	<input type="submit" name="install" class="btn btn-success" value="Install"></p>
	</form>';
}

// Process Member Deck install
else if( $id == "mdeck" )
{
	if( isset( $_POST['install'] ) )
	{
		$create = $database->query("CREATE TABLE IF NOT EXISTS `tcg_cards_user` (
			`ud_id` int(11) NOT NULL AUTO_INCREMENT,
			`ud_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			`ud_deck` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
			`ud_color` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
			`ud_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			`ud_cards` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			`ud_count` int(2) NOT NULL,
			`ud_break` int(1) NOT NULL,
			`ud_task_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			`ud_proof_logs` longtext COLLATE utf8_unicode_ci NOT NULL,
			`ud_completed` int(1) NOT NULL,
			`ud_finished` date NOT NULL,
			PRIMARY KEY (`ud_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		if( !$create )
		{
			$error[] = "Sorry, there was an error and the member feature table was not created. ".mysqli_error($create);
		}

		else
		{
			$success[] = "The member feature table was successfully created.";
		}
	}

	echo '<center>';
	if( isset( $error ) )
	{
		foreach( $error as $msg )
		{
			echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
		}
	}

	if( isset( $success ) )
	{
		foreach( $success as $msg )
		{
			echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
		}
	}
	echo '</center>

	<form method="post" action="'.$tcgurl.'admin/settings.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<p>Are you sure you want to install this plugin? You can uninstall it anytime.<br />
	Click on the button below to install the plugin:<br />
	<input type="submit" name="install" class="btn btn-success" value="Install"></p>
	</form>';
}
?>