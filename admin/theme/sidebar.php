<?php

echo '<div id="sideBar">
	<a href="index.php" class="logo">
		<span class="logo-sm"></span><span class="logo-lg"></span>
	</a>

	<div id="accordion" class="accordion">';
	if( $row['usr_role'] == "1" || $row['usr_role'] == "2" )
	{
		echo '<h6>Navigation</h6>
		<a class="main-link" tabindex="1" data-toggle="collapse" href="#dashboards" data-target="#dashboards" aria-expanded="false" aria-controls="dashboards" id="headingOne">
			<i class="bi-house-door" role="img"></i> Dashboards
		</a>
		<div class="collapse" id="dashboards" aria-labelledby="headingOne" data-parent="#accordion">
			<div id="child1">
				<li><a class="sub-link" tabindex="2" href="" data-toggle="collapse" data-target="#collapseOneA">Contents</a></li>
				<div class="child collapse" data-parent="#child1" id="collapseOneA">
					<li><a href="content.php?mod=cards">Cards</a></li>
					<li><a href="content.php?mod=posts">Posts</a></li>
					<li><a href="content.php?mod=pages">Pages</a></li>
					<li><a href="content.php?mod=games">Games</a></li>
				</div>
				<li><a class="sub-link" tabindex="2" href="" data-toggle="collapse" data-target="#collapseOneB">People</a></li>
				<div class="child collapse" data-parent="#child1" id="collapseOneB">
					<li><a href="people.php?mod=members">Members</a></li>
					<li><a href="people.php?mod=affiliates">Affiliates</a></li>';
					if( $settings->getValue( 'tcg_status' ) == "Upcoming" || $settings->getValue( 'tcg_status' ) == "Prejoin" )
					{
						echo '<li><a href="people.php?mod=prejoin">Prejoin Rewards</a></li>';
					}
				echo '</div>
			</div>
		</div>
		
		<a class="main-link" tabindex="1" data-toggle="collapse" href="#administration" data-target="#administration" aria-expanded="false" aria-controls="administration" id="headingThree">
			<i class="bi-shield-plus" role="img"></i> Administration
		</a>
		<div class="collapse" id="administration" aria-labelledby="headingThree" data-parent="#accordion">
			<li><a href="content.php?mod=cards&page=upcoming&action=add">Upcoming Deck</a></li>
			<li><a href="content.php?mod=posts&action=add">Blog Post</a></li>
			<li><a href="content.php?mod=pages&action=add">Page Content</a></li>
			<li><a href="people.php?mod=members&action=add">Member</a></li>
			<li><a href="content.php?mod=games&action=add">Games</a></li>
			<li><a href="people.php?mod=affiliates&action=add">Affiliate</a></li>
		</div>
		
		<h6>Settings</h6>
		<a class="main-link" tabindex="1" data-toggle="collapse" href="#configuration" data-target="#configuration" aria-expanded="false" aria-controls="configuration" id="headingFour">
			<i class="bi-tools" role="img"></i> Configuration
		</a>
		<div class="collapse" id="configuration" aria-labelledby="headingFour" data-parent="#accordion">
			<li><a href="settings.php?mod=general">General Settings</a></li>
			<li><a href="settings.php?mod=paths">File Paths</a></li>
			<li><a href="settings.php?mod=cards">Cards Settings</a></li>
			<li><a href="settings.php?mod=rewards">Rewards</a></li>
			<li><a href="settings.php?mod=others">Others</a></li>
		</div>
		<a class="main-link" tabindex="1" data-toggle="collapse" href="#user-settings" data-target="#user-settings" aria-expanded="false" aria-controls="user-settings" id="headingFive">
			<i class="bi-people" role="img"></i> User Settings
		</a>
		<div class="collapse" id="user-settings" aria-labelledby="headingFive" data-parent="#accordion">
			<li><a href="people.php?mod=members&page=levels">User Levels</a></li>
			<li><a href="people.php?mod=members&page=roles">User Roles</a></li>
			<li><a href="people.php?mod=members&page=tasks">Member Deck Tasks</a></li>
		</div>
		<a class="main-link" tabindex="1" href="content.php?mod=cards&page=categories"><i class="bi-folder-plus" role="img"></i> Card Categories</a>
		<a class="main-link" tabindex="1" href="content.php?mod=cards&page=sets"><i class="bi-box-seam" role="img"></i> Card Set/Series</a>
		
		<h6>Applications</h6>
		<a class="main-link" tabindex="1" data-toggle="collapse" href="#categories" data-target="#categories" aria-expanded="false" aria-controls="categories" id="headingSix">
			<i class="bi-folder2-open" role="img"></i> Categories
		</a>
		<div class="collapse" id="categories" aria-labelledby="headingSix" data-parent="#accordion">';
			$sql = $database->query("SELECT * FROM `tcg_cards_cat`");
			while( $get = mysqli_fetch_assoc( $sql ) )
			{
				echo '<li><a href="content.php?mod=cards&page=decks&id='.$get['cat_id'].'"><span class="fas fa-tag" aria-hidden="true"></span> '.$get['cat_name'].'</a></li>';
			}
			echo '<li><a href="content.php?mod=cards&page=decks"><span class="fas fa-tag" aria-hidden="true"></span> All</a></li>
		</div>

		<a class="main-link" tabindex="1" data-toggle="collapse" href="#shoppe" data-target="#shoppe" aria-expanded="false" aria-controls="shoppe" id="headingSeven">
			<i class="bi-cart3" role="img"></i> Shoppe
		</a>
		<div class="collapse" id="shoppe" aria-labelledby="headingSeven" data-parent="#accordion">
			<li><a href="shoppe.php">Inventory</a></li>
			<li><a href="shoppe.php?mod=catalog">Catalog</a></li>
			<li><a href="shoppe.php?mod=category">Categories</a></li>
			<li><a href="shoppe.php?mod=items&action=add">Add an Item</a></li>
		</div>
		<a class="main-link" tabindex="1" href="content.php?mod=uploads"><i class="bi-upload" role="img"></i> Uploads</a>
		<a class="main-link" tabindex="1" href="settings.php?mod=plugins"><i class="bi-plugin" role="img"></i> Plugins</a>';
		if( $settings->getValue( 'xtra_mdeck_en' ) == "0" ) {}
		else
		{
            echo '<a class="main-link" tabindex="1" href="people.php?mod=members&page=tasks"><i class="bi-card-checklist" role="img"></i> Member Tasks</a>';
		}

		echo '<h6>Components</h6>
		<a class="main-link" tabindex="1" href="people.php?mod=members&page=badges"><i class="bi-award" role="img"></i> Level Badges</a>
		<a class="main-link" tabindex="1" href="settings.php?mod=tcg-items"><i class="bi-dice-5" role="img"></i> TCG Items</a>
		<a class="main-link" tabindex="1" href="people.php?mod=members&page=freebies"><i class="bi-gift" role="img"></i> Freebies</a>
		<a class="main-link" tabindex="1" href="people.php?mod=members&page=wishes"><i class="bi-stars" role="img"></i> Wishes</a>';
		$chk = $database->num_rows("SHOW TABLES LIKE 'tcg_chatbox'");
		if( $chk >= 1 )
		{
			echo '<a class="main-link" tabindex="1" href="content.php?mod=chatbox"><i class="bi-chat-quote" role="img"></i> Chatbox</a>';
		}
	}

	else if( $row['usr_role'] == "3" || $row['usr_role'] == "4" )
	{
		echo '<h6>Navigation</h6>
		<a class="main-link" tabindex="1" data-toggle="collapse" href="#dashboards" data-target="#dashboards" aria-expanded="false" aria-controls="dashboards" id="headingOne">
			<i class="bi-house-door" role="img"></i> Dashboards
		</a>
		<div class="collapse" id="dashboards" aria-labelledby="headingOne" data-parent="#accordion">
			<div id="child1">
				<li><a class="sub-link" tabindex="2" href="" data-toggle="collapse" data-target="#collapseOneA">Contents</a></li>
				<div class="child collapse" data-parent="#child1" id="collapseOneA">
					<li><a href="content.php?mod=cards">Cards</a></li>
					<li><a href="content.php?mod=posts">Posts</a></li>
					<li><a href="content.php?mod=pages">Pages</a></li>
				</div>
				<li><a class="sub-link" tabindex="2" href="" data-toggle="collapse" data-target="#collapseOneB">People</a></li>
				<div class="child collapse" data-parent="#child1" id="collapseOneB">
					<li><a href="people.php?mod=members">Members</a></li>
					<li><a href="people.php?mod=affiliates">Affiliates</a></li>
				</div>
			</div>
		</div>

		<h6>Applications</h6>
		<a class="main-link" tabindex="1" href="content.php?mod=uploads"><i class="bi-upload" role="img"></i> Uploads</a>

		<h6>Components</h6>
		<a class="main-link" tabindex="1" href="content.php?mod=cards&page=events"><i class="bi-card-image" role="img"></i> Event Cards</a>
		<a class="main-link" tabindex="1" href="people.php?mod=members&page=freebies"><i class="bi-gift" role="img"></i> Freebies</a>
		<a class="main-link" tabindex="1" href="people.php?mod=members&page=wishes"><i class="bi-stars" role="img"></i> Wishes</a>';
	}

	else if( $row['usr_role'] == "5" )
	{
		echo '<h6>Navigation</h6>
		<a class="main-link" tabindex="1" data-toggle="collapse" href="#dashboards" data-target="#dashboards" aria-expanded="false" aria-controls="dashboards" id="headingOne">
			<i class="bi-house-door" role="img"></i> Dashboards
		</a>
		<div class="collapse" id="dashboards" aria-labelledby="headingOne" data-parent="#accordion">
			<div id="child1">
				<li><a class="sub-link" tabindex="2" href="" data-toggle="collapse" data-target="#collapseOneA">Contents</a></li>
				<div class="child collapse" data-parent="#child1" id="collapseOneA">
					<li><a href="content.php?mod=cards">Cards</a></li>
					<li><a href="content.php?mod=cards&page=upcoming&action=add">Add Upcoming Deck</a></li>
					<li><a href="content.php?mod=uploads">Uploads</a></li>
				</div>
			</div>
		</div>';
	}
	echo '</div><!-- #accordion -->
</div>';