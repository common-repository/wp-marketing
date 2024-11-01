<div class="wrap wpmarketing locked" data-plugins_url="<?php echo plugins_url("wpmarketing/"); ?>">
	
	<?php require_once "header.php"; ?>
	
	<div class="metabox-holder">
		<div class="postbox-container">
			
			<div class="postbox">
				<h3>One More Step</h3>
				
				<div class="inside">
					<div class="tab">
						
						<p style="margin: 20px 10px;">We just need a little more information to activate WP Marketing.</p>

						<div class="inside">
							<form action="admin.php?page=wpmarketing" method="post" class="activation_form" accept-charset="utf-8">
								<?php if ($_POST) { ?>
									<p class="wpmarketing_error">You must fill in <strong>Your Name</strong> and provide a valid <strong>Email Address</strong> to use WP Marketing.</p>
								<?php } ?>
	
								<div class="field">
						      <label for="name">Your Name</label>
						      <input type="text" name="name" id="name" value="<?php if (isset($_POST["name"])) { echo $_POST["name"]; } ?>">
						    </div>

						    <div class="field">
						      <label for="email">Your Email</label>
						      <input type="text" name="email" id="email" value="<?php if (isset($_POST["email"])) { echo $_POST["email"]; } ?>">
						    </div>
	
								<div class="field">
									<p style="font-size: 10px; color: #aaa; width: 300px; margin: 30px 0 20px; ">&check; &nbsp; By activating WP Marketing, you are opting in to receive WP Marketing updates (new features, special offers).</p>
									<input type="submit" value="Start Using WP Marketing &nbsp; &nbsp; &rarr;" class="button button-primary button-hero orange">
								</div>
						  </form>
						</div>
						
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
</div>