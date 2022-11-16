<?php
/*********************************************************
 * Action:			Uninstall Plugins
 * Description:		Show page for uninstalling a plugin
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>Uninstall a Plugin</h1>';

// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Process chatbox uninstall
else if( $id == "chatbox" )
{
	if( isset( $_POST['uninstall'] ) )
	{
		$delete = $database->query("DROP TABLE `tcg_chatbox`");

		if ( !$delete )
		{
			$error[] = "Sorry, there was an error and the chatbox table was not deleted. ".mysqli_error($delete);
		}

		else
		{
			$success[] = "The chatbox table was successfully deleted.";
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
	<p>Are you sure you want to uninstall this plugin? <b>This action can not be undone!</b><br />
	Click on the button below to uninstall the plugin:<br />
	<input type="submit" name="uninstall" class="btn btn-danger" value="Uninstall"></p>
	</form>';
}

// Process uninstall MOTM
else if( $id == "motm" )
{
	if( isset( $_POST['uninstall'] ) )
	{
		$delete = $database->query("DROP TABLE `game_motm_logs`");

		if( !$delete )
		{
			$error[] = "Sorry, there was an error and the member feature table was not deleted. ".mysqli_error($delete);
		}

		else
		{
			$database->query("DROP TABLE `game_motm_list`");
			$success[] = "The member feature table was successfully deleted.";
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
	<p>Are you sure you want to uninstall this plugin? <b>This action can not be undone!</b><br />
	Click on the button below to uninstall the plugin:<br />
	<input type="submit" name="uninstall" class="btn btn-danger" value="Uninstall"></p>
	</form>';
}

// Process uninstall Member Decks
else if( $id == "mdeck" )
{
	if( isset( $_POST['uninstall'] ) )
	{
		$delete = $database->query("DROP TABLE `tcg_cards_user`");

		if( !$delete )
		{
			$error[] = "Sorry, there was an error and the member feature table was not deleted. ".mysqli_error($delete);
		}

		else
		{
			$database->query("DROP TABLE `tcg_cards_user`");
			$success[] = "The member feature table was successfully deleted.";
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
	<p>Are you sure you want to uninstall this plugin? <b>This action can not be undone!</b><br />
	Click on the button below to uninstall the plugin:<br />
	<input type="submit" name="uninstall" class="btn btn-danger" value="Uninstall"></p>
	</form>';
}
?>