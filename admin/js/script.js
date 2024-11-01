(function() {
  this.WPMW || (this.WPMW = {});

  jQuery(document).ready(function($) {
    if ($(".wrap.wpmarketing").length) {
      Swag.registerHelpers();
      Handlebars.registerHelper("ifEmptyObject", function(item, options) {
        if ($.isEmptyObject(item)) {
          return options.fn(this);
        } else {
          return options.inverse(this);
        }
      });
      $(window).on("hashchange", function() {
        var h, hash, header, header_template, info, layout, layout_data, layout_template, match, page, parent_h, parent_hash, route, tab, tab_template, _base, _ref;
        hash = window.location.hash.replace("#!", "");
        if (!hash.length) {
          window.location.hash = "#!/";
        }
        h = hash.split("/");
        parent_h = window.location.hash.split("/");
        parent_h.pop();
        parent_hash = parent_h.join("/");
        $("[data-wpm-loading]").show();
        _ref = WPMW.routes;
        for (route in _ref) {
          info = _ref[route];
          match = new RegExp(route).test(hash);
          if (match) {
            page = {
              id: h[2],
              page: {}
            };
            if (typeof info.preload === "function") {
              page = info.preload(page);
            }
            page.page || (page.page = {});
            if (typeof info.title !== "undefined") {
              (_base = page.page).title || (_base.title = info.title);
            }
            header = $("#wpmarketing_header_" + info.layout);
            if (header.length) {
              header_template = header.html();
              header = Handlebars.compile(header_template);
              page.header = header(page);
            }
            tab_template = $("#wpmarketing_tab_" + info.tab).html();
            tab_template || (tab_template = "");
            tab = Handlebars.compile(tab_template);
            if (WPMW.current_layout === info.layout) {
              $("[data-wpm-tab]").hide().html(tab(page));
            } else {
              layout_template = $("#wpmarketing_layout_" + info.layout).html();
              layout_template || (layout_template = "");
              layout = Handlebars.compile(layout_template);
              page.ctas_length = WPMW.ctas.length || 0;
              page.ctas = WPMW.ctas;
              page.outlet = tab(page);
              layout_data = page;
              $("[data-wpm-layout]").hide().html(layout(layout_data));
            }
            WPMW.current_tab = info.tab;
            WPMW.current_layout = info.layout;
            WPMW.current_id = page.id;
            if ($.isEmptyObject(WPMW.ctas)) {
              $(".ctas .hold_place").show();
            } else {
              $(".ctas .hold_place").hide();
            }
            if (typeof info.complete === "function") {
              info.complete();
            }
            break;
          }
        }
        if (!match) {
          WPMW.current_tab = null;
          WPMW.current_layout = null;
          WPMW.current_id = null;
          $("[data-wpm-container]").html("");
        }
        $("[data-wpm-layout] a").removeClass("parent_of_current").removeClass("current");
        $("[data-wpm-layout] a[href='" + window.location.hash + "']").addClass("current");
        $("[data-wpm-layout] a[href='" + parent_hash + "']").addClass("parent_of_current");
        $("[data-wpm-loading]").hide();
        return $("[data-wpm-layout], [data-wpm-tab]").fadeIn(150);
      });
      return WPMW.fetchCTAs(function() {
        return $(window).trigger("hashchange");
      });
    }
  });

  this.WPMW || (this.WPMW = {});

  (function($) {
    return WPMW.routes = {
      "/unlock": {
        layout: "unlock",
        tab: "page"
      },
      "/ctas/(.*?)/responses": {
        layout: "ctas",
        tab: "responses",
        title: "Responses",
        preload: function(page) {
          var cta, finish_date, start_date;
          cta = WPMW.ctas[page.id];
          cta || (cta = {});
          start_date = new Date();
          start_date.setMonth(start_date.getMonth() - 1);
          finish_date = new Date();
          cta.start = (start_date.getMonth() + 1) + "/" + start_date.getDate() + "/" + start_date.getFullYear();
          cta.finish = (finish_date.getMonth() + 1) + "/" + finish_date.getDate() + "/" + finish_date.getFullYear();
          WPMW.fetchResponses(cta.id, cta.start, cta.finish);
          return cta;
        },
        complete: function() {
          return $(".has_datepicker").datepicker({
            onSelect: function() {
              var cta_id, finish, start;
              finish = $(".responses_finish").val();
              start = $(".responses_start").val();
              cta_id = WPMW.current_id;
              return WPMW.fetchResponses(cta_id, start, finish);
            }
          });
        }
      },
      "/ctas/(.*?)/overview": {
        layout: "ctas",
        tab: "overview"
      },
      "/ctas/(.*?)": {
        layout: "ctas",
        tab: "settings",
        title: $(".wpmarketing").hasClass("locked") && Object.keys(WPMW.ctas).length < 1 ? "Create New CTA" : "Congratulations &mdash; you hit the limit!",
        preload: function(page) {
          var cta, email, field, _base, _base1, _i, _j, _len, _len1, _ref, _ref1;
          WPMW.fetchMailChimpLists();
          cta = WPMW.ctas[page.id];
          cta || (cta = {});
          cta.page || (cta.page = {});
          cta.cache_key = WPMW.randomToken();
          cta.emails || (cta.emails = []);
          if (cta.action === "button") {
            cta.fields || (cta.fields = []);
          } else {
            cta.fields || (cta.fields = [
              {
                type: "text",
                label: "Name",
                key: "name",
                placeholder: "Your Full Name"
              }, {
                type: "email",
                label: "Best Email",
                key: "email",
                placeholder: "Your Email Address"
              }
            ]);
          }
          if (cta.fields.length) {
            _ref = cta.fields;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              field = _ref[_i];
              field.html = WPMW.addField("return", field);
            }
          }
          if (cta.emails.length) {
            _ref1 = cta.emails;
            for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
              email = _ref1[_j];
              email.html = WPMW.addEmail("return", email);
            }
          }
          if (page.id === "new") {
            cta.overlay || (cta.overlay = {});
            cta.closable || (cta.closable = "1");
            (_base = cta.overlay).escapable || (_base.escapable = "1");
            (_base1 = cta.overlay).clickable || (_base1.clickable = "1");
            cta.title || (cta.title = "Sign up for our Newsletter");
            cta.description || (cta.description = "It's as easy as 1, 2, 3!");
          }
          cta.button || (cta.button = "Sign Me Up &nbsp;&rarr;");
          cta.text_response || (cta.text_response = "Thank you for your response.");
          cta.appearance || (cta.appearance = {
            background: "#eeeeee",
            width: "325px",
            heading: {
              background: "#006298",
              color: "#ffffff"
            },
            subheading: {
              background: "",
              color: "#444444"
            },
            button: {
              background: "#DC4100",
              color: "#ffffff"
            },
            paragraph: {
              color: "#777777"
            }
          });
          cta.triggers || (cta.triggers = [
            {
              event: "load",
              delay: "0",
              scroll: "0%"
            }
          ]);
          cta.sync || (cta.sync = {});
          cta.sync.mailchimp = $.extend(cta.sync.mailchimp, WPMW.settings.sync.mailchimp);
          cta.sync.zendesk = $.extend(cta.sync.zendesk, WPMW.settings.sync.zendesk);
          cta.settings = WPMW.settings;
          cta.ctas_length = Object.keys(WPMW.ctas).length;
          return cta;
        },
        complete: function() {
          WPMW.setStyles();
          $(".fields tbody").sortable({
            axis: "y",
            items: "tr",
            forcePlaceholderSize: true,
            start: function(e, ui) {
              return ui.placeholder.height(ui.item.height());
            }
          });
          return $(".has_colourpicker").wpColorPicker();
        }
      },
      "/settings": {
        layout: "settings",
        tab: "index",
        preload: function(page) {
          page.sync = WPMW.settings.sync;
          return page;
        }
      },
      "/ctas": {
        layout: "ctas",
        tab: "index"
      },
      "/": {
        layout: "ctas",
        tab: "index"
      }
    };
  })(jQuery);

  this.WPMW || (this.WPMW = {});

  WPMW.ctas || (WPMW.ctas = {});

  WPMW.ctaUpdate || (WPMW.ctaUpdate = []);

  WPMW.settings || (WPMW.settings = {});

  WPMW.ctaFields = {
    contact: {
      bar: ["button", "title", "fields", "redirect", "closable", "heading_background", "sync", "sticky"],
      box: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync"],
      dialog: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync", "escapable", "clickable", "width"],
      inline: ["button", "title", "fields", "redirect", "background", "description", "sync", "container"]
    },
    subscription: {
      bar: ["button", "title", "fields", "redirect", "closable", "heading_background", "sync", "sticky"],
      box: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync"],
      dialog: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync", "escapable", "clickable", "width"],
      inline: ["button", "title", "fields", "redirect", "background", "description", "sync", "container"]
    },
    callback: {
      bar: ["button", "title", "fields", "redirect", "closable", "heading_background", "sync", "sticky"],
      box: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync"],
      dialog: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync", "escapable", "clickable", "width"],
      inline: ["button", "title", "fields", "redirect", "background", "description", "sync", "container"]
    },
    download: {
      bar: ["button", "title", "fields", "download", "closable", "heading_background", "sync", "sticky"],
      box: ["button", "title", "fields", "download", "background", "closable", "heading_background", "subheading_background", "description", "sync"],
      dialog: ["button", "title", "fields", "download", "background", "closable", "heading_background", "subheading_background", "description", "sync", "escapable", "clickable", "width"],
      inline: ["button", "title", "fields", "download", "background", "description", "sync", "container"]
    },
    petition: {
      bar: ["button", "title", "fields", "redirect", "closable", "heading_background", "sync", "sticky"],
      box: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync"],
      dialog: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync", "escapable", "clickable", "width"],
      inline: ["button", "title", "fields", "redirect", "background", "description", "sync", "container"]
    },
    appointment: {
      bar: ["button", "title", "fields", "redirect", "closable", "heading_background", "sync", "sticky"],
      box: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync"],
      dialog: ["button", "title", "fields", "redirect", "background", "closable", "heading_background", "subheading_background", "description", "sync", "escapable", "clickable", "width"],
      inline: ["button", "title", "fields", "redirect", "background", "description", "sync", "container"]
    },
    button: {
      bar: ["button", "title", "redirect", "closable", "heading_background", "sticky"],
      box: ["button", "title", "redirect", "closable", "background", "heading_background", "subheading_background", "description"],
      dialog: ["button", "title", "redirect", "closable", "background", "heading_background", "subheading_background", "description", "escapable", "clickable", "width"],
      inline: ["button", "title", "redirect", "description", "background", "container"]
    },
    social: {
      inline: ["title", "description", "social"],
      box: ["title", "description", "social"],
      midbox: ["social"]
    }
  };

  (function($) {
    WPMW.randomToken = function() {
      return Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2);
    };
    WPMW.ctaUpdate.push = function(data) {
      var cta, cta_template, existing;
      if (typeof data.data === "undefined") {
        WPMW.ctas[data.id] = data;
      } else {
        WPMW.ctas[data.id] = data.data;
        WPMW.ctas[data.id].name = data.name;
        WPMW.ctas[data.id].id = data.id;
      }
      existing = $(".cta_" + data.id);
      cta_template = $("#wpmarketing_sidebar_cta").html();
      cta = Handlebars.compile(cta_template);
      if (existing.length) {
        existing.find("[data-name]").text(data.name);
      } else {
        $(".ctas").append(cta(data));
      }
      $(".ctas .hold_place").hide();
      $(".wpmarketing").attr("data-ctas-length", "" + (Object.keys(WPMW.ctas).length));
      if (data["new"]) {
        return window.location.hash = "#!/ctas/" + data.id;
      }
    };
    WPMW.setStyles = function() {
      var action, current_style, field, form, style, _i, _j, _len, _len1, _ref, _ref1;
      form = $("#cta_form");
      action = WPMW.ctaFields[$(".cta_action").val()];
      current_style = form.find("input[name='style']").val() || "bar";
      form.find("[data-field], [data-style]").hide();
      _ref = Object.keys(action);
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        style = _ref[_i];
        form.find("[data-style='" + style + "']:not(.cta_tab)").show();
      }
      _ref1 = action[current_style];
      for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
        field = _ref1[_j];
        form.find("[data-field='" + field + "']:not(.cta_tab)").show();
      }
      return setTimeout(function() {
        if (!$(".cta_styles li.selected:visible").length) {
          form.find(".cta_styles li:visible:last").trigger("click");
        }
        return WPMW.setName();
      }, 100);
    };
    WPMW.fetchCTAs = function(callback) {
      var cta, k, _ref;
      if (callback == null) {
        callback = false;
      }
      if ($.isEmptyObject(WPMW.ctas)) {
        return $.post(ajaxurl, {
          action: "ctas_fetch_all"
        }, function(response) {
          var cta, ctas, _i, _len;
          ctas = JSON.parse(response);
          for (_i = 0, _len = ctas.length; _i < _len; _i++) {
            cta = ctas[_i];
            WPMW.ctaUpdate.push(cta);
          }
          if (typeof callback === "function") {
            return callback();
          }
        });
      } else if ($(".ctas li").length <= 1) {
        _ref = WPMW.ctas;
        for (k in _ref) {
          cta = _ref[k];
          WPMW.ctaUpdate.push(cta);
        }
        if (typeof callback === "function") {
          return callback();
        }
      }
    };
    WPMW.fetchResponses = function(cta_id, start, finish) {
      $(".responses .response, .responses .response_details").remove();
      $(".responses .no_responses").hide();
      $(".responses .is_loading").show();
      $(".responses_count").text(0);
      return $.post(ajaxurl, {
        action: "cta_fetch_responses",
        cta_id: cta_id,
        start: start,
        finish: finish
      }, function(data) {
        var json, response, _i, _len, _ref;
        json = JSON.parse(data);
        _ref = json.responses;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          response = _ref[_i];
          WPMW.addResponse(response);
        }
        $(".responses .is_loading").hide();
        if (json.count === 0) {
          $(".responses .no_responses").show();
        }
        return $(".responses_count").text(json.count);
      });
    };
    WPMW.fetchMailChimpLists = function() {
      var _base, _base1;
      (_base = WPMW.settings).sync || (_base.sync = {});
      (_base1 = WPMW.settings.sync).mailchimp || (_base1.mailchimp = {});
      if (typeof WPMW.settings.sync.mailchimp.lists === "undefined") {
        return $.post(ajaxurl, {
          action: "mailchimp_lists"
        }, function(response) {
          var json, list, list_id, _i, _len, _ref;
          list_id = $(".mailchimp_list_id").attr("data-list-id");
          json = JSON.parse(response);
          if (json.success) {
            WPMW.settings.sync.mailchimp.lists = json.lists;
            _ref = json.lists;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              list = _ref[_i];
              $("<option value=\"" + list.id + "\">" + list.name + "</option>").appendTo(".mailchimp_list_id");
            }
            $(".mailchimp").removeClass("is_loading");
            return $(".mailchimp_list_id").val(list_id);
          }
        });
      } else {
        return setTimeout(function() {
          return $(".mailchimp").removeClass("is_loading");
        }, 100);
      }
    };
    WPMW.addResponse = function(data) {
      var html, response, template;
      template = $("#wpmarketing_response").html();
      response = Handlebars.compile(template);
      html = response(data);
      $(".responses .no_responses").hide();
      return $(html).appendTo(".wpmarketing .responses tbody");
    };
    WPMW.ctaDelete = function(id) {
      $(".cta_" + id).remove();
      delete WPMW.ctas[id];
      if ($.isEmptyObject(WPMW.ctas)) {
        return $(".ctas .hold_place").show();
      } else {
        return $(".ctas .hold_place").hide();
      }
    };
    WPMW.addField = function(output, data) {
      var field, field_template;
      if (output == null) {
        output = "append";
      }
      if (data == null) {
        data = {};
      }
      field_template = $("#wpmarketing_field").html();
      field = Handlebars.compile(field_template);
      if (output === "return") {
        return field(data);
      } else {
        return $(field(data)).appendTo("#cta_form .fields");
      }
    };
    WPMW.addEmail = function(output, data) {
      var email, email_template;
      if (output == null) {
        output = "append";
      }
      if (data == null) {
        data = {};
      }
      email_template = $("#wpmarketing_email").html();
      email = Handlebars.compile(email_template);
      if (output === "return") {
        return email(data);
      } else {
        return $(email(data)).appendTo(".action_emails");
      }
    };
    WPMW.setName = function() {
      var action, name, style;
      name = $("#cta_form input[name='name'][type='hidden']");
      if (name.length) {
        style = $(".cta_styles li.selected").attr("data-title");
        action = $(".cta_action option:selected").text();
        return name.val("" + action + " (" + style + ")");
      }
    };
    $(document).on("change", "#action", function() {
      switch ($(this).val()) {
        case "button":
          $(".cta_field_row").remove();
      }
      return WPMW.setStyles();
    });
    $(document).on("click", ".cta_styles li", function() {
      var form, position, style;
      form = $(this).closest("form");
      style = $(this).attr("data-style");
      position = $(this).attr("data-position");
      $(".cta_styles li").removeClass("selected");
      $(this).addClass("selected");
      form.find("input[name='style']").val(style);
      form.find("[name='position']").val(position);
      return WPMW.setStyles();
    });
    $(document).on("keyup", "#cta_form input[name='name']", function() {
      return $(".cta_" + WPMW.current_id).find("[data-name]").text($(this).val());
    });
    $(document).on("click", "#disable_css", function() {
      if ($(this).is(":checked")) {
        return $(".css_fields").hide();
      } else {
        return $(".css_fields").show();
      }
    });
    $(document).on("click", ".delete_cta", function() {
      var form;
      if (confirm("Are you sure you want to delete this CTA?")) {
        form = $(this).closest("form");
        form.addClass("is_loading");
        $.post(ajaxurl, {
          action: "cta_delete",
          id: $(this).attr("data-id")
        }, function(response) {
          var json;
          json = JSON.parse(response);
          if (json.success) {
            WPMW.ctaDelete(json.id);
            return window.location.hash = "#!/ctas";
          } else {
            form.removeClass("is_loading");
            return alert("We couldn't delete this CTA.");
          }
        });
      }
      return false;
    });
    $(document).on("change", ".change_expire", function() {
      var cache_key;
      cache_key = WPMW.randomToken();
      $(".cache_key").val(cache_key);
      return $("[data-cache-key]").text(cache_key);
    });
    $(document).on("change", ".trigger_event", function() {
      var event;
      event = $(this).val();
      $("[data-trigger-event]:not([data-trigger-event='" + event + "'])").hide();
      $("[data-trigger-event='" + event + "']").show();
      $("[data-not-trigger-event][data-not-trigger-event='" + event + "']").hide();
      return $("[data-not-trigger-event][data-not-trigger-event!='" + event + "']").show();
    });
    $(document).on("click", ".add_field", function() {
      WPMW.addField("append", {
        required: true
      });
      return false;
    });
    $(document).on("click", ".add_email", function() {
      var data;
      if ($(".action_email").length) {
        data = {
          to: "" + WPMW.settings.subscriber_name + " <" + WPMW.settings.subscriber_email + ">",
          from: "" + WPMW.settings.website + " <no-reply@" + WPMW.settings.website + ">",
          subject: "" + WPMW.settings.website + " Lead",
          message: "Hi " + WPMW.settings.subscriber_name + ",\n\n{{ name }} ({{ email }}) has submitted a form:\n\n{{{ data }}}\n\nThanks,\n\n" + WPMW.settings.website
        };
      } else {
        data = {
          to: "{{ name }} <{{ email }}>",
          from: "" + WPMW.settings.website + " <no-reply@" + WPMW.settings.website + ">",
          subject: "{{ name }}, thanks for contacting us!",
          message: "Hi {{ name }},\n\nWe'll be in touch as soon as we can!\n\nThanks,\n\n" + WPMW.settings.website
        };
      }
      WPMW.addEmail("append", data);
      return false;
    });
    $(document).on("click", ".delete_response", function() {
      var id, response;
      if (confirm("This will permanently delete this response. Are you sure you want to delete this response?")) {
        response = $(this).closest(".response");
        id = response.attr("data-id");
        $(".response_details_" + id + ", .response_" + id).removeClass("selected_response");
        $(".response_" + id).hide();
        $.post(ajaxurl, {
          action: "cta_delete_response",
          id: id
        }, function(response) {
          var json;
          json = JSON.parse(response);
          if (json.success) {
            return $(".response_details_" + id + ", .response_" + id).remove();
          } else {
            $(".response_" + id).show();
            return alert("We were unable to delete this response. Its possible its already deleted.");
          }
        });
      }
      return false;
    });
    $(document).on("click", ".delete_email", function() {
      if (confirm("Are you sure you want to delete this field?")) {
        $(this).closest(".action_email").remove();
      }
      return false;
    });
    $(document).on("click", ".delete_field", function() {
      if (confirm("Are you sure you want to delete this field?")) {
        $(this).closest("tr").remove();
      }
      return false;
    });
    $(document).on("click", ".show_response_details", function() {
      var id;
      id = $(this).closest(".response").attr("data-id");
      $(".response_details:not(.response_details_" + id + "), .response:not(.response_" + id + ")").removeClass("selected_response");
      $(".response_" + id + ", .response_details_" + id).toggleClass("selected_response");
      return false;
    });
    $(document).on("submit", ".unlock_code_form", function() {
      $(".unlock_code_form").find(".initial").hide(150);
      $(".unlock_code_form").find(".loading").show(150);
      $.post(ajaxurl, {
        action: "unlock",
        unlock_code: $(this).find("input[name='unlock_code']").val()
      }, function(response) {
        var json;
        json = JSON.parse(response);
        if (json.success) {
          WPMW.settings.unlock_code = json.unlock_code;
          $(".unlock_code").text(json.unlock_code);
          return $(".wpmarketing").removeClass("locked").addClass("unlocked");
        } else {
          alert("The unlock code you provided was invalid.");
          $(".unlock_code_form").find(".loading").hide(150);
          return $(".unlock_code_form").find(".initial").show(150);
        }
      });
      return false;
    });
    $(document).on("click", ".change_unlock_code", function() {
      $(".unlock_code_form").toggle(150);
      $(".change_unlock_code_form").toggle(150);
      return false;
    });
    $(document).on("click", ".cta_show_tab, .integrations_show_tab", function() {
      var tab, tabber;
      tabber = $(this).hasClass("cta_show_tab") ? "cta" : "integrations";
      tab = $(this).attr("href");
      $("." + tabber + "_show_tab.selected").removeClass("selected");
      $(this).addClass("selected");
      $("." + tabber + "_tab:not(." + tabber + "_tab_" + tab + ")").hide();
      $("." + tabber + "_tab." + tabber + "_tab_" + tab).show();
      return false;
    });
    $(document).on("click", ".duplicate_cta", function() {
      var form;
      form = $(this).closest("form");
      form.addClass("is_loading");
      $.post(ajaxurl, {
        action: "cta_duplicate",
        id: $(this).attr("data-id")
      }, function(response) {
        var json;
        json = JSON.parse(response);
        if (json.success) {
          WPMW.ctaUpdate.push(json);
          return window.location.hash = "#!/ctas/" + json.id;
        } else {
          form.removeClass("is_loading");
          return alert("We couldn't duplicate this CTA.");
        }
      });
      return false;
    });
    return $(document).on("submit", ".wpmarketing_form", function() {
      var form;
      form = $(this);
      form.addClass("is_loading");
      $.post(ajaxurl, {
        action: form.attr("action"),
        data: $(this).serializeJSON()
      }, function(response) {
        var json;
        json = JSON.parse(response);
        if (json.success) {
          if (!json["new"]) {
            form.removeClass("is_loading");
          }
          switch (form.attr("id")) {
            case "settings_form":
              WPMW.settings = json;
              return WPMW.fetchMailChimpLists();
            case "cta_form":
              return WPMW.ctaUpdate.push(json);
          }
        } else {
          if (form.attr("id") === "cta_form") {
            return alert("We couldn't save this CTA.");
          } else {
            return alert("We couldn't save your settings.");
          }
        }
      });
      return false;
    });
  })(jQuery);

}).call(this);
