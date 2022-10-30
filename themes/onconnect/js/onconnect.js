jQuery(document).ready(function () {
  "use strict";
  
  jQuery(".dropdown-icon").hover(function () {
    jQuery(".dropdown").toggle();
  });
  jQuery(".authors-show-all").click(function () {
    jQuery(".author-names").hide();
    jQuery(".authors-names-full").removeClass("hidden");
  });
  jQuery(".authors-names-full").click(function () {
    jQuery(".authors-names-full").addClass("hidden");
    jQuery(".author-names").show();
  });
  jQuery(".study-show-all").click(function () {
    jQuery(".study-numbers").hide();
    jQuery(".study-numbers-full").removeClass("hidden");
  });
  jQuery(".study-numbers-full").click(function () {
    jQuery(".study-numbers-full").addClass("hidden");
    jQuery(".study-numbers").show();
  });
    
  //search page clear all functionality
  jQuery('.clear-all').click(function(e){
		e.preventDefault();
		jQuery(".categories input:checkbox").each(function() {
			this.checked = false;
		});
		var uri = window.location.toString();
            if (uri.indexOf("?") > 0) {
                var clean_uri = uri.substring(0, uri.indexOf("?"));
				window.location.replace(clean_uri);
            }
	});
	// search page add div wrapper on show more
	var search = jQuery('.search-page-main-contianer .pager  ul li a');
	
	if(search.length){		
		search.wrap("<div class='showmore-btn'></div>");
	}else{	
	var showmore = jQuery('.search-page-main-contianer .showmore-btn');
	if(showmore.length){
		search.unwrap("<div class='showmore-btn'></div>");
	}
		
	}
	// searchpage add class "category-first-child" to the first ul
	var categoryChild =  jQuery('.category-child ul.item-list__checkbox');
		if(categoryChild.length){
		jQuery('.category-child ul.item-list__checkbox').addClass("category-first-child");
	}
	//searchpage remove span tag inside search btn.
	var searchBtn = jQuery('.onconnect-search-button');
	if(searchBtn.length){
		searchBtn.find('span').remove();
	}
});

// for metrics popup
jQuery('.viewall-metrics').click(function() {
   jQuery('.metrics-popup-container').addClass('show-metrics')
});

jQuery('.close-metrics').click(function() {
   jQuery('.metrics-popup-container').removeClass('show-metrics')
});

jQuery('.view-all-popup').click(function() {
   jQuery('.metrics-popup-container').addClass('show-metrics')
});
jQuery('.close-metrics').click(function() {
   jQuery('.metrics-popup-container').removeClass('show-metrics')
});
// for moble design
jQuery('#view-all-mobile').click(function() {
  jQuery('.metrics-popup-container').addClass('show-metrics')
});

// backscroll disabling
// jQuery("#view-all-mobile").click(function () {
//     document.documentElement.style.overflow = "hidden";
//     document.body.scroll = "no";
// });
// jQuery(".close-metrics").click(function () {
//     document.documentElement.style.overflow = "scroll";
//     document.body.scroll = "yes";
// });
jQuery("body").on('click','.description-show-more',function () {
	   
    jQuery(".description-trim").addClass("hidden");
    jQuery(".description-full").removeClass("hidden");
  });


  jQuery('.cancel-btn .js-form-submit').click(function() {
    window.location.href = '/home';
    return false;
});