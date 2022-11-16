<?php
/********************************************
 * Action:			Plugins Main
 * Description:		Show main page of plugins
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>Plugins</h1>
<p>You can install or uninstall any plugins that you may or may not need here.<br />
Please be careful though, <font color="red">uninstalling a plugin will delete all database records and tables, so make sure to backup your database first</font>.</p>

<table width="100%" cellspacing="0" class="table table-bordered table-striped">
<thead><tr>
	<td width="15%">Plugin</td>
	<td width="55%">Information</td>
	<td width="10%">Action</td>
</tr></thead>
<tbody>';

// Check for chatbox plugin
$res2 = $database->num_rows("SHOW TABLES LIKE 'tcg_chatbox'");
echo '<tr>
	<td align="center">Chatbox</td>
	<td>A simple chatbox that you can use for interaction.</td>';
	if( $res2 >= 1 )
	{
		echo '<td align="center"><button onclick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=uninstall&id=chatbox\';" class="btn-cancel" />Uninstall</button></td>';
	}

	else
	{
		echo '<td align="center"><button onclick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=install&id=chatbox\';" class="btn-success" />Install</button></td>';
	}
echo '</tr>';

// Check for MOTW/MOTM plugin
$res4 = $database->num_rows("SHOW TABLES LIKE 'game_motm_logs'");
echo '<tr>
	<td align="center">Featured Member</td>
	<td>Showcase your most active member via voting system.</td>';
	if( $res4 >= 1 )
	{
		echo '<td align="center"><button onclick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=uninstall&id=motm\';" class="btn-cancel" />Uninstall</button></td>';
	}

	else
	{
		echo '<td align="center"><button onclick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=install&id=motm\';" class="btn-success" />Install</button></td>';
	}
echo '</tr>';

// Check for Member Deck plugin
$res5 = $database->num_rows("SHOW TABLES LIKE 'tcg_cards_user'");
echo '<tr>
	<td align="center">Member Decks</td>
	<td>Allow your members to create their member decks.</td>';
	if( $res5 >= 1 )
	{
		echo '<td align="center"><button onclick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=uninstall&id=mdeck\';" class="btn-cancel" />Uninstall</button></td>';
	}

	else
	{
		echo '<td align="center"><button onclick="window.location.href=\''.$tcgurl.'admin/settings.php?mod='.$mod.'&action=install&id=mdeck\';" class="btn-success" />Install</button></td>';
	}
echo '</tr>
</tbody>
</table>';
?>