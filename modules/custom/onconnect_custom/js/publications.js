(function ($, Drupal) {
  Drupal.behaviors.publications = {
    attach: function (context, settings) {

      // On click view metrics show only first tab.
      $(".viewall-metrics").click(function () {
        $(".metrics-popup li").each(function () {
          $(this).removeClass("active");
        });
        $(".tab-content .tab-pane").removeClass("active");
        $(".metrics-popup li a").each(function () {
          $(this).attr("aria-expanded", false);
        });
        $("#publication-metrics").addClass("active");
        $(".metrics-popup li").first().addClass("active");
        $(".metrics-popup li a").first().attr("aria-expanded", true);
      });

      // On click view more open respective metrics tab.
      $(".view-more-text").click(function () {
        $(".metrics-popup li").each(function () {
          $(this).removeClass("active");
        });
        $(".tab-content .tab-pane").removeClass("active");
        $(".metrics-popup li a").each(function () {
          $(this).attr("aria-expanded", false);
        });
        var metricsPaneID = $(this).attr("data-id").replace("-title", '');
        $("#" + metricsPaneID).addClass("active");
        $("#" + $(this).attr("data-id")).addClass("active");
        $("#" + $(this).attr("data-id")).parent("li").addClass("active");
      });

      function getCookie(cname) {
        let name = cname + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
      }

      var currentPath = jQuery(location).attr('pathname');
      var publicationView = getCookie("publications-view-" + currentPath);
      if (publicationView === "list") {
        $(".publications-grid").addClass("hidden");
        $(".publications-view .grid").removeClass("hidden");
        $(".publications-view .list").addClass("hidden");
        $(".publications-list").removeClass("hidden");
      }
      $(".publications-view .list").click(function () {
        document.cookie = 'publications-view-' + currentPath + '=list';
        $(".publications-grid").addClass("hidden");
        $(".publications-view .grid").removeClass("hidden");
        $(".publications-view .list").addClass("hidden");
        $(".publications-list").removeClass("hidden");
      });
      $(".publications-view .grid").click(function () {
        document.cookie = 'publications-view-' + currentPath + '=grid';
        $(".publications-grid").removeClass("hidden");
        $(".publications-view .list").removeClass("hidden");
        $(".publications-view .grid").addClass("hidden");
        $(".publications-list").addClass("hidden");
        //$(".showmore-btn a").attr("view","grid");
      });
      $(".jumpto a").click(function (e) {
        e.preventDefault();
        var href = $(this).attr("href");
        $([document.documentElement, document.body]).animate({
          scrollTop: $(href).offset().top,
          behavior: 'smooth'
        }, 10);
      });
      $(document).click(function (e) {
        if ($(e.target).closest(".latest-publications").length === 0) {
          $(".menu-link-grid-dropdown").addClass("hidden");
          $(".menu-link-list-dropdown").addClass("hidden");
        }
      });
    }
  };
})(jQuery, Drupal);

jQuery(document).ready(function () {
  var relatedDiseases = drupalSettings.relatedDiseases;
  var relatedCongresses = drupalSettings.relatedCongresses;
  
  if (relatedDiseases) {
    jQuery("#collapseRelatedDiseaseType").html("");
    var panelBody = '<div class="panel-body">';
    jQuery.each(relatedDiseases, function(index, value) {
      panelBody += '<a href="' + index + '" role="button">' + value + '</a>';
    });
    panelBody += '</div>';
    jQuery("#collapseRelatedDiseaseType").html(panelBody);
   // console.log(panelBody);
  }
  if(relatedCongresses) {
   
	jQuery("#collapsecongresses").html("");
    var panelBody = '<div class="panel-body">';
    jQuery.each(relatedCongresses, function(index, value) {
      panelBody += '<a href="' + index + '" role="button">' + value + '</a>';
    });
    panelBody += '</div>';
    jQuery("#collapsecongresses").html(panelBody);
    //console.log(panelBody);
  }
 
  jQuery(".description-show-more").click(function () {
    //console.log("here");
    jquery(".description-full").removeClass("hidden");
    jquery(".description-trim").addClass("hidden");
  });
  jQuery("body").on("click", ".menu-icon-grid a", function (e) {
    e.preventDefault();
    jQuery(".menu-link-grid-dropdown").addClass("hidden");
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
  jQuery("body").on("click", ".menu-icon-list", function (e) {
    jQuery(".menu-link-list-dropdown").addClass("hidden");
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
  jQuery('body').on('click', '.showmore-btn a', function (e) {
    e.preventDefault();
    var count = jQuery('.showmore-btn a').attr("pager");
    var href = window.location.href;
    var params = window.location.search;
    if (params) {
      var val = params.search("pagerCount");
      if (val > 0) {
        var url = new URL(href);
        var search_params = url.searchParams;
        search_params.set('pagerCount', count);
        url.search = search_params.toString();
        href = url.toString();
      }
      else {
        href = href + "&pagerCount=" + count + "";
      }
    }
    else {
      href = href + "?pagerCount=" + count + "";
    }
    window.location.replace(href);
  });
});
// Landing page filters remove from URL.
jQuery(".filters-links").click(function () {
  var link = jQuery(this).attr("data-value");
  var href = window.location.href;
  var newLink = href.replace("&" + link, "");
  newLink = newLink.replace(link, "");
  newLink = newLink.replace("?" + link, "");
  window.location.replace(newLink);
});

jQuery('body').on('click', '#showMore a', function (e) {
  e.preventDefault();
  ajax_call();
});

function ajax_call() {
  var url = window.location.href;
  var offset = jQuery('#showMore a').attr('offset');
  var limit = jQuery('#showMore a').attr('limit');
  var show = jQuery('#showMore a').attr('show');
  var vid = jQuery('#showMore a').attr('vid');
  var tid = jQuery('#showMore a').attr('tid');
  jQuery.ajax({
    url: "/custom_ajax_link",
    type: "POST",
    data: {
      'url': url,
      'offset': offset,
      'limit': limit,
      'show': show,
      'vid': vid,
      'tid': tid
    },
    dataType: 'json',
    success: function (response) {
      jQuery.each(response, function (index, el) {
        if (index == 'grid') {
          jQuery('.publications-grid').append(el);
        }
        if (index == 'list') {
          jQuery('.publications-list').append(el);
        }
      });
      jQuery('#showMore a').attr('offset', parseInt(limit) + parseInt(offset));
      jQuery('#showMore a').attr('limit', show);
      var total = jQuery('#showMore a').attr('total');
      var display = parseInt(offset) + parseInt(limit) + parseInt(show);
      if (display > total) {
        jQuery('.showCount').html('');
        jQuery('#showMore').hide();
        var items = total + ' items';
      }
      else {
        var msg = 'Showing ' + display + ' of ' + total;
        var items = display + ' items';
      }
      jQuery('.showCount').html(msg);
      jQuery('.count').html(items);
    }
  });
}
// submit publication form cancel
jQuery('.js-form-submit').click(function() {
  window.location.href = '/home';
  return false;
});
