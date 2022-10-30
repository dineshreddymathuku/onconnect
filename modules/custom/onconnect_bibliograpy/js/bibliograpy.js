(function ($, Drupal) {
  Drupal.behaviors.bibliograpy = {
    attach: function (context, settings) {
      jQuery(".bibliograpy-links").click(function (e) {
        var href = jQuery(this).attr("href");
        var nid = jQuery(this).attr("data-nid");
        var classData = "bibliograpy-links-" + nid;
        var id = jQuery(this).attr("id");
        e.preventDefault();
        e.stopPropagation();
        if (jQuery(this).hasClass("bibliograpy-links-add")) {
          jQuery.ajax({
            url: href,
            type: "GET",
            success: function (res) {
              jQuery("#" + id).removeClass("bibliograpy-links-add");
              jQuery("#" + id).addClass("bibliograpy-links-remove");
              jQuery("#" + id).attr("href", "/remove/bibliograpy/" + nid);
              if (jQuery("#publication-").hasClass("parent-link")) {
                jQuery("#" + id).text("- Remove from bibliography");
              }
              else {
                jQuery("#" + id).text("Remove from bibliography");
              }
            }
          });
        }
        if (jQuery(this).hasClass("bibliograpy-links-remove")) {
          jQuery.ajax({
            url: href,
            type: "GET",
            success: function (res) {
              jQuery("#" + id).removeClass("bibliograpy-links-remove");
              jQuery("#" + id).addClass("bibliograpy-links-add");
              jQuery("#" + id).attr("href", "/add/bibliograpy/" + nid);
              //console.log(jQuery("#publication-").hasClass("parent-link")) ;
              if (jQuery("#publication-").hasClass("parent-link")) {
                jQuery("#" + id).text("+Add to bibliography");
              }
              else {
                jQuery("#" + id).text("Add to bibliography");
              }
            }
          });
        }
        if (jQuery("." + classData).hasClass("bibliograpy-links-add")) {
          jQuery.ajax({
            url: href,
            type: "GET",
            success: function (res) {
              jQuery("." + classData).removeClass("bibliograpy-links-add");
              jQuery("." + classData).addClass("bibliograpy-links-remove");
              jQuery("." + classData).attr("href", "/remove/bibliograpy/" + nid);
              jQuery("." + classData).text("Remove from bibliography");
            }
          });
        }
        if (jQuery("." + classData).hasClass("bibliograpy-links-remove")) {
          jQuery.ajax({
            url: href,
            type: "GET",
            success: function (res) {
              jQuery("." + classData).removeClass("bibliograpy-links-remove");
              jQuery("." + classData).addClass("bibliograpy-links-add");
              jQuery("." + classData).attr("href", "/add/bibliograpy/" + nid);
              jQuery("." + classData).text("Add to bibliography");
            }
          });
        }
      });
      jQuery(".authors-show-all").click(function () {
        jQuery(".author-number").hide();
        jQuery(".authors-names-full").removeClass("hidden");
      });
      jQuery(".authors-names-full").click(function () {
        jQuery(".authors-names-full").addClass("hidden");
        jQuery(".author-number").show();
      });
      jQuery(".study-show-all").click(function () {
        jQuery(".study-numbers").hide();
        jQuery(".study-numbers-full").removeClass("hidden");
      });
      jQuery(".study-numbers-full").click(function () {
        jQuery(".study-numbers-full").addClass("hidden");
        jQuery(".study-numbers").show();
      });
    }
  };
})(jQuery, Drupal);
