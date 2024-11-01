<div class="wpmarketing_header">
	<?php if ($wpmarketing["activated"]) { ?>
		<div class="top_right_nav">
			<a href="#!/ctas">Welcome</a> &nbsp; | &nbsp; 
			<a href="#!/settings">Settings</a> &nbsp; | &nbsp; 
			<a href="?page=wpmarketing<?php echo (isset($_GET["nowp"]) ? "" : "&nowp"); ?>"><?php echo (isset($_GET["nowp"]) ? "Exit" : "Start"); ?> Full Screen</a>
			<!-- <span class="not_for_unlocked"> &nbsp; | &nbsp; <a href="#!/unlock">Unlock WPMarketing</a></span>
			<span class="not_for_locked"> &nbsp; | &nbsp; <a href="?page=wpmarketing<?php echo (isset($_GET["nowp"]) ? "" : "&nowp"); ?>"><?php echo (isset($_GET["nowp"]) ? "Exit" : "Start"); ?> Full Screen</a></span> -->
		</div>
	<?php } ?>
	
	<h2 data-show="home">
		<a href="#!/ctas"><img src="<?php echo plugins_url("wp-marketing/admin/imgs/logo_black.png"); ?>" class="logo"></a>
	</h2>
	
	<?php if ($wpmarketing["activated"]) { ?>
		<p class="wpmarketing_warning not_for_unlocked" style="margin-bottom: 0">
			The free version of WPMarketing includes 1 CTA. To add more CTAs (and other cool features), <a href="#!/unlock">unlock WPMarketing</a>.
		</p>
	<?php } ?>
</div>