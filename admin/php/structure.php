<div class="wrap wpmarketing <?php echo $wpmarketing["status"]; ?>" data-ctas-length="0" data-plugins_url="<?php echo plugins_url("wpmarketing/"); ?>">
	<?php require_once "header.php"; ?>
	<?php require_once "remetric.php"; ?>
	
	<div class="metabox-holder">
		<div class="postbox-container">
		
			<div class="postbox">
				<div class="give_space" data-wpm-loading>
					Loading WPMarketing...<br><br>
					<img src="<?php echo plugins_url("wp-marketing/admin/imgs/loading.gif"); ?>">
				</div>
				<div class="inside" data-wpm-layout></div>
			</div>
			
		</div>
	</div>
</div>
<script id="wpmarketing_field" type="text/x-handlebars-template">
	<tr class="cta_field_row">
		<td><input type="checkbox" name="fields[][required]" value="true" {{#if required}}checked="checked"{{/if}}></td>
		<td>
			<select name="fields[][type]">
				<option value="text"			{{#is type "text"		 }}selected="selected"{{/is}}>Text</option>
				<option value="email"			{{#is type "email"	 }}selected="selected"{{/is}}>Email</option>
				<option value="tel"				{{#is type "tel"		 }}selected="selected"{{/is}}>Telephone</option>
				<option value="textarea"	{{#is type "textarea"}}selected="selected"{{/is}}>Large Text</option>
				<option value="password"	{{#is type "password"}}selected="selected"{{/is}}>Password</option>
				<option value="checkbox"	{{#is type "checkbox"}}selected="selected"{{/is}}>Checkboxes</option>
				<option value="select"		{{#is type "select"	 }}selected="selected"{{/is}}>Dropdown List</option>
			</select>
		</td>
		<td><input type="text" name="fields[][label]" value="{{ label }}"></td>
		<td><input type="text" name="fields[][key]" value="{{ key }}"></td>
		<td><input type="text" name="fields[][placeholder]" value="{{ placeholder }}"></td>
		<td style="width: 50px; text-align: center">
			<a href="#" class="move_field">&varr;</a>
			<a href="#" class="delete_field dashicons dashicons-trash"></a>
		</td>
	</tr>
</script>
<script id="wpmarketing_tab_settings" type="text/x-handlebars-template">
	{{{ header }}}

	{{#unless id}}
		<div class="tab not_for_unlocked">
			<p>The free version of WP Marketing has a 1-CTA limit. Now, its time to graduate to WP Marketing Guru by unlocking the plugin!</p>
			<p>When you unlock WP Marketing, you'll receive plugin updates, fast support, and more high-converting Call-To-Actions. To find out how to unlock WP Marketing, click on the button below:</p>
			<div class="button_wrapper">
				<a href="#!/unlock" class="button button-primary button-hero orange">Learn How to Unlock WPMarketing Now!</a>
			</div>
		</div>
	{{/unless}}

	<div class="tab {{#unless id}}not_for_locked_over_one_cta{{/unless}}">
		<form id="cta_form" action="cta_update" class="wpmarketing_form">
			<input type="hidden" name="id" value="{{ id }}">
			<input type="hidden" name="style" value="{{ style }}">
			<input type="hidden" name="position" value="{{ position }}">
			<input type="hidden" name="branded" value="false">
			<input type="hidden" name="cache_key" value="{{ cache_key }}" class="cache_key">
		
			<div class="loading give_space">
				<img src="<?php echo plugins_url("wp-marketing/admin/imgs/loading.gif"); ?>">
			</div>
		
			<div class="contents">
				
				<p class="cta_shortcode" data-field="container">
					[cta id={{id}}]
				</p>
				
				{{#if id}}
					<div class="field">
						<label for="name">What is the name of this CTA (for your own reference)?</label>
						<input type="text" name="name" id="name" value="{{ name }}">
					</div>
				{{else}}
					<input type="hidden" name="name" value="{{ name }}">
				{{/if}}
			
				<div class="field">
					<label for="action">What should this Call-To-Action (CTA) do?</label>
					<select name="action" id="action" class="cta_action">
						<option value="contact"				{{#is action "contact"}}selected="selected"{{/is}}							>Contact Form</option>
						<option value="subscription" 	{{#is action "subscription"}}selected="selected"{{/is}}					>Subscription Form</option>
						<option value="button" 				{{#is action "button"}}selected="selected"{{/is}} 							>Button Link</option>
						<option value="download" 			{{#is action "download"}}selected="selected"{{/is}} 						>Free Download</option>
						<option value="petition" 			{{#is action "petition"}}selected="selected"{{/is}} 						>Petition</option>
						<option value="social" 				{{#is action "social"}}selected="selected"{{/is}} 			disabled>Social Shares</option>
						<option value="callback" 			{{#is action "callback"}}selected="selected"{{/is}}			disabled>Call Me Back Button</option>
						<option value="appointment" 	{{#is action "appointment"}}selected="selected"{{/is}}	disabled>Appointment Booker</option>
						<option value="other" 				{{#is action "other"}}selected="selected"{{/is}} 				disabled>Poll</option>
						<option value="other" 				{{#is action "other"}}selected="selected"{{/is}} 				disabled>Survey</option>
						<option value="other" 				{{#is action "other"}}selected="selected"{{/is}} 				disabled>Live Chat</option>
						<option value="other" 				{{#is action "other"}}selected="selected"{{/is}} 				disabled>Euro Cookie Notice</option>
						<option value="other" 				{{#is action "other"}}selected="selected"{{/is}} 				disabled>Cobrowsing</option>
					</select>
				</div>
		
				<div class="field">
					<p>Where would you like your CTA to be located?</p>
				
					<ul class="cta_styles">
						<li data-title="Right Box" data-style="box" data-position="right" {{#is position "right"}}{{#is style "box"}}class="selected"{{/is}}{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/box_right.png"); ?>">
							Right Box
						</li>
						<li data-title="Left Box" data-style="box" data-position="left" {{#is position "left"}}{{#is style "box"}}class="selected"{{/is}}{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/box_left.png"); ?>">
							Left Box
						</li>
						<li data-title="Mid-Right Box" data-style="midbox" data-position="right" {{#is position "right"}}{{#is style "midbox"}}class="selected"{{/is}}{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/midbox_right.png"); ?>">
							Mid-Right Box
						</li>
						<li data-title="Mid-Left Box" data-style="midbox" data-position="left" {{#is position "left"}}{{#is style "midbox"}}class="selected"{{/is}}{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/midbox_left.png"); ?>">
							Mid-Left Box
						</li>
						<li data-title="Pop Over" data-style="dialog" {{#is style "dialog"}}class="selected"{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/dialog.png"); ?>">
							Pop Over
						</li>
						<li data-title="Top Bar" data-style="bar" data-position="top" {{#is position "top"}}{{#is style "bar"}}class="selected"{{/is}}{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/bar_top.png"); ?>">
							Top Bar
						</li>
						<li data-title="Bottom Bar" data-style="bar" data-position="bottom" {{#is position "bottom"}}{{#is style "bar"}}class="selected"{{/is}}{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/bar_bottom.png"); ?>">
							Bottom Bar
						</li>
						<li data-title="Inline" data-style="inline" {{#is style "inline"}}class="selected"{{/is}}>
							<img src="<?php echo plugins_url("wp-marketing/admin/imgs/ctas/inline.png"); ?>">
							Inline
						</li>
					</ul>
				</div>
				
				<div class="wpmarketing_clear"></div>
				
				<div class="field">
					<label>
						<input type="checkbox" name="disabled" id="disabled" value="1" {{#if disabled}}checked="checked"{{/if}}> Disable this CTA
					</label>
				</div>
				
				<div class="wpmarketing_clear"></div>
				
				<div class="nav-tab-wrapper cta_tabs">
					<a href="integrations"	class="nav-tab cta_show_tab" data-field="button">Integrations</a>
					<a href="actions" 			class="nav-tab cta_show_tab" data-field="button">Actions</a>
					<a href="visibility"		class="nav-tab cta_show_tab">Visibility</a>
					<a href="appearance"		class="nav-tab cta_show_tab">Appearance</a>
					<a href="setup"					class="nav-tab cta_show_tab selected">Setup</a>
					<div class="wpmarketing_clear"></div>
				</div>
				
				<div class="cta_tab cta_tab_setup selected">
					<div class="field" data-field="title">
						<label for="title">What is the headline?</label>
						<input type="text" name="title" id="title" class="full_width" value="{{ title }}">
					</div>
					
					<div class="field" data-field="description">
						<label for="description">What is the subheadline or content paragraph?</label>
						<textarea name="description" id="description" class="full_width">{{ description }}</textarea>
					</div>
					
					<div class="field" data-field="fields">
						<label>
							Which fields would like to include?
							<span class="hint">
								Select and checkbox fields are defined in the Placeholder, delimited by a semi-colon (;).<br>
								To mark a checkbox as checked, make the first character an asterisk (*).
								To use a label that's different from the value, separate it by a pipe (|).
							</span>
						</label>
						
						<table class="fields table">
							<thead>
								<tr>
									<th>Required</th>
									<th>Type</th>
									<th>Label</th>
									<th>Key</th>
									<th>Placeholder</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								{{#each fields}}
									{{{ html }}}
								{{/each}}
							</tbody>
						</table>
					
						<a href="#" class="button add_field">Add Field</a>
					</div>

					<div class="field" data-field="button">
						<label for="button">What should the submit button say?</label>
						<input type="text" name="button" id="button" class="full_width" value="{{ button }}">
					</div>
					
					<div class="field" data-field="social">
						<label for="social_networks">Which social networks would you like to include?</label>
						
						<ul>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Facebook
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Twitter
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Linked In
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Pinterest
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Reddit
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Digg
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Delicious
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="social[][share]" value="https://www.facebook.com/sharer/sharer.php?u={{url}}"> Evernote
								</label>
							</li>
						</ul>
					</div>
				</div>
				
				<div class="cta_tab cta_tab_appearance">
					<div class="field" data-field="closable">
						<input type="checkbox" name="closable" id="closable" value="1" {{#is closable "1"}}checked="checked"{{/is}}>
						<label for="closable" class="inline_block">Show a close button</label>
					</div>

					<div class="field" data-field="container">
						<label for="container">
							What html element are we placing the CTA into?
							<span class="hint">
								You can also use [cta id={{id}}] as a shortcode.
							</span>
						</label>
						<input type="text" name="container" id="container" value="{{ container }}">
					</div>
				
					<div class="field" data-field="sticky">
						<input type="checkbox" name="sticky" id="sticky" value="absolute" {{#is sticky "1"}}checked="checked"{{/is}}>
						<label for="sticky" class="inline_block">Don't scroll with the page</label>
					</div>
					
					<div class="field">
						<input type="checkbox" name="disable_css" id="disable_css" value="1" {{#is disable_css "1"}}checked="checked"{{/is}}>
						<label for="disable_css" class="inline_block">Disable CSS colours &amp; fonts</label>
					</div>
					
					<div class="css_fields" style="{{#is disable_css "1"}}display: none;{{/is}}">
						
						<div class="field half_field" data-field="heading_background">
							<label for="heading_background">Headline Background</label>
							<input type="text" name="appearance[heading][background]" id="heading_background" class="has_colourpicker" value="{{ appearance.heading.background }}">
						</div>

						<div class="field half_field">
							<label for="heading_colour">Headline Text Colour</label>
							<input type="text" name="appearance[heading][color]" id="heading_colour" class="has_colourpicker" value="{{ appearance.heading.color }}">
						</div>
						
						<div class="wpmarketing_clear" data-field="background"></div>
						
						<div class="field half_field" data-field="background">
							<label for="background">Background</label>
							<input type="text" name="appearance[background]" id="background" class="has_colourpicker" value="{{ appearance.background }}">
						</div>

						<div class="field half_field">
							<label for="paragraph_colour">Paragraph Text Colour</label>
							<input type="text" name="appearance[paragraph][color]" id="paragraph_colour" value="{{ appearance.paragraph.color }}" class="has_colourpicker">
						</div>
					
						<div class="wpmarketing_clear" data-field="subheading_background"></div>
					
						<div class="field half_field" data-field="subheading_background">
							<label for="subheading_background">Subheadline Background</label>
							<input type="text" name="appearance[subheading][background]" id="subheading_background" class="has_colourpicker" value="{{ appearance.subheading.background }}">
						</div>
					
						<div class="field half_field">
							<label for="subheading_colour">Subheadline Text Colour</label>
							<input type="text" name="appearance[subheading][color]" id="subheading_colour" class="has_colourpicker" value="{{ appearance.subheading.color }}">
						</div>
					
						<div class="wpmarketing_clear"></div>

						<div class="field half_field">
							<label for="button_background">Button Background</label>
							<input type="text" name="appearance[button][background]" id="button_background" class="has_colourpicker" value="{{ appearance.button.background }}">
						</div>
					
						<div class="field half_field">
							<label for="button_colour">Button Text Colour</label>
							<input type="text" name="appearance[button][color]" id="button_colour" class="has_colourpicker" value="{{ appearance.button.color }}">
						</div>
					
						<div class="wpmarketing_clear"></div>
					
						<div class="field half_field">
							<label for="font">Font Family</label>
							<input type="text" name="appearance[font]" id="font" value="{{ appearance.font }}">
						</div>
						
						<div class="field half_field">
							<label for="font">Width</label>
							<input type="text" name="appearance[width]" id="width" value="{{ appearance.width }}">
						</div>
					
					</div>
					
					<div class="wpmarketing_clear"></div>
					
					<div class="field">
						<label for="button_class">What class should the button have (use a space to separate multiple classes)?</label>
						<input type="text" name="button_class" id="button_class" class="full_width" value="{{ button_class }}">
					</div>
				</div>
				
				<div class="cta_tab cta_tab_integrations" data-field="button">
					<div class="sync" data-field="sync">
						
						<p>There are a services that WPMarketing can connect with:</p>
						
						<div class="nav-tab-wrapper integrations_tabs">
							<a href="mailchimp"	class="nav-tab integrations_show_tab selected">Mailchimp</a>
							<a href="zendesk"		class="nav-tab integrations_show_tab">Zendesk</a>
							<a href="aweber"		class="nav-tab integrations_show_tab">Aweber</a>
							<div class="wpmarketing_clear"></div>
						</div>
						
						<div class="mailchimp integrations_tab integrations_tab_mailchimp selected {{#empty sync.mailchimp.lists}}is_loading{{/empty}}">
							{{#if sync.mailchimp.api_key}}
								<div class="loading give_space">
									Loading Mailchimp Lists...<br><br>
									<img src="<?php echo plugins_url("wp-marketing/admin/imgs/loading.gif"); ?>">
								</div>
						
								<div class="inner_contents">
									<div class="field">
										<label for="mailchimp_list_id">Which list would you like to add subscribers to?</label>
										<select name="sync[mailchimp][list_id]" class="mailchimp_list_id" id="mailchimp_list_id" data-list-id="{{ sync.mailchimp.list_id }}">
											<option value="">None</option>
											{{#each sync.mailchimp.lists}}
												<option value="{{ id }}" {{#is id ../sync.mailchimp.list_id}}selected="selected"{{/is}}>{{ name }}</option>
											{{/each}}
										</select>
									</div>
								</div>
							{{else}}
								<p>Your Mailchimp Account is not setup. Visit the <a href="#!/settings">Settings</a> page to add your API Key.</p>
							{{/if}}
						</div>
						
						<div class="zendesk integrations_tab integrations_tab_zendesk">
							{{#if sync.zendesk.api_key}}
								<div class="field">
									<input type="checkbox" name="sync[zendesk][sync]" id="sync_zendesk_sync" value="1" {{#is sync.zendesk.sync "1"}}checked="checked"{{/is}}>
									<label for="sync_zendesk_sync" class="inline_block">Send responses to Zendesk</label>
								</div>
							{{else}}
								<p>Your Zendesk Account is not setup. Visit the <a href="#!/settings">Settings</a> page to add your API Key.</p>
							{{/if}}
						</div>
						
						<div class="aweber integrations_tab integrations_tab_aweber">
							<div class="inner_contents">
								<div class="field">
									<label for="aweber_list_id">Which list would you like to add subscribers to?</label>
									<input type="text" name="sync[aweber][list_id]" id="aweber_list_id" value="{{ sync.aweber.list_id }}">
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="cta_tab cta_tab_actions" data-field="button">
					<div class="field half_field">
						<label for="text_response">What is the on-page thank you message?</label>
						<textarea name="text_response" id="text_response" style="height: 5em; width: 90%; ">{{ text_response }}</textarea>
					</div>
					
					<div class="field half_field">
						<label for="redirect" data-field="redirect">What URL should the visitor be redirected to (leave blank if none)?</label>
						<label for="redirect" data-field="download">What is free download URL?</label>
						<input type="text" name="redirect" id="redirect" class="full_width" value="{{ redirect }}">
					</div>
					
					<div class="wpmarketing_clear"></div>
					
					<div class="action_emails">
						{{#each emails}}
							{{{ html }}}
						{{/each}}
					</div>
					
					<a href="#" class="button add_email">Add Notification Email</a>
				</div>
				
				<div class="cta_tab cta_tab_visibility">					
					<div class="field" data-field="escapable">
						<input type="checkbox" name="overlay[escapable]" id="escapable" value="1" {{#is overlay.escapable "1"}}checked="checked"{{/is}}>
						<label for="escapable" class="inline_block">Visitors can close it by pressing ESC</label>
					</div>
				
					<div class="field" data-field="clickable">
						<input type="checkbox" name="overlay[clickable]" id="clickable" value="1" {{#is overlay.clickable "1"}}checked="checked"{{/is}}>
						<label for="clickable" class="inline_block">Visitors can close it by clicking on the background</label>
					</div>
					
					<div class="field">
						<input type="checkbox" name="mobile[hide]" id="mobile_hide" value="1" {{#is mobile.hide "1"}}checked="checked"{{/is}}>
						<label for="mobile_hide" class="inline_block">Hide for small mobile devices</label>
					</div>
				
					<div class="field">
						<input type="checkbox" name="tablet[hide]" id="tablet_hide" value="1" {{#is tablet.hide "1"}}checked="checked"{{/is}}>
						<label for="tablet_hide" class="inline_block">Hide for tablets</label>
					</div>
				
					<div class="field">
						<input type="checkbox" name="desktop[hide]" id="desktop_hide" value="1" {{#is desktop.hide "1"}}checked="checked"{{/is}}>
						<label for="desktop_hide" class="inline_block">Hide for desktops</label>
					</div>
				
					<div class="field half_field">
						<label for="pages_include">
							Which pages should SHOW this CTA?
							<span class="hint">
								Separate pages with commas and * for wildcard<br>Eg. /support/,help,blog/*
							</span>
						</label>
						<input type="text" name="pages[include]" id="pages_include" value="{{ pages.include }}{{#unless pages.include}}*{{/unless}}">
					</div>
				
					<div class="field half_field">
						<label for="pages_exclude">
							Which pages should HIDE this CTA?
							<span class="hint">
								Separate pages with commas and * for wildcard<br>Eg. /support/,help,blog/*
							</span>
						</label>
						<input type="text" name="pages[exclude]" id="pages_exclude" value="{{ pages.exclude }}">
					</div>
					
					<div class="wpmarketing_clear"></div>
					
					{{#each triggers}}
						<div class="field half_field">
							<label>
								What event should display this CTA?
							</label>
							<select name="triggers[][event]" class="trigger_event">
								<option value="load"		{{#is event "load"}}selected="selected"{{/is}}		>On page load</option>
								<option value="leave"		{{#is event "leave"}}selected="selected"{{/is}}		>Predicted page leave</option>
								<option value="scroll"	{{#is event "scroll"}}selected="selected"{{/is}}	>On scroll</option>
								<option value="manual"	{{#is event "manual"}}selected="selected"{{/is}}	>None (show manually or with widgets)</option>
							</select>
							<p class="hint" data-trigger-event="manual" {{#isnt event "manual"}}style="display: none; "{{/isnt}}>
								CTA.push(["show", "<span data-cache-key>{{ ../cache_key }}</span>"]);
							</p>
						</div>
				
						<div class="field half_field" data-not-trigger-event="manual" {{#is event "manual"}}style="display: none; "{{/is}}>
							<label for="trigger_expire">When should this CTA be shown to a visitor?</label>
							<select id="trigger_expire" name="triggers[][expire]" class="change_expire">
								<option value="-1"		{{#is expire "-1"}}selected="selected"{{/is}}		>Every time</option>
								<option value="0"			{{#is expire "0"}}selected="selected"{{/is}}		>Every time until they interact</option>
								<option value="9999"	{{#is expire "9999"}}selected="selected"{{/is}}	>Once</option>
								<option value="1"			{{#is expire "1"}}selected="selected"{{/is}}		>Once a day</option>
								<option value="7"			{{#is expire "7"}}selected="selected"{{/is}}		>Once a week</option>
								<option value="30"		{{#is expire "30"}}selected="selected"{{/is}}		>Once a month</option>
							</select>
						</div>
					
						<div class="wpmarketing_clear"></div>
					
						<div class="field half_field" data-trigger-event="load" style="{{#isnt event "load"}}display: none;{{/isnt}}">
							<label for="trigger_delay">How many seconds to delay before showing?</label>
							<input type="text" id="trigger_delay" name="triggers[][delay]" value="{{ delay }}">
						</div>
					
						<div class="field half_field" data-trigger-event="scroll" style="{{#isnt event "scroll"}}display: none;{{/isnt}}">
							<label for="trigger_scroll">How much should be scrolled before showing (eg. 500px or 50%)?</label>
							<input type="text" id="trigger_scroll" name="triggers[][scroll]" value="{{ scroll }}">
						</div>
					{{/each}}
				
				</div>
		
				<div class="wpmarketing_clear"></div>
				
				<div class="actions_field">
					{{#if id}}
						<input type="submit" name="commit" class="button button-primary" value="Save Changes">
						<a href="#" class="button duplicate_cta" data-id="{{ id }}">Duplicate</a>
						<a href="#" class="button delete_cta" data-id="{{ id }}">Delete</a>
					{{else}}
						<input type="submit" name="commit" class="button button-primary" value="Save &amp; Publish">
					{{/if}}
				</div>
			</div>
		</form>
	</div>
</script>
<script id="wpmarketing_header_ctas" type="text/x-handlebars-template">
	<h2 class="nav-tab-wrapper cta_{{ id }}">
		{{#unless id}}
			{{{ page.title }}}
			<a style="visibility: hidden; " class="nav-tab nav-tab-active">H</a>
		{{else}}
			<span data-name>{{ name }}</span>
		  <a href="#!/ctas/{{ id }}" class="nav-tab">Setup</a>
		  <a href="#!/ctas/{{ id }}/responses" class="nav-tab">Responses</a>
		  <!--<a href="#!/ctas/{{ id }}/overview" class="nav-tab">Overview</a>-->
		{{/unless}}
		<span class="wpmarketing_clear"></span>
	</h2>
</script>
<script id="wpmarketing_layout_ctas" type="text/x-handlebars-template">
	<div class="sidebar">
		<a href="#!/ctas/new" class="button button-primary button-hero orange">Create New CTA</a>
		<ul class="ctas">
			<li class="hold_place">
				<img src="<?php echo plugins_url("wp-marketing/admin/imgs/up_arrow.png"); ?>">
				<h5>Create Your First CTA</h5>
				<p>Add a Call-To-Action to your website to increase your leads and conversion potential.</p>
			</li>
			{{#each ctas}}
				<li class="cta_{{ id }}"><a href="#!/ctas/{{ id }}" data-name>{{ name }}</a></li>
			{{/each}}
		</ul>
	</div>

	<div class="main" data-wpm-tab>
		{{{ outlet }}}
	</div>

	<div class="wpmarketing_clear"></div>
</script>
<script id="wpmarketing_sidebar_cta" type="text/x-handlebars-template">
	<li class="cta_{{ id }}"><a href="#!/ctas/{{ id }}" data-name>{{ name }}</a></li>
</script>
<script id="wpmarketing_tab_overview" type="text/x-handlebars-template">
	{{{ header }}}
	
	<div class="tab">
		This is the overview.
	</div>
</script>
<script id="wpmarketing_response" type="text/x-handlebars-template">
	<tr class="response response_{{ id }}" data-id="{{ id }}">
		<td>
			{{#if data.name}}
				{{ data.name }}
			{{else}}
				{{ visitor.name }}
			{{/if}}
		</td>
		<td>
			{{#if data.email}}
				<a href="mailto:{{ data.email }}">{{ data.email }}</a>
			{{else}}
				<a href="mailto:{{ visitor.email }}">{{ visitor.email }}</a>
			{{/if}}
		</td>
		<!-- <td>{{ visitor.location }}</td>
		<td>{{ visitor.computer }}</td> -->
		<td>{{ created_at }}</td>
		<td><a href="#" class="show_response_details">Details</a></td>
		<td><a href="#" class="delete_response">Delete</a></td>
	</tr>
	<tr class="response_details response_details_{{ id }}">
		<td colspan="7">
			<ul style="line-height: 1em; ">
				{{#is data.action "button"}}
					<li>Button was clicked and redirected to <a href="{{data.redirect}}">{{data.redirect}}</a>.</li>
				{{else}}
					{{#eachProperty data}}
						{{#isnt key "cta_name"}}
							{{#isnt key "action"}}
								{{#is key "email"}}
									<li>{{titleize key}}: <a href="mailto:{{value}}">{{value}}</a></li>
								{{else}}
									<li>{{titleize key}}: {{value}}</li>
								{{/is}}
							{{/isnt}}
						{{/isnt}}
					{{/eachProperty}}
				{{/is}}
			</ul>
		</td>
	</tr>
</script>
<script id="wpmarketing_tab_responses" type="text/x-handlebars-template">
	{{{ header }}}
	
	<div class="tab">
		<p style="float: right; "><span class="responses_count">0</span> Responses</p>
		<p style="float: left; ">
			Start: <input type="text" class="has_datepicker responses_start" value="{{start}}"> &nbsp; 
			Finish: <input type="text" class="has_datepicker responses_finish" value="{{finish}}">
		</p>
		<table class="widefat responses">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<!-- <th>Location</th>
					<th>Computer</th> -->
					<th>Submitted At</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr class="is_loading">
					<td colspan="6" class="hold_place generous_spacing">
						<img src="<?php echo plugins_url("wp-marketing/admin/imgs/loading.gif"); ?>">
					</td>
				</tr>
				<tr class="no_responses">
					<td colspan="6" class="hold_place generous_spacing">
						No responses were found.
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</script>
<script id="wpmarketing_email" type="text/x-handlebars-template">
	<div class="action_email">
		<table>
			<tr>
				<td style="width: 20%; text-align: right; "><label>To:</label></td>
				<td><input type="text" class="full_width" name="emails[][to]" value="{{ to }}"></td>
			</tr>
			<tr>
				<td style="width: 20%; text-align: right; "><label>From:</label></td>
				<td><input type="text" class="full_width" name="emails[][from]" value="{{ from }}"></td>
			</tr>
			<tr>
				<td style="width: 20%; text-align: right; "><label>Subject:</label></td>
				<td><input type="text" class="full_width" name="emails[][subject]" value="{{ subject }}"></td>
			</tr>
			<tr>
				<td colspan="2"><textarea class="full_width" name="emails[][message]">{{ message }}</textarea></td>
			</tr>
		</table>
		<a href="#" class="delete_email float_right">Delete</a>
		<div class="wpmarketing_clear"></div>
	</div>
</script>
<?php if (isset($_REQUEST["nowp"])) { ?>
	<script type="text/javascript">
		jQuery(function($) {
			$(".end_marketingfocus").show();
			$(".start_marketingfocus").hide();
			$("#wpadminbar, #adminmenuwrap, #adminmenuback, #wpfooter").hide();
			$("#wpwrap, .wrap").css("margin", 0);
			$("#wpcontent").css("margin", "-40px 0 0 0")
			$("#wpbody-content").css("padding-bottom", 0);
			$(".wrap").css("padding", "10px")
		});
	</script>
<?php } ?>
<script id="wpmarketing_layout_settings" type="text/x-handlebars-template">
	<h2 class="nav-tab-wrapper">
		WPMarketing Settings
		<a style="visibility: hidden; " class="nav-tab nav-tab-active">H</a>
	</h2>
	
	<div class="tab unlock_tab" style="max-width: 600px; ">

		<form id="settings_form" action="settings_update" class="wpmarketing_form">
			
			<div class="loading give_space">
				<img src="<?php echo plugins_url("wp-marketing/admin/imgs/loading.gif"); ?>">
			</div>
			
			<div class="contents">
				
				<!-- <a href="#!/unlock" class="not_for_locked">Change My Unlock Code</a><br> -->
				
				<div class="field">
					<label for="mailchimp_api_key">What is your Mailchimp API Key?</p>
					<input type="text" class="full_width" name="sync[mailchimp][api_key]" id="mailchimp_api_key" value="{{ sync.mailchimp.api_key }}">
				</div>
				
				<div class="field">
					<label for="zendesk_api_key">What is your Zendesk API Key?</p>
					<input type="text" class="full_width" name="sync[zendesk][api_key]" id="zendesk_api_key" value="{{ sync.zendesk.api_key }}">
				</div>
				
				<div class="field">
					<label for="zendesk_subdomain">What is your Zendesk subdomain?</p>
					<input type="text" class="full_width" name="sync[zendesk][subdomain]" id="zendesk_subdomain" value="{{ sync.zendesk.subdomain }}">
				</div>
				
				<div class="field">
					<label for="zendesk_email">What is your Zendesk email address?</p>
					<input type="text" class="full_width" name="sync[zendesk][email]" id="zendesk_email" value="{{ sync.zendesk.email }}">
				</div>
				
				<div class="wpmarketing_clear"></div>
			
				<div class="actions_field">
					<input type="submit" name="commit" class="button button-primary" value="Save Settings">
				</div>
			
			</div>
		</form>
	
	</div>
</script>
<script id="wpmarketing_layout_unlock" type="text/x-handlebars-template">
	<h2 class="nav-tab-wrapper cta_{{ id }}">
		Unlock WPMarketing
		<a style="visibility: hidden; " class="nav-tab nav-tab-active">H</a>
	</h2>
	
	<div class="tab unlock_tab" style="max-width: 600px; ">

		<form class="unlock_code_form blue_form">
			<div class="initial">
				<p>
					Already have an unlock code?
				</p>
				<input type="text" name="unlock_code">
				<input type="submit" value="Unlock My Apps Now!" class="button button-large button-primary orange">
			</div>
			<div class="loading">
				<img src="<?php echo plugins_url("wp-marketing/admin/imgs/loading.gif"); ?>">
			</div>
			<div class="success">
				<p>
					You have successfully unlocked your WP Marketing apps!<br><br>
					<a href="#" class="go_home">&larr; Go Back to Apps</a>
				</p>
			</div>
		</form>

		<div class="change_unlock_code_form blue_form">
			<h2 style="margin: 0; padding: 0px; ">WPMarketing is Unlocked!</h2>
			<p>
				You're using unlock code: <strong class="unlock_code"><?php echo $wpmarketing["unlock_code"]; ?></strong><br><br>
				<a href="#" class="change_unlock_code">I want to use a different code.</a>
			</p>
		</div>
		
		<div class="not_for_unlocked">

		  <h2>How to unlock WP Marketing</h2>

			<p>Is there a marketer in the world who wouldn't want beautiful, high-converting lead-generators at their fingertips?</p>

		  <p>
				A <em>GREAT</em> marketing tool can have 100x more ROI than a <em>GOOD</em> marketing tool. If you're like us, you're always on the lookout for small investments that grow your business and your bottom line &mdash; <em>quickly</em>.
			</p>
			
			<p>
				WP Marketing is the perfect blend of tools to start connecting with your visitors and grow your social reach &mdash; <em>without breaking the bank</em>. We offer 3 comprehensive annual plans to fit all budgets.
			</p>
			
			<p>
				When visitors see our tools on your site, they'll think <em>you're</em> the WP marketing guru.
			</p>
			
			<p id="!/apps/upgrade/pricing">
				All of our plans include:
			</p>
		
			<ul class="checklist">
				<li>&check; &nbsp; 1 Year of First-Class Upgrades &amp; Support</li>
				<li>&check; &nbsp; Access to Premium CTAs &amp; Features</li>
				<li>&check; &nbsp; Maintenance &amp; Security Updates</li>
				<li>&check; &nbsp; New CTAs added regularly</li>
				<li>&check; &nbsp; Cancel at any time</li>
			</ul>

			<table class="table pricing_table">
				<thead>
					<tr>
						<th colspan="3"></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="plan">
							<p class="price">$99</p>
							<p class="site_count">1-Site License</p>
							<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NX9NZCDNRD3CC" target="_blank" class="button button-hero">Select Plan</a>
						</td>
						<td class="plan" data-annual-savings="176">
							<p class="price">$199</p>
							<p class="site_count">5-Site License</p>
							<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HSDMLTLJS3Z3S" target="_blank" class="button button-primary orange button-hero">Select Plan</a>
						</td>
						<td class="plan" data-annual-savings="356">
							<p class="price">$399</p>
							<p class="site_count">20-Site License</p>
							<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SA2LARZBRG46U" target="_blank" class="button button-hero">Select Plan</a>
						</td>
					</tr>
				</tr>
			</table>
			
			<p style="margin-top: -20px; text-align: right; ">
				Rather pay monthly? <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=W8RW46U72EAXU">We have a $19 monthly plan.</a><br>
				Need uber-bulk pricing? <a href="mailto:dallas@wpmarketing.guru">Email us.</a>
			</p>
			
			<!-- <p class="button_wrapper">
				<a href="#!/apps/upgrade/pricing" class="button button-primary button-hero green">Get Started&nbsp; &rarr;</a>
			</p> -->

			<h2>Frequently Asked Questions</h2>
			<ul class="faq">
				<li>
					<p class="question">What if I'm not satisfied with the purchase?</p>
					<p>Currently, our 1 CTA limit is the only restriction on our free WP Marketing plugin. This will give you a very good idea of the quality and extensibility of our plugin. If you do purchase and you're not 100% satisfied, we'll immediately refund you (and cancel future payments) within 15 days of purchase.</p>
				</li>
				<li>
					<p class="question">Do I have to sign a long-term contract?</p>
					<p>No. WP Marketing is a pay-as-you-go service. There are no long-term contracts or commitments on your part. When you purchase our annual subscription, then you simply pay year-to-year. When you cancel your subscription, you won't be billed again.</p>
				</li>
				<li>
					<p class="question">Do you have one-time or multi-year purchasing options?</p>
					<p>Not at the moment.</p>
				</li>
				<li>
					<p class="question">Do you have multi-site purchasing options?</p>
					<p>Not at the moment.</p>
				</li>
				<li>
					<p class="question">How often do you add new CTAs, features, or updated the plugin?</p>
					<p>Our feature releases aren't on a rigid schedule - we want to make sure they're incredibly useful, easy-to-use, and bug-free. Typically, they'll range from once a week to once a month.</p>
				</li>
			</ul>
		</div>
	
	</div>
</script>
<script id="wpmarketing_tab_index" type="text/x-handlebars-template">	
	<div class="section">
		<h3>What can you do with WPMarketing?</h3>
		<p>WPMarketing puts the tools in your hands to become an expert online marketer. With WPMarketing, we make it easy for marketers to add Call-To-Actions on their site <em>without the help of developers</em>.</p>
		<p>WPMarketing CTAs are useful to: collect contact information, offer a free download, opt-in to your newsletter, collect call-back phone numbers, and boost social sharing.</p>
		<p>Here's an overview of how to use WPMarketing CTAs:</p>
		<div class="youtube_wrapper">
			<a href="//www.youtube.com/embed/DF7mmEtl2mQ" target="_blank">
				<img src="<?php echo plugins_url("wp-marketing/admin/imgs/video.jpg"); ?>">
			</a>
		</div>
	</div>
	
	<div class="section not_for_unlocked">
		<h3>What am I missing out on by not unlocking WP Marketing?</h3>
		<p>WPMarketing, as a basic plugin, will always be free. Here are the best 2 reasons why its worthwhile to upgrade:</p>
		<ul>
			<li>The Free version of WPMarketing has a 1-CTA Limit. The Unlocked version allows unlimited Call-To-Actions.</li>
			<li>Free WPMarketing has limited access to our Call-To-Actions. The Unlocked version includes Social Shares, Call Me Back, Poll, and Comment Stream &mdash; to name a few.</li>
			<li>If you were to hire a freelancer to you a tool as flexible as WP Marketing, the cost would easily be <em>over $1000</em>. We've put our professionals to work to provide you with the best possible code for the best possible price.</li>
		</ul>
		<div class="button_wrapper">
			<a href="#!/unlock" class="button button-primary button-hero orange">Unlock WPMarketing Now!</a>
		</div>
	</div>
	
	<div class="section">
		<h3>What is a CTA?</h3>
		<p>CTA is short for "Call-To-Action". When you've explained your product or service, and developed trust in your audience, its time to get them to act. A Call-To-Action is your visitors' way to prove that you've won them over.</p>
		<p>CTAs have their roots in direct-response marketing, but they're more relevant, measurable, and vital on the web. With just a few clicks and keystrokes and first-class web technology, you can know what your visitors want and <em>why they want it</em>.
	</div>
</script>