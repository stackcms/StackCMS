<?php
/******************************************************
 * Action:			Empty Content Action
 * Description:		Show main page of TCG page and post
 */


echo '<h1>Content</h1>
<p>The following tabs below shows the list of your TCG\'s blog post, page content, games and affiliates.</p>

<div class="box">
<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" href="#posts" data-toggle="tab" role="tab" aria-controls="posts" aria-selected="true">Posts</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#pages" data-toggle="tab" role="tab" aria-controls="pages" aria-selected="false">Pages</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#games" data-toggle="tab" role="tab" aria-controls="games" aria-selected="false">Games</a>
	</li>
</ul>

<div class="tab-content" id="myTabContent">
	<div id="posts" class="tab-pane fade show active" role="tabpanel" aria-labelledby="posts-tab">';
		@include($tcgpath.'admin/vendor/content/tabs/post.tab.php');
	echo '</div><!-- #post -->

	<div id="pages" class="tab-pane fade" role="tabpanel" aria-labelledby="pages-tab">';
		@include($tcgpath.'admin/vendor/content/tabs/page.tab.php');
	echo '</div><!-- #page -->

	<div id="games" class="tab-pane fade" role="tabpanel" aria-labelledby="games-tab">';
		@include($tcgpath.'admin/vendor/content/tabs/game.tab.php');
	echo '</div><!-- #game -->
</div><!-- tab-content -->
</div><!-- box -->';
?>