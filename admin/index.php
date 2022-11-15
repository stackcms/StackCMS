<?php
@include('class.lib.php');
@include($tcgpath.'admin/theme/header.php');

// Check if user is logged in
if( empty( $login ) )
{
	header('Location: '.$tcgurl.'account.php?do=login');
}

// Get prejoin_donation table data
$pd = $database->num_rows("SELECT * FROM `prejoin_donations` WHERE `deck_type`='Donations'");
$td = $database->num_rows("SELECT * FROM `tcg_cards`");
$pdTotal = $pd + $td;

// Get prejoin claimed decks
$cd = $database->num_rows("SELECT * FROM `prejoin_donations` WHERE `deck_type`='Claims'");

// Get sum of donated collaterals
$sumCol = 0;
$col = $database->query("SELECT * FROM `prejoin_record`");
while( $collateral = mysqli_fetch_assoc( $col ) )
{
    $sumCol += $collateral['usr_collaterals'];
}

// Get total sum of potential members
$pm = $database->num_rows("SELECT * FROM `prejoin_record`");

// Get total donations from top 3 donators
$ds = $database->query("SELECT * FROM `prejoin_record` ORDER BY `usr_cards` DESC LIMIT 3");

// Check user role before proceeding
if( $row['usr_role'] == 7 )
{
	header('Location: '.$tcgurl.'account.php');
}

else
{
	echo '<h1>Dashboard</h1>
	<p>Welcome to your '.$tcgname.'\'s administration panel, cap\'n! What would you like to do today?</p>';
	$activeCards = $database->num_rows("SELECT * FROM `tcg_cards` WHERE `card_status`='Active'");
	if( $activeCards != 20 )
	{
		echo '<center><div class="alert alert-primary" role="alert">
			<font color="red"><b>Notice:</b></font> Hello there, '.$tcgowner.'! Since you just installed the script, make sure to generate your starter pack and starter pack bonus first before opening for prejoin!<br />
			<a href="'.$tcgurl.'admin/people.php?mod=members&page=starter-pack">Click on this link</a> to get your starter pack and starter pack bonus!
		</div></center>';
	}
	else {}

	echo '<center>'.$upgradeNotice.'</center><br />

	<div class="container-fluid">
        <div class="row">
            <div class="col-9">
                <h5>TCG Overview</h5>
                <div class="flex">
                    <div>
                        <div class="row">
                            <div class="col">
                                <h6>Members</h6>
								<h3>'; $count->numAll('user_list','','usr',''); echo '</h3>
							</div>

							<div class="col-auto">
								<div class="icon"><i class="bi-people-fill" role="img"></i></div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<b>Active:</b><br />
								<b>Pending:</b><br />
								<b>Inactive:</b><br />
								<b>Hiatus:</b><br />
								<b>Retired:</b>
							</div>

							<div class="col-auto">';
								$count->numAll('user_list','Active','usr',''); echo '<br />';
								$count->numAll('user_list','Pending','usr',''); echo '<br />';
								$count->numAll('user_list','Inactive','usr',''); echo '<br />';
								$count->numAll('user_list','Hiatus','usr',''); echo '<br />';
								$count->numAll('user_list_quit','','usr','');
							echo '</div>
						</div>
					</div><!-- div 1 -->

					<div>
                        <div class="row">
                            <div class="col">
                                <h6>Released Decks</h6>
                                <h3>'; $count->numCards('Active','1'); echo '</h3>
                            </div>

                            <div class="col-auto">
                                <div class="icon"><i class="bi-card-image" role="img"></i></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <b>Upcoming:</b><br />
                                <b>Regular:</b><br />
                                <b>Special:</b><br />
                                <b>Cards:</b>
                            </div>

                            <div class="col-auto">';
								$count->numCards('Upcoming',''); echo '<br />';
								$count->numCards('','1'); echo '<br />';
								$count->numCards('','2'); echo '<br />';
								$count->countCards();
							echo '</div>
						</div>
					</div><!-- div 2 -->

					<div>
						<div class="row">
							<div class="col">
								<h6>Affiliates</h6>
								<h3>'; $count->numAll('tcg_affiliates','Active','aff',''); echo '</h3>
							</div>

							<div class="col-auto">
								<div class="icon"><i class="bi-globe2" role="img"></i></div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<b>Active:</b><br />
								<b>Pending:</b><br />
								<b>Inactive:</b><br />
								<b>Hiatus:</b><br />
								<b>Closed:</b>
							</div>

							<div class="col-auto">';
								$count->numAll('tcg_affiliates','Active','aff',''); echo '<br />';
								$count->numAll('tcg_affiliates','Pending','aff',''); echo '<br />';
								$count->numAll('tcg_affiliates','Inactive','aff',''); echo '<br />';
								$count->numAll('tcg_affiliates','Hiatus','aff',''); echo '<br />';
								$count->numAll('tcg_affiliates','Closed','aff','');
							echo '</div>
						</div>
					</div><!-- div 3 -->

					<div>
						<div class="row">
							<div class="col">
								<h6>Blog Posts</h6>
								<h3>'; $count->numAll('tcg_post','Published','post','post'); echo '</h3>
							</div>

							<div class="col-auto">
								<div class="icon"><i class="bi-rss" role="img"></i></div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<b>Published:</b><br />
								<b>Draft:</b><br />
								<b>Scheduled:</b>
							</div>

							<div class="col-auto">';
								$count->numAll('tcg_post','Published','post','post'); echo '<br />';
								$count->numAll('tcg_post','Draft','post','post'); echo '<br />';
								$count->numAll('tcg_post','Scheduled','post','post');
							echo '</div>
						</div>
					</div><!-- div 4 -->
				</div><!-- .flex -->
			</div>';

			// Show pre-prejoin data
			if( $settings->getValue( 'tcg_status' ) == "Upcoming" )
			{
				echo '<div class="col">
					<h5>Prejoin Overview</h5>
					<div class="flex">
						<div>
							<div class="row">
								<div class="col">
									<b>Claimed Decks:</b><br />
									<b>Donated Decks:</b><br />
									<b>Donated Items:</b><br />
									<b>Potential Members:</b>
								</div>

								<div class="col-auto">
									'.$cd.'<br />
									'.$pdTotal.'<br />
									'.$sumCol.'<br />
									'.$pm.'
								</div>
							</div>

							<br />

							<h6>Top 3 Donators</h6>
							<ol>';
							while( $d = mysqli_fetch_assoc( $ds ) )
                        	{
								echo '<li>'.$d['usr_name'].' ('.$d['usr_cards'].' decks + '.$d['usr_collaterals'].' items)</li>';
							}
							echo '</ol>
						</div><!-- div 1 -->
					</div><!-- .flex -->
				</div>';
			}

			// Show TCG data
			else
			{
				$dqc = $database->num_rows("SELECT * FROM `tcg_donations` WHERE `deck_type`='Claims'");
				$dqd = $database->num_rows("SELECT * FROM `tcg_donations` WHERE `deck_type`='Donations'");
				$dtd = $database->num_rows("SELECT * FROM `tcg_cards`");

				// Count masteries of all users including remasteries
				$countMastery = $database->query("SELECT `itm_masteries` FROM `user_items`");
				$countNone = $database->num_rows("SELECT `itm_masteries` FROM `user_items` WHERE `itm_masteries`='None'");
				$flush = null;
				$countMast = null;
				while( $row = mysqli_fetch_assoc( $countMastery ) )
				{
					$flush = explode(", ", $row['itm_masteries']);
					$countMast = count($flush) - $countNone;
				}

				echo '<div class="col">
					<h5>Statistics</h5>
					<div class="flex">
						<div>
							<div class="row">
								<div class="col">
									<b>Current Claims:</b><br />
									<b>Current Donations:</b><br />
									<b>Total Decks:</b><br />
									<b>Total Masteries:</b>
								</div>

								<div class="col-auto">
									'.$dqc.'<br />
									'.$dqd.'<br />
									'.$dtd.'<br />
									'.$countMast.'
								</div>
							</div>
						</div><!-- div 1 -->
					</div><!-- .flex -->
				</div>';
			}
		echo '</div><!-- .row 1 -->

		<br />

		<div class="row">
			<div class="col-2">
				<h5>Quick Links</h5>
				<center>
				<div class="quickLink">
					<center><div class="icon"><i class="bi-pencil-fill" role="img"></i></div>
					<a href="'.$tcgurl.'admin/content.php?mod=post&action=add">+ Blog</a></center>
				</div>

				<div class="quickLink">
					<center><div class="icon"><i class="bi-card-image" role="img"></i></div>
					<a href="'.$tcgurl.'admin/content.php?mod=cards&page=upcoming&action=add">+ Deck</a></center>
				</div>
					
				<div class="quickLink">
					<center><div class="icon"><i class="bi-person-fill" role="img"></i></div>
					<a href="'.$tcgurl.'admin/people.php?mod=members&action=add">+ Member</a></center>
				</div>

				<div class="quickLink">
					<center><div class="icon"><i class="bi-upload" role="img"></i></div>
					<a href="'.$tcgurl.'admin/content.php?mod=uploads">Uploads</a></center>
				</div>
				</center>
			</div><!-- .col-2 -->

			<div class="col">
                <div class="flex">
                    <div>
                        <h5><i class="bi-people" role="img"></i> Latest Members</h5>
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col" width="18%">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col" width="25%">Joined</th>
                                </tr>
                            </thead>
                            <tbody>';
							$ol_query = $database->query("SELECT * FROM `user_list` WHERE `usr_status`='Active' ORDER BY `usr_reg` DESC LIMIT 5");
							while( $row = mysqli_fetch_assoc( $ol_query ) )
							{
								echo '<tr>
								<td align="center"><b>'.$row['usr_name'].'</b></td>
								<td align="center"><a href="mailto:'.$row['usr_email'].'" target="_blank">'.$row['usr_email'].'</a></td>
								<td align="center">'.date("Y/m/d", strtotime($row['usr_reg'])).'</td>
								</tr>';
							}
							echo '</tbody>
						</table>
					</div>
				</div><!-- .flex -->
			</div><!-- .col 1 -->

			<div class="col">
                <div class="flex">
                    <div>
                        <h5><i class="bi-person-bounding-box" role="img"></i> Online Members</h5>
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col" width="25%">Name</th>
                                    <th scope="col">Online Since</th>
                                    <th scope="col" width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>';
							$ol_query = $database->query("SELECT * FROM `user_list` WHERE `usr_status`='Active' AND TIMESTAMPDIFF(MINUTE, usr_sess, NOW()) LIMIT 5");
							while( $row = mysqli_fetch_assoc( $ol_query ) )
							{
								echo '<tr>
								<td align="center"><b>'.$row['usr_name'].'</b></td>
								<td align="center">'.date("Y/m/d", strtotime($row['usr_sess'])).' at '.date("h:i:s A", strtotime($row['usr_sess'])).'</td>
								<td>
                                    <button class="bare" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=email&id='.$row['usr_id'].'\';" data-toggle="tooltip" data-placement="bottom" title="Send a Message"><i class="bi-chat-left-dots" role="img"></i></button>
                                    <button class="bare" onClick="window.location.href=\''.$tcgurl.'admin/people.php?mod=members&action=edit&id='.$row['usr_id'].'\';" data-toggle="tooltip" data-placement="bottom" title="Edit User"><i class="bi-pencil-square" role="img"></i></button>
                                    <button class="bare" onClick="window.location.href=\'\';" data-toggle="tooltip" data-placement="bottom" title="View Profile"><i class="bi-binoculars" role="img"></i></button>
                                </td>
								</tr>';
							}
							echo '<tbody>
						</table>
					</div>
				</div><!-- .flex -->
			</div><!-- .col 2 -->
		</div><!-- .row 2 -->

		<br />

        <div class="row">
            <div class="col">
                <div class="flex">
                    <div>
                        <h5><i class="bi-clock-history" role="img"></i> Recent Activity (<a href="'.$tcgurl.'admin/content.php?mod=activities">View all?</a>)</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Activity</th>
                                    <th scope="col" width="20%">Date</th>
                                </tr>
                            </thead>
                            <tbody>';
							$ol_query = $database->query("SELECT * FROM `tcg_activities` ORDER BY `act_id` DESC LIMIT 7");
							while( $row = mysqli_fetch_assoc( $ol_query ) )
							{
								echo '<tr>
								<td>'.$row['act_rec'].'</td>
								<td align="center">'.date("Y/m/d", strtotime($row['act_date'])).'</td>
								</tr>';
							}
							echo '<tbody>
						</table>
					</div>
				</div>
			</div><!-- .col 1 -->

			<div class="col">
				<div class="flex">
					<div>
						<h5><i class="bi-gear" role="img"></i> Stack Changelogs</h5>
						<p>To view the full list of this version\'s changelogs and its previous version, <a href="https://stackcms.dev/" target="_blank">visit our website</a>.</p>';
						$plugin->plugRSS();
					echo '</div>
				</div>
			</div><!-- .col 2 -->
		</div><!-- .row 3 -->
	</div><!-- .container-fluid -->';
}

@include($tcgpath.'admin/theme/footer.php');
?>