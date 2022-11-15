			</div><!-- /.content -->
	<!-- END CONTENT -->


	<!-- BEGIN FOOTER -->	
			<div class="sidebar">
				<?php @include($tcgpath.'themes/delta/sidebar.php'); ?>
			</div><!-- /#sidebar -->
		</div><!-- /#container -->

	</div><!-- /#wrapper -->
	
	<div id="copyright">
		<div class="credit"><?php echo $credits; ?> &bull; Theme by <a href="https://www.design-with.in/" target="_blank">Design Within</a></div>
		<div class="disclaimer">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.<br />Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla.</div>
	</div><!-- /#copyright -->
	<!-- END FOOTER -->
</div><!-- /alignment -->

<script>
function myFunction()
{
	var x = document.getElementById("menu");
	if(x.className === "navbar-fixed-top")
	{
		x.className += " responsive";
	}
	else
	{
		x.className = "navbar-fixed-top";
	}
}
</script>
</body>
</html>
