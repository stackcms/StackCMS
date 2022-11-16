<?php
/*******************************************************
 * Action:			Empty Settings Action
 * Description:		Show main page of settings main page
 */

if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


echo '<h1>Settings</h1>
<p>Change your TCG settings through this page.</p>

<ul class="tabs" data-persist="true">
	<li><a href="#general">General</a></li>
	<li><a href="#paths">File Paths</a></li>
	<li><a href="#cards">Cards</a></li>
	<li><a href="#rewards">Rewards</a></li>
	<li><a href="#others">Others</a></li>
</ul>

<div class="tabcontents" align="left">
	<div id="general">';
		@include($tcgpath.'admin/modules/settings/tabs/general.tab.php');
	echo '</div><!-- #general -->

	<div id="paths">';
		@include($tcgpath.'admin/modules/settings/tabs/paths.tab.php');
	echo '</div><!-- #paths -->

	<div id="cards">';
		@include($tcgpath.'admin/modules/settings/tabs/cards.tab.php');
	echo '</div><!-- #cards -->

	<div id="rewards">';
		@include($tcgpath.'admin/modules/settings/tabs/rewards.tab.php');
	echo '</div><!-- #rewards -->

	<div id="others">';
		@include($tcgpath.'admin/modules/settings/tabs/others.tab.php');
	echo '</div><!-- #others -->
</div><!-- .tabcontents -->';
?>