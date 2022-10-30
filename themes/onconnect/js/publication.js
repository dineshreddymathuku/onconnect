(function ($, Drupal) {
  Drupal.behaviors.publicationsData = {
    attach: function (context, settings) {
      $(".publications-view .list").click(function () {
        $(".publications-grid").addClass("hidden");
        $(".publications-view .grid").removeClass("hidden");
        $(".publications-view .list").addClass("hidden");
        $(".publications-list").removeClass("hidden");
      });
      $(".publications-view .grid").click(function () {
        $(".publications-grid").removeClass("hidden");
        $(".publications-view .list").removeClass("hidden");
        $(".publications-view .grid").addClass("hidden");
        $(".publications-list").addClass("hidden");
      });
	  
    }
  };
})(jQuery, Drupal);

jQuery(document).ready(function () {
  jQuery(document).click(function (e) {
    if (jQuery(e.target).closest(".latest-publications").length === 0) {
      jQuery(".menu-link-grid-dropdown").addClass("hidden");
      jQuery(".menu-link-list-dropdown").addClass("hidden");
    }
  });
  jQuery(".menu-icon-grid a").click(function (e) {
    e.preventDefault();
    var nid = jQuery(this).attr("data-nid");
    if (jQuery("#menu-link-grid-" + nid).hasClass("show")) {
      jQuery("#menu-link-grid-" + nid).addClass("hidden");
      jQuery("#menu-link-grid-" + nid).removeClass("show");
    }
    else if (jQuery("#menu-link-grid-" + nid).hasClass("hidden")) {
      jQuery("#menu-link-grid-" + nid).removeClass("hidden");
      jQuery("#menu-link-grid-" + nid).addClass("show");
    }
  });
  jQuery(".menu-icon-list a").click(function () {
    e.preventDefault();
    var nid = jQuery(this).attr("data-nid");
    if (jQuery("#menu-link-list-" + nid).hasClass("show")) {
      jQuery("#menu-link-list-" + nid).addClass("hidden");
      jQuery("#menu-link-list-" + nid).removeClass("show");
    }
    else if (jQuery("#menu-link-list-" + nid).hasClass("hidden")) {
      jQuery("#menu-link-list-" + nid).removeClass("hidden");
      jQuery("#menu-link-list-" + nid).addClass("show");
    }
  });
  jQuery(".bibliography-links").click(function (e) {
    var href = jQuery(this).attr("href");
    var nid = jQuery(this).attr("data-nid");
    var id = jQuery(this).attr("id");
	
    e.preventDefault();
    e.stopPropagation();
    if (jQuery(this).hasClass("bibliography-links-add")) {
      jQuery.ajax({
        url: href,
        type: "GET",
        success: function (res) {
          jQuery("#" + id).removeClass("bibliography-links-add");
          jQuery("#" + id).addClass("bibliography-links-remove");
          jQuery("#" + id).attr("href", "/remove/bibliography/" + nid);
          jQuery("#" + id).text("Remove from Bibliography");
        }
      });
    }
    if (jQuery(this).hasClass("bibliography-links-remove")) {
      jQuery.ajax({
        url: href,
        type: "GET",
        success: function (res) {
          jQuery("#" + id).removeClass("bibliography-links-remove");
          jQuery("#" + id).addClass("bibliography-links-add");
          jQuery("#" + id).attr("href", "/add/bibliography/" + nid);
          jQuery("#" + id).text("Add to Bibliography");
        }
      });
    }
  });
});
if(jQuery("#edit-field-attachments-0-subform-field-type option[value='file']")){
  jQuery('#edit-field-attachments-0-subform-field-resource-link-0').css('display','none')
}
