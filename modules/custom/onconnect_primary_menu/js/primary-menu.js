(function ($, Drupal) {
  Drupal.behaviors.primary_menu = {
    attach: function (context, settings) {

    }
  };
})(jQuery, Drupal);

jQuery(document).ready(function () {
  jQuery("a.latest-view-link").click(function () {
    var latestTitle = jQuery(this).attr("data-latesttitle");
    var latestHref = jQuery(this).attr("href");
    document.cookie = "latestTitle=" + latestTitle;
    document.cookie = "latestHref=" + latestHref;
  });

  function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
    if (!results) {
      return null;
    }
    if (!results[2]) {
      return '';
    }
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  var searchtext = getParameterByName("search_api_fulltext");
  if (searchtext !== null || searchtext | length > 1) {
    jQuery("input.searchbox-input").val(searchtext);
    jQuery(".secondary-menu .searchbox").addClass("searchbox-open");
  }
});
