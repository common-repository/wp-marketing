<?php if (isset($just_activated)) { ?>

<script type="text/javascript">
	this._RM || (this._RM = []);
	_RM.push(["api_key", "CLpE1snqfBH2bi1TR0Lxdfvhr0"]);
  _RM.push(["track", {
    description: "{{ member.name }} installed {{ product }}",
    verb: "installed",
    product: "WP Marketing",
    wpm_version: "<?php echo $wpmarketing["version"]; ?>",
    member: {
  		name: "<?php echo $wpmarketing["subscriber_name"]; ?>",
      email: "<?php echo $wpmarketing["subscriber_email"]; ?>",
  		key: window.location.host + "-wpm",
      website: "<?php echo $wpmarketing["website"]; ?>",
      initiated: "WP Marketing",
      has_wpmarketing: 1
    }
  }]);
	
  (function() {
    var rm = document.createElement("script"); rm.type = "text/javascript";
		rm.async = true;  rm.src = "https://secure.remetric.com/track.js";
		var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(rm, s);
  })();
</script>

<?php } ?>