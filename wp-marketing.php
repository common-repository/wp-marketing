<?php
/*

Plugin Name: WP Marketing
Plugin URI: http://WPMarketing.guru
Description: WP Marketing is a suite of high-converting tools that help you to engage your visitors, personalize customer connections, and boost your profits.
Version: 1.0.9
Contributors: dallas22ca
Author: Dallas Read
Author URI: http://www.DallasRead.com
Text Domain: wp-marketing
Tags: marketing, customer support, customer service, conversions, Call-To-Action, cta, hello bar, mailchimp, aweber, getresponse, subscribe, subscription, newsletter, sumo me, sumome, wp marketing
Requires at least: 3.6
Tested up to: 4.1
Stable tag: trunk
License: MIT

Copyright (c) 2014 Dallas Read.

ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(-1);
*/

class WPMarketing {
  public static $wpmarketing_instance;
	const version = "1.0.9";
	const db = 1.0;
	const debug = false;

  public static function init() {
    if ( is_null( self::$wpmarketing_instance ) ) { self::$wpmarketing_instance = new WPMarketing(); }
    return self::$wpmarketing_instance;
  }

  private function __construct() {
		define("WPMARKETING_ROOT", dirname(__FILE__));

		add_action( "admin_init", array( $this, "admin_init" ) );
    add_action( "admin_menu", array( $this, "menu_page" ) );
		add_action( "plugins_loaded", array( $this, "db_check" ) );
		add_action( "widgets_init", array( $this, "register_cta_widget" ) );

		add_action( "wp_enqueue_scripts", array( $this, "wp_enqueue_scripts") );
		add_action( "wp_head", array( $this, "wp_head" ) );

		add_action( "wp_ajax_mailchimp_lists", array( $this, "mailchimp_lists" ) );
		add_action( "wp_ajax_settings_update", array( $this, "settings_update" ) );
		add_action( "wp_ajax_unlock", array( $this, "unlock" ) );

		add_action( "wp_ajax_cta_update", array( $this, "cta_update" ) );
		add_action( "wp_ajax_cta_delete", array( $this, "cta_delete" ) );
		add_action( "wp_ajax_cta_duplicate", array( $this, "cta_duplicate" ) );

		add_action( "wp_ajax_ctas_fetch_all", array( $this, "ctas_fetch_all" ) );
		add_action( "wp_ajax_cta_submit", array( $this, "cta_submit" ) );
		add_action( "wp_ajax_cta_fetch_responses", array( $this, "cta_fetch_responses" ) );
		add_action( "wp_ajax_cta_delete_response", array( $this, "cta_delete_response" ) );
		add_action( "wp_ajax_cta_count_responses", array( $this, "cta_count_responses" ) );

		add_action( "wp_ajax_nopriv_ctas_fetch_all", array( $this, "ctas_fetch_all" ) );
		add_action( "wp_ajax_nopriv_cta_submit", array( $this, "cta_submit" ) );
		add_action( "wp_ajax_nopriv_cta_fetch_responses", array( $this, "cta_fetch_responses" ) );
		add_action( "wp_ajax_nopriv_cta_count_responses", array( $this, "cta_count_responses" ) );

		add_shortcode("response_counter", array( $this, "shortcode_response_counter" ) );
		add_shortcode("cta", array( $this, "shortcode_cta" ) );

		register_activation_hook( __FILE__, array( $this, "db_check" ) );
    register_uninstall_hook( __FILE__, array( $this, "uninstall" ) );
  }

	public static function admin_init() {
		if (WPMarketing::debug) {
			wp_register_script( "wpmarketing_admin", plugins_url("admin/js/script.js", __FILE__) );
			wp_register_script( "wpmarketing_handlebars", plugins_url("admin/js/vendor/handlebars.js", __FILE__) );
			wp_register_script( "wpmarketing_swag", plugins_url("admin/js/vendor/swag.min.js", __FILE__) );
			wp_register_script( "wpmarketing_serialize_json", plugins_url("admin/js/vendor/jquery.serializejson.min.js", __FILE__) );
			wp_enqueue_script( array( "jquery", "jquery-ui-sortable", "jquery-ui-datepicker", "wp-color-picker", "wpmarketing_swag", "wpmarketing_handlebars", "wpmarketing_serialize_json", "wpmarketing_admin" ) );
		} else {
			wp_register_script( "wpmarketing_admin", plugins_url("admin/js/script.min.js", __FILE__) );
			wp_enqueue_script( array( "jquery", "jquery-ui-sortable", "jquery-ui-datepicker", "wp-color-picker", "wpmarketing_admin" ) );
		}
	}

	public static function wp_enqueue_scripts() {
		wp_register_style( "ctajs", plugins_url("public/css/cta.min.css", __FILE__) );
		wp_register_style( "wpmarketing_frontend", plugins_url("public/css/style.min.css", __FILE__) );
		wp_enqueue_style( array( "wpmarketing_frontend", "ctajs" ) );

		wp_register_script( "wpmarketing_frontend", plugins_url("public/js/script.min.js", __FILE__) );
		wp_register_script( "ctajs", plugins_url("public/js/cta.min.js", __FILE__) );
		wp_enqueue_script( array( "jquery", "ctajs", "wpmarketing_frontend" ) );
	}

	public static function wp_head() {
		echo "<script type=\"text/javascript\">this.WPMW || (this.WPMW = {});
			WPMW.ajaxurl = '" . admin_url("admin-ajax.php") . "';
			WPMW.loader = '" . plugins_url("public/imgs/loading.gif", __FILE__) . "';
		</script>";

		if (WPMarketing::debug) {
			echo "<script type=\"text/javascript\">this.CTA || (this.CTA = []);CTA.push([\"debug\", true]);</script>";
		}
	}

  public static function menu_page() {
    add_menu_page( "WP Marketing", "Marketing", 7, "wpmarketing", array("WPMarketing", "admin_panel"), "dashicons-editor-expand", 25 );
  }

  public static function admin_panel() {
		global $wpdb;
		global $wpmarketing;
		global $just_activated;
		global $ctas_table;

		wp_register_style( "wpmarketing_admin", plugins_url("admin/css/style.min.css", __FILE__) );
		wp_enqueue_style( array( "wpmarketing_admin", "wp-color-picker" ) );

    WPMarketing::parse_params();
    $wpmarketing = WPMarketing::settings();
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";

		if ($wpmarketing["activated"]) {
			require_once "admin/php/structure.php";
		} else {
			require_once "admin/php/activate.php";
		}

		echo "<script type=\"text/javascript\">this.WPMW || (this.WPMW = {}); WPMW.settings = " . json_encode($wpmarketing) . ";</script>";
  }

	public static function wp_footer() {
		global $wpmarketing;
		$wpmarketing = WPMarketing::settings();

		if (array_key_exists("convert_alert_status", $wpmarketing) && $wpmarketing["convert_alert_status"] == "on") {
			require_once WPMARKETING_ROOT . "/public/php/apps/convert_alert.php";
		}
	}

	public static function db_check() {
		global $wpdb;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$events_table = $wpdb->prefix . "wpmarketing_events";
		$visitors_table = $wpdb->prefix . "wpmarketing_visitors";

		if (get_option("wpmarketing_db_version") != WPMarketing::db) {
			$charset_collate = '';
			if ( ! empty( $wpdb->charset ) ) { $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}"; }
			if ( ! empty( $wpdb->collate ) ) { $charset_collate .= " COLLATE {$wpdb->collate}"; }

			$ctas = "CREATE TABLE " . $ctas_table . " (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(150) NOT NULL,
				data text NOT NULL,
				created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

			$events = "CREATE TABLE " . $events_table . " (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				verb varchar(50) NOT NULL,
				description varchar(255),
				cta_id mediumint(9) NOT NULL,
				visitor_id mediumint(9) NOT NULL,
				data text NOT NULL,
				created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id),
				INDEX verb_idx (verb),
				INDEX visitor_idx (visitor_id),
				INDEX cta_idx (cta_id)
			) $charset_collate;";

			$visitors = "CREATE TABLE " . $visitors_table . " (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				user_id mediumint(9) NOT NULL,
				email varchar(155) NOT NULL,
				data text NOT NULL,
				created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id),
				INDEX user_idx (user_id),
				INDEX email_idx (email)
			) $charset_collate;";

			require_once( ABSPATH . "wp-admin/includes/upgrade.php" );
			dbDelta( $ctas );
			dbDelta( $events );
			dbDelta( $visitors );
			update_option( "wpmarketing_db_version", WPMarketing::db );
		}
	}

  public static function uninstall() {
		// global $wpdb;
		//
		// $ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		// $events_table = $wpdb->prefix . "wpmarketing_events";
		// $visitors_table = $wpdb->prefix . "wpmarketing_visitors";
		//
		// $wpdb->query("DROP TABLE IF EXISTS $ctas_table");
  	// $wpdb->query("DROP TABLE IF EXISTS $events_table");
  	// $wpdb->query("DROP TABLE IF EXISTS $visitors_table");
    //
		// delete_option("wpmarketing_settings");
  }

  public static function settings($update = array()) {
		global $wpmarketing;

    if (empty($wpmarketing) || !empty($update)) {
			$settings = get_option("wpmarketing_settings");
			if ($settings == null) { $settings = array(); }

	    $defaults = array(
	      "version" => WPMarketing::version,
	      "db_version" => 0,
				"website" => $_SERVER["SERVER_NAME"],
	      "unlock_code" => "",
	      "subscriber_name" => "",
	      "subscriber_email" => "",
				"trial_end_at" => 0,
				"status" => "unlocked"
	    );

			if (!empty($update) || $wpmarketing != $settings) {
				$wpmarketing = array_merge($defaults, $settings);
				$wpmarketing = array_merge($wpmarketing, $update);
				update_option("wpmarketing_settings", $wpmarketing);
			}

			$wpmarketing["activated"] = !(!isset($wpmarketing["subscriber_email"]) || $wpmarketing["subscriber_email"] == "");

			if (isset($wpmarketing["unlock_code"]) && $wpmarketing["unlock_code"] != "") {
				$wpmarketing["status"] = "unlocked";
			} else if (isset($wpmarketing["trial_end_at"]) && $wpmarketing["trial_end_at"] > time()) {
				$wpmarketing["status"] = "trialing";
			} else {
				$wpmarketing["status"] = "locked";
			}
		}

    return $wpmarketing;
  }

	public static function unlock() {
		$data = array( "success" => false );

    if (isset($_POST["unlock_code"])) {
			$unlock_code = trim($_POST["unlock_code"]);
      $request = new WP_Http;
      $result = $request->request("http://guitarvid.com/activation/wpmarketing/unlock.php?unlock_code=" . $unlock_code);
      $response = json_decode($result["body"]);

			if ($response->success == 1) {
        $data = WPMarketing::settings( array( "unlock_code" => $unlock_code ) );
				$data["success"] = true;
      }
		}

    die(json_encode($data));
	}

  public static function parse_params() {
		global $wpmarketing;
		global $just_activated;

    if (isset($_POST["email"]) && is_email($_POST["email"]) && isset($_POST["name"])) {
			WPMarketing::settings( array(
				"subscriber_name" => trim($_POST["name"]),
				"subscriber_email" => sanitize_email(trim($_POST["email"]))
			) );
			$just_activated = true;
		}
  }

	public static function start_free_trial() {
		global $wpmarketing;
		$data = array( "success" => false );

    if ($wpmarketing["trial_end_at"] == 0) {
      $data = WPMarketing::settings( array( "trial_end_at" => strtotime("+7 day") ) );
			$data["success"] = true;
		}

    die(json_encode($data));
	}

	public static function remote_ip() {
		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$remote_ip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$remote_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$remote_ip = $_SERVER["REMOTE_ADDR"];
		}
		if (inet_pton($remote_ip) === false) { $remote_ip = "0.0.0.0"; }
		return $remote_ip;
	}

	public static function request_path() {
	  $url = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://' . $_SERVER["SERVER_NAME"] : 'https://' . $_SERVER["SERVER_NAME"];
	  $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":" . $_SERVER["SERVER_PORT"] : "";
	  $url .= $_SERVER["REQUEST_URI"];
	  return $url;
	}

	public static function random_token() {
		return md5(uniqid(rand() * rand(), true));
	}

	public static function addslashes_deep($value) {
		if ( is_array($value) ) {
    	$value = array_map('addslashes_deep', $value);
		} elseif ( is_object($value) ) {
    	$vars = get_object_vars( $value );
    	foreach ($vars as $key=>$data) {
				$value->{$key} = addslashes_deep( $data );
			}
		} else {
	    $value = addslashes($value);
		}

		return $value;
	}

	/*
		CTAS
	*/

	public static function cta_update() {
		global $wpdb;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$cta = array();
		unset($_POST["action"]);

		if (isset($_POST["data"]["name"])) {
			$name = sanitize_text_field($_POST["data"]["name"]);
			unset($_POST["data"]["name"]);

			$cta["name"] = $name;
			$cta["data"] = addslashes(json_encode($_POST));
      $cta["created_at"] = current_time("mysql");
      $cta["updated_at"] = current_time("mysql");

			if ($_POST["data"]["id"] != "") {
				$cta["id"] = $_POST["data"]["id"];
				$response = $wpdb->update( $ctas_table, $cta, array( "id" => $cta["id"]) );
			} else {
				$response = $wpdb->insert( $ctas_table, $cta );
				$cta["id"] = $wpdb->insert_id;
				$cta["new"] = true;
			}
		}

		$cta["name"] = stripslashes($cta["name"]);
		$cta["data"] = stripslashes_deep(json_decode(stripslashes_deep($cta["data"]))->data);
		$cta["success"] = isset($response);
		$cta["errors"] = $wpdb->last_error;
		die(json_encode($cta));
	}

	public static function ctas_fetch_all() {
		global $wpdb;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$ctas = $wpdb->get_results("SELECT * FROM $ctas_table ORDER BY updated_at DESC", ARRAY_A);

		// GET VISITOR BY USER_ID
		// IF RESPONSE FOR VISITOR EXISTS && TRIGGER[EXPIRE] == "0", DON'T ADD

		foreach ($ctas as $key => $cta) {
			$ctas[$key]["data"] = stripslashes_deep(json_decode(stripslashes_deep($cta["data"]), true)["data"]);
			$ctas[$key]["name"] = stripslashes($ctas[$key]["name"]);
		}

		die(json_encode($ctas));
	}

	public static function cta_delete() {
		global $wpdb;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$data = array( "id" => $_POST["id"] );

		$response = $wpdb->delete( $ctas_table, $data );
		$data["success"] = $response != 0;

		die(json_encode($data));
	}

	public static function cta_duplicate() {
		global $wpdb;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$cta = $wpdb->get_row("SELECT * FROM $ctas_table WHERE id = $_POST[id]", ARRAY_A);
		unset($cta["id"]);

		$cta["name"] = stripslashes($cta["name"]) . " - Duplicate";
		$response = $wpdb->insert( $ctas_table, $cta );

		$cta["data"] = stripslashes_deep(json_decode(stripslashes_deep($cta["data"]))->data);
		$cta["id"] = $wpdb->insert_id;
		$cta["success"] = $response != 0;

		die(json_encode($cta));
	}

	public static function cta_submit() {
		global $wpmarketing;
		global $wpdb;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$events_table = $wpdb->prefix . "wpmarketing_events";
		$visitors_table = $wpdb->prefix . "wpmarketing_visitors";
		$current_user_id = get_current_user_id();
		$submittable = true;

		$submit = array(
			"verb" => "submit",
			"description" => "{{ contact.name }} submitted <a href=\"#!/ctas/{{ cta_id }}\">{{ cta_name }}</a> on <a href=\"{{ page.url }}\">{{ page.title }}</a>",
			"cta_id" => $_POST["cta_id"],
			"data" => isset($_POST["data"]) ? $_POST["data"] : array(),
			"created_at" => current_time("mysql")
		);

		if (isset($submit["data"]["email"])) { $email = $submit["data"]["email"]; }
		$cta = $wpdb->get_row("SELECT * FROM $ctas_table WHERE id = $submit[cta_id] LIMIT 1", ARRAY_A);
		$cta_data = json_decode(stripslashes_deep($cta["data"]), true);

		if ($cta_data["data"]["triggers"][0]["expire"] == "0") {
			$visitor = $wpdb->get_row("SELECT * FROM $visitors_table WHERE user_id = $current_user_id LIMIT 1", ARRAY_A);
			$results = $wpdb->get_var("SELECT COUNT(*) FROM $events_table WHERE visitor_id = $visitor[id] AND cta_id = $submit[cta_id]");

			if ($results > 0) {
				$submittable = false;
			}
		}

		if (!$submittable) {
			$submit["error"] = "You have already submitted this form. Your information has already been recorded.";
			$submit["success"] = false;
		} else {
			if ($cta != null) {
				$submit["data"]["cta_name"] = $cta["name"];
				$visitor = null;

				if ($current_user_id != 0) {
					$visitor = $wpdb->get_row("SELECT * FROM $visitors_table WHERE user_id = $current_user_id LIMIT 1", ARRAY_A);
				}

				if ($visitor == null && isset($email)) {
					$visitor = $wpdb->get_row("SELECT * FROM $visitors_table WHERE email = '$email' LIMIT 1", ARRAY_A);
				}

				if ($visitor == null) {
					$visitor_data_object = $submit["data"];
					unset($visitor_data_object["action"]);
					unset($visitor_data_object["redirect"]);
					$visitor = array(
						"user_id" => $current_user_id,
						"data" => addslashes(json_encode($visitor_data_object)),
						"created_at" => current_time("mysql"),
						"updated_at" => current_time("mysql")
					);
					if (isset($email)) { $visitor["email"] = $email; }

					if (isset($submit["data"])) {
						$wpdb->insert( $visitors_table, $visitor );
						$visitor["id"] = $wpdb->insert_id;
					}
				} else {
					$visitor["data"] = json_decode(stripslashes_deep($visitor["data"]), true);
					$visitor_data_object = array_merge($visitor["data"], $submit["data"]);
					unset($visitor_data_object["action"]);
					unset($visitor_data_object["redirect"]);
					$update = array(
						"data" => addslashes(json_encode($visitor_data_object)),
						"updated_at" => current_time("mysql")
					);
					if (isset($email)) { $visitor["email"] = $email; $update["email"] = $email; }
					$wpdb->update( $visitors_table, $update, array( "id" => $visitor["id"] ) );
				}

				$submit["visitor_id"] = $visitor["id"];
				$submit["data"] = addslashes(json_encode($submit["data"]));
				$response = $wpdb->insert( $events_table, $submit );
				$submit["success"] = $response != false;

				if ($submit["success"]) {
					$submit["data"] = json_decode(stripslashes_deep($submit["data"]), true);
					$submit["cta"] = $cta_data;
					$visitor["data"] = $visitor_data_object;
					$submit["visitor"] = $visitor;

					if ($current_user_id != 0) {
						update_user_meta( $current_user_id, "cta_submitted_$submit[cta_id]", true );
					}

					if (1 == 1) { //&& $wpmarketing["status"] == "unlocked"
						$submit["sync"] = array();
						$has_mailchimp = isset($submit["cta"]["data"]["sync"]["mailchimp"]["list_id"]);
						$has_zendesk = isset($submit["cta"]["data"]["sync"]["zendesk"]["sync"]);
						$has_aweber = isset($submit["cta"]["data"]["sync"]["aweber"]["list_id"]);
						$has_emails = isset($submit["cta"]["data"]["emails"]);
						$has_password = isset($visitor["data"]["password"]) && $visitor["data"]["password"] != "";
						$has_email = isset($visitor["data"]["email"]) && $visitor["data"]["email"] != "";

						if ($has_emails) {
							require "admin/php/vendor/Mustache/Autoloader.php";
							Mustache_Autoloader::register();

							foreach ($submit["cta"]["data"]["emails"] as $email) {
								WPMarketing::submit_notify($email, $visitor["data"]);
							}
						}

						if ($has_mailchimp) {
							$mailchimp_list_id = $submit["cta"]["data"]["sync"]["mailchimp"]["list_id"];

							if ($mailchimp_list_id != "") {
								$submit["sync"]["mailchimp"] = WPMarketing::mailchimp_subscribe($visitor["data"], $mailchimp_list_id);
							}
						}

						if ($has_zendesk) {
							$submit["sync"]["zendesk"] = WPMarketing::zendesk_subscribe($visitor["data"]);
						}

						if ($has_aweber) {
							$aweber_list_id = $submit["cta"]["data"]["sync"]["aweber"]["list_id"];

							if ($aweber_list_id != "") {
								$submit["sync"]["aweber"] = WPMarketing::aweber_subscribe($visitor["data"], $aweber_list_id);
							}
						}

						if ($has_password && $has_email) {
							// do_action( "cta_create_user", $visitor["data"] );
						}
					}
				}
			}

			$submit["success"] = $response != false;
			if (!$submit["success"]) { $submit["error"] = $wpdb->last_error; }
		}

		die(json_encode($submit));
	}

	public static function submit_notify($email, $visitor) {
		$visitor["data"] = "<table>";
		foreach ($_POST["data"] as $field => $value)
		{
      if ($field != "action" && $field != "Action")
      {
				if (is_array($value)) {
					$value = "<ul><li>" . implode("</li><li>", $value) . "</li></ul>";
				} else {
					$value = filter_var($value, FILTER_SANITIZE_STRING);
				}

  			$visitor["data"] .= "
          <tr>
            <td style=\"text-align:right; \">
              <strong>$field:</strong>
            </td>
            <td>$value</td>
          </tr>";
      }
		}
		$visitor["data"] .= "</table>";

		$m = new Mustache_Engine;
		$to = $m->render($email["to"], $visitor);
		$from = $m->render($email["from"], $visitor);
		$subject = $m->render($email["subject"], $visitor);
		$message = $m->render(nl2br(stripslashes($email["message"])), $visitor);

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: $from" . "\r\n";

		wp_mail($to, $subject, $message, $headers);
	}

	public static function cta_fetch_responses() {
		global $wpdb;
		$events_table = $wpdb->prefix . "wpmarketing_events";
		$visitors_table = $wpdb->prefix . "wpmarketing_visitors";

		$data = array();
		$data["cta_id"] = $_POST["cta_id"];

		if (isset($_POST["start"]) && isset($_POST["finish"])) {
			$data["start"] = date("Y-m-d H:i:s", strtotime($_POST["start"]));
			$data["finish"] = date("Y-m-d H:i:s", strtotime($_POST["finish"] . "+1 days"));

			$data["cta_sql"] = $wpdb->prepare(
				"SELECT * FROM $events_table WHERE verb = %s and cta_id = %s and created_at >= %s and created_at <= %s ORDER BY created_at DESC",
				"submit",
				$data["cta_id"],
				$data["start"],
				$data["finish"]
			);
		} else {
			$data["cta_sql"] = $wpdb->prepare(
				"SELECT * FROM $events_table WHERE verb = %s and cta_id = %s ORDER BY created_at DESC",
				"submit",
				$data["cta_id"]
			);
		}

		$data["responses"] = $wpdb->get_results( $data["cta_sql"], ARRAY_A);

		$data["visitor_ids"] = array_map(function($r) {
			return $r["visitor_id"];
		}, $data["responses"]);

		$data["visitors_sql"] = $wpdb->prepare("SELECT * FROM $visitors_table WHERE id IN(%s)", $data["visitor_ids"]);
		$data["visitors"] = $wpdb->get_results( $data["visitors_sql"], OBJECT_K);

		foreach ($data["responses"] as $key => $response) {
			$visitor = $data["visitors"][$response["visitor_id"]];
			$data["responses"][$key]["data"] = json_decode(stripslashes_deep($response["data"]));
			$data["responses"][$key]["visitor"] = json_decode(stripslashes_deep($visitor->data));

			if (isset($_REQUEST["include_user"]) && $visitor->user_id != 0) {
				$user = (array) get_userdata( $visitor->user_id );
				$user["meta"] = (array) get_user_meta( $visitor->user_id );
				$data["responses"][$key]["user"] = $user;
			}
		}

		$data["count"] = count($data["responses"]);

		die(json_encode($data));
	}

	public static function aweber_subscribe($visitor, $list_id) {
		global $wpmarketing;
		$wpmarketing = WPMarketing::settings();
		$url = "http://www.aweber.com/scripts/addlead.pl";
		$data = array(
			"user-agent" => "WPMarketing Plugin",
			"timeout" => 10,
			"sslverify" => false,
			"headers" => array(
				"Content-Type" => "application/json"
			),
			"body" => array(
				"listname" => $list_id,
				"redirect" => $_SERVER["SERVER_NAME"],
				"meta_message" => "1",
				"meta_required" => "email",
				"submit" => "Subscribe"
			)
		);

		if (isset($visitor["name"])) { $data["body"]["name"] = $visitor["name"]; }
		if (isset($visitor["email"])) { $data["body"]["email"] = $visitor["email"]; }
		if (isset($visitor["first_name"])) { $data["body"]["first_name"] = $visitor["first_name"]; }
		if (isset($visitor["last_name"])) { $data["body"]["last_name"] = $visitor["last_name"]; }
		if (!isset($data["body"]["name"]) && isset($visitor["first_name"])) {
			$data["body"]["name"] = $visitor["first_name"];
			if (isset($visitor["last_name"])) { $data["body"]["name"] += " $visitor[last_name]"; }
		}

		$response = wp_remote_post($url, $data);

		if (WPMarketing::debug) {
			return $response;
		} else {
			return !is_wp_error($response);
		}
	}

	public static function zendesk_subscribe($visitor) {
		global $wpmarketing;
		$wpmarketing = WPMarketing::settings();

		if (isset($wpmarketing["sync"]["zendesk"]["api_key"]) && isset($wpmarketing["sync"]["zendesk"]["subdomain"])) {
			$subdomain = $wpmarketing["sync"]["zendesk"]["subdomain"];
			$zendesk_email = $wpmarketing["sync"]["zendesk"]["email"];
			$api_key = $wpmarketing["sync"]["zendesk"]["api_key"];
			$api = "https://$subdomain.zendesk.com/api/v2/";
			$full_path = "tickets.json";
			$url = "$api/$full_path";


			$body = array(
				"ticket" => array(
					"comment" => array(),
					"requester" => array(
						"name" => $visitor["name"],
						"email" => $visitor["email"]
					)
				)
			);

			if (isset($visitor["subject"])) {
				$body["ticket"]["subject"] = $visitor["subject"];
			} else {
				$body["ticket"]["subject"] = "Website Request";
			}

			if (isset($visitor["body"])) {
				$body["ticket"]["comment"]["body"] = stripslashes($visitor["body"]);
			}

			$data = array(
				"user-agent" => "WPMarketing Plugin",
				"timeout" => 10,
				"sslverify" => false,
				"headers" => array(
					"Content-Type" => "application/json",
					"Accept" => "application/json",
					"Authorization" => 'Basic ' . base64_encode( $zendesk_email . "/token:" . $api_key )
				),
				"body" => json_encode($body)
			);

			$response = wp_remote_post($url, $data);
		} else {
			$response = array( "response" => array( "code" => 0 ));
		}

		return $response;
	}

	public static function mailchimp_subscribe($visitor, $list_id) {
		$post = array(
			"id" => $list_id,
			"email" => array( "email" => $visitor["email"] ),
			"send_welcome" => false,
			"email_type" => "html",
			"update_existing" => true,
			"replace_interests" => false,
			"double_optin" => false,
			"merge_vars" => array()
		);

		if (isset($visitor["email"])) { $post["merge_vars"]["EMAIL"] = $visitor["email"]; }
		if (isset($visitor["name"])) {
			$name = explode(" ", $visitor["name"]);
			$post["merge_vars"]["FNAME"] = $name[0];
			if (isset($name[1])) { $post["merge_vars"]["LNAME"] = $name[1]; }
		}
		if (isset($visitor["first_name"])) { $post["merge_vars"]["FNAME"] = $visitor["first_name"]; }
		if (isset($visitor["last_name"])) { $post["merge_vars"]["LNAME"] = $visitor["last_name"]; }
		if (isset($visitor["mobile"])) { $post["merge_vars"]["PHONE"] = $visitor["mobile"]; }

		return WPMarketing::mailchimp("lists/subscribe", $post);
	}

	public static function mailchimp_lists() {
		global $wpmarketing;
		$wpmarketing = WPMarketing::settings();
		$data = array( "success" => false );

		if (isset($wpmarketing["sync"]["mailchimp"]["api_key"]) && $wpmarketing["sync"]["mailchimp"]["api_key"] != "") {
			$response = WPMarketing::mailchimp("lists/list");
			$data["response"] = json_decode($response["body"]);

			if ($response["response"]["code"] == 200) {
				$data["success"] = true;
				$data["lists"] = $data["response"]->data;
			}
		} else {
			$data["success"] = true;
			$data["lists"] = array();
		}

		die(json_encode($data));
	}

	public static function mailchimp($path, $post = array()) {
		global $wpmarketing;
		$wpmarketing = WPMarketing::settings();

		if (isset($wpmarketing["sync"]["mailchimp"]["api_key"]) && $wpmarketing["sync"]["mailchimp"]["api_key"] != "") {
			$api_key = $wpmarketing["sync"]["mailchimp"]["api_key"];
			$key_exploded = explode("-", $api_key);

			if (count($key_exploded) > 1) {
				$data_center = $key_exploded[1];
				$api = "https://$data_center.api.mailchimp.com/2.0";
				$full_path = "$path.json";
				$url = "$api/$full_path";
				$post["apikey"] = $api_key;

				$data = array(
					"user-agent" => "WPMarketing Plugin",
					"timeout" => 10,
					"sslverify" => false,
					"headers" => array(
						"Content-Type" => "application/json"
					),
					"body" => json_encode($post)
				);

				$response = wp_remote_post($url, $data);
			} else {
				$response = array( "response" => array( "code" => 0 ));
			}
		} else {
			$response = array( "response" => array( "code" => 0 ));
		}

		return $response;
	}

	public static function settings_update() {
		$data = array( "success" => false );
    $data = WPMarketing::settings( $_POST["data"] );
		$data["success"] = true;
    die(json_encode($data));
	}

	public static function register_cta_widget() {
		register_widget( "WPMWidget" );
	}

	public static function cta_delete_response() {
		global $wpdb;
		$events_table = $wpdb->prefix . "wpmarketing_events";
		$success = $wpdb->delete($events_table, array(
			"id" => $_POST["id"]
		));
		die(json_encode(array( "success" => $success )));
	}

	public static function cta_count_responses() {
		$count = 0;

		if (isset($_REQUEST["id"])) {
			global $wpdb;
			$events_table = $wpdb->prefix . "wpmarketing_events";
			$wpdb->get_results( $wpdb->prepare("SELECT * FROM $events_table WHERE verb = %s AND cta_id = %d",
				"submit", $_REQUEST["id"]
			), ARRAY_A);
			$count = $wpdb->num_rows;
		}

		die(json_encode(array( "count" => $count )));
	}

	public static function shortcode_response_counter( $attrs ) {
		return "<span class=\"cta_counter\" data-id=\"$attrs[id]\">0</span>";
	}

	public static function shortcode_cta( $attrs ) {
		return WPMarketing::generate_cta_container( $attrs["id"] );
	}

	public static function generate_cta_container( $id ) {
		global $wpdb;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$cta = $wpdb->get_row("SELECT * FROM $ctas_table WHERE id = $id LIMIT 1", ARRAY_A);
		$container = "";

		if ($cta != null) {
			$cta = stripslashes_deep(json_decode(stripslashes_deep($cta["data"]))->data);
			$cache_key = $cta->cache_key;

			if ($cta->style == "inline" && !property_exists($cta, "disabled")) {
				$container = "<div class=\"wpm_container_$cache_key\"></div>";
			}
		}

		return $container;
	}
}

class WPMWidget extends WP_Widget {
	function WPMWidget() {
		parent::__construct( "wpm_widget", "WP Marketing", array( "description" => "Add a Call-To-Action." ) );
	}

	function widget( $args, $instance ) {
		echo WPMarketing::generate_cta_container( $instance["cta_id"] );
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance["cta_id"] = ( ! empty( $new_instance["cta_id"] ) ) ? (integer) $new_instance["cta_id"] : 0;
		return $instance;
	}

	function form( $instance ) {
		global $wpdb;
		$cta_options = "";
		$instance["cta_id"] = isset($instance["cta_id"]) ? $instance["cta_id"] : 0;
		$ctas_table = $wpdb->prefix . "wpmarketing_ctas";
		$ctas = $wpdb->get_results("SELECT * FROM $ctas_table ORDER BY updated_at DESC", ARRAY_A);

		foreach ($ctas as $key => $cta) {
			$cta["data"] = stripslashes_deep(json_decode(stripslashes_deep($cta["data"]))->data);

			if ($cta["data"]->style == "inline" && !property_exists($cta["data"], "disabled")) {
				$selected = "";
				if ((integer) $instance["cta_id"] == (integer) $cta["id"]) { $selected = "selected=\"selected\""; }
				$cta_options .= "<option value=\"" . $cta["id"] . "\" " . $selected . ">" . $cta["name"] . "</option>";
			}
		}

		echo "<div class=\"widget-content\">";

		if ($cta_options == "") {
			echo "<p>No Inline CTAs have been created.</p>";
		} else {
			echo "<p><label for=\"" . $this->get_field_name( "cta_id" ) . "\">Which CTA would you like to display (only Inline are permitted)?</label>
								<select name=\"" . $this->get_field_name( "cta_id" ) . "\">
									<option disabled=\"disabled\">Select A CTA...</option>
									$cta_options
								</select></p>";
		}

		echo "<p style=\"font-size: .8em; color: #777; \">
					CTAs are customized on the <a href=\"admin.php?page=wpmarketing\">WP Marketing page</a>.
				</p>
			</div>";
	}
}

//delete_option("wpmarketing_settings");
WPMarketing::init();

?>
