		</div><!-- .content -->

		<div class="copyright">
			<div class="pull-left">
				&copy; 2016 - <?php echo date("Y"); ?> <a href="https://stackcms.dev/" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Visit Stack CMS Website">Stack <?php echo $version; ?></a> &bull; All rights reserved<br />
				Designed and coded with <span class="fas fa-coffee" aria-hidden="true"></span> and <span class="fas fa-heart" aria-hidden="true"></span>.
			</div>

			<div class="pull-right">
				<a href="https://board.stackcms.dev/" target="_blank"><span class="fas fa-globe" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Community Forum"></span></a>
				<a href="https://tracker.stackcms.dev/" target="_blank"><span class="fas fa-bug" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Bug Tracker"></span></a>
				<a href="https://github.com/stackcms/" target="_blank"><span class="fab fa-github-alt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Github Repository"></span></a>
				<a href="https://discord.gg/wF4ww6e6Vw" target="_blank"><span class="fab fa-discord" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Discord Server"></span></a>
				<a href="#Home"><span class="fas fa-arrow-up" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Back to top"></span></a>
			</div>
		</div><!-- .copyright -->
	</div><!-- #container -->
</div><!-- .container-fluid -->

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" scr="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>

<script>  
$(document).ready(function(){
	$('#admin-membersfreebies').DataTable();
	$('#admin-membersretired').DataTable();
	$('#admin-membersbadges').DataTable();
	$('#admin-membersactive').DataTable();
	$('#admin-memberslevels').DataTable();
	$('#admin-memberstrades').DataTable();
	$('#admin-membersroles').DataTable();
	$('#admin-memberstasks').DataTable();
	$('#admin-memberslogs').DataTable();

	$('#admin-wishespending').DataTable();
	$('#admin-wishesgranted').DataTable();

	$('#admin-prejoinmain').DataTable();
	$('#admin-tcgitems').DataTable();

	$('#admin-shoppeitems1').DataTable();
	$('#admin-shoppeitems2').DataTable();
	$('#admin-shoppeitems3').DataTable();
	$('#admin-shoppeitems4').DataTable();
	$('#admin-shoppeitems5').DataTable();
	$('#admin-shoppeitems6').DataTable();
	$('#admin-shoppeitems7').DataTable();
	$('#admin-shoppeitems8').DataTable();
	$('#admin-shoppeitems9').DataTable();
	$('#admin-shoppeitems10').DataTable();

    $('#admin-cardsupcoming').DataTable();
    $('#admin-cardsreleased').DataTable();
	$('#admin-cardsdonation').DataTable();
	$('#admin-cardspercat').DataTable();
	$('#admin-cardsevent').DataTable();
	$('#admin-cardscat').DataTable();
	$('#admin-cardsall').DataTable();

	$('#admin-activities').DataTable();
	$('#admin-pagesmain').DataTable();
	$('#admin-postsmain').DataTable();
	$('#admin-chatbox').DataTable();
	$('#admin-games').DataTable();
});
</script>
</body>
</html>