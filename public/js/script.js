(function() {
  this.WPMW || (this.WPMW = {});

  jQuery(document).ready(function($) {
    WPMW.updateCounters = function() {
      return $(".cta_counter").each(function() {
        var counter, data;
        counter = $(this);
        data = {
          action: "cta_count_responses",
          id: $(this).attr("data-id")
        };
        return $.post(WPMW.ajaxurl, data, function(response) {
          var json, numAnim;
          json = JSON.parse(response);
          counter.attr("id", "cta-counter-" + data.id);
          numAnim = new countUp("cta-counter-" + data.id, parseInt(counter.text()), json.count, 0, 0.5);
          return numAnim.start();
        });
      });
    };
    WPMW.findAndPush = function(ids) {
      var data;
      if (ids == null) {
        ids = false;
      }
      data = {
        action: "ctas_fetch_all"
      };
      return $.post(WPMW.ajaxurl, data, function(response) {
        var cta, ctas, _i, _len, _results;
        ctas = JSON.parse(response);
        _results = [];
        for (_i = 0, _len = ctas.length; _i < _len; _i++) {
          cta = ctas[_i];
          if (!cta.data.disabled) {
            cta.data.loader = WPMW.loader;
            cta.data.id = cta.id;
            if (cta.data.style === "inline" && (typeof cta.data.container === "undefined" || cta.data.container === "")) {
              cta.data.container = ".wpm_container_" + cta.data.cache_key;
            }
            _results.push(CTA.push(["widget", cta.data]));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      });
    };
    WPMW.findAndPush();
    return WPMW.updateCounters();
  });

}).call(this);
