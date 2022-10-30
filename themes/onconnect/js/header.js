(function ($, Drupal, once) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      var isOpen = false;
      $(".searchbox-icon").click(function () {
        if (isOpen == false) {
          $(".searchbox").addClass("searchbox-open");
          $(".searchbox-input").focus();
          isOpen = true;
        }
        else {
          $(".searchbox").removeClass("searchbox-open");
          $(".searchbox-input").focusout();
          isOpen = false;
        }
      });
      $(".searchbox-icon").mouseup(function () {
        return false;
      });
      $(".searchbox").mouseup(function () {
        return false;
      });
      $(document).mouseup(function () {
        if (isOpen == true) {
          $(".searchbox-icon").css("display", "block");
          $(".searchbox-icon").click();
        }
      });

      function buttonUp() {
        var inputVal = $(".searchbox-input").val();
        inputVal = $.trim(inputVal).length;
        if (inputVal !== 0) {
          $(".searchbox-icon").css("display", "none");
        }
        else {
          $(".searchbox-input").val("");
          $(".searchbox-icon").css("display", "block");
        }
      }

      $(".searchbox-submit").keyup(function () {
        buttonUp();
      });
      //menu active color
      $('.hover-menu-list').click(function () {
        $(".hover-menu-list").removeClass("visited active");
        $(this).toggleClass("active");
      });
      //primary menu on hover changing color
      $(".header-main-menu").hover(function () {
      $('.header-main-menu').children('a').css("color","#293033");
      $(this).next().children('a').css({
        "color": "#293033",
        "border-bottom":"0"
      });
      $(this).next().next().children('a').css({
        "color": "#293033",
        "border-bottom":"0"
      });
      $(this).next().next().next().children('a').css({
        "color": "#293033",
        "border-bottom":"0"
      });
      $(this).prev().children('a').css({
        "color": "#293033",
        "border-bottom":"0"
      });
      $(this).prev().prev().children('a').css({
        "color": "#293033",
        "border-bottom":"0"
      });
      $(this).prev().prev().prev().children('a').css({
        "color": "#293033",
        "border-bottom":"0"
      });
      $(this).children('a').css({
        "color": "#003FE2",
        "border-bottom": "1px solid #003FE2",
        "padding-bottom":"8px"
      });
      });
      $(".header-navigation").mouseleave(function () {
      $(".hover-menu-list").css({
        "color": "#fff",
        "border-bottom":"0"
      });
      })
      //twitter feed styles
      setTimeout(twitterCardStyles, 1000);
      setTimeout(twitterCardStyles, 2000);
      setTimeout(twitterCardStyles, 3000);
      setTimeout(function () {
        $(".twitter-timeline").contents().find(".timeline-LoadMore-prompt").click(function () {
          setTimeout(twitterCardStyles, 1000);
           window.location.reload();
        });
      }, 1000);
      $('.text-center').show();
      $('#twt-smore').click(function () {
       $('.twitter-timeline').contents().find('.timeline-TweetList > li:not(:lt(3))').show();
       $("#twitter-widget-0").height(1000);
        $('.text-center').hide();
      });
      function twitterCardStyles() {
        $('#twitter-widget-0').css('height','auto');
        $('.twitter-timeline').contents().find('.timeline-TweetList > li:not(:lt(3))').hide();
        $('.twitter-timeline').contents().find('.timeline-Header-title').html('<img alt="twitter-icon" src="/profiles/pfeconconnectpfcom_profile/themes/onconnect/images/icons/twitter.svg" /> <span>The Latest</span><a href="https://twitter.com/PfizerOncMed" target="_blank"> @PfizerOncMed </a>');
        $('.twitter-timeline').contents().find('.timeline-InformationCircle').html('<a href="https://twitter.com/PfizerOncMed" target="_blank">Follow us <img class="twitter-right-icon" alt="right-arrow-icon" src="/profiles/pfeconconnectpfcom_profile/themes/onconnect/images/icons/right-icon-blue.svg" ></a>');
        $('.twitter-timeline').contents().find('.timeline-InformationCircle a').css("color", "#003FE2");
        $('.twitter-timeline').contents().find('.timeline-Header-title a').css("color", "#003FE2");
        $('.twitter-timeline').contents().find('.timeline-Header-title img').css("padding-right", "26px");
        $('.twitter-timeline').contents().find('.timeline-InformationCircle img').css("padding","1px 0 0 11px");
        $(".twitter-timeline").contents().find('head').append('<link rel="stylesheet" href="/profiles/pfeconconnectpfcom_profile/themes/onconnect/css/twitter.css" type="text/css" />');
        $(".twitter-timeline").contents().find('head').append('<link href="https://use.typekit.net/bvg4ezy.css" rel="stylesheet"></link>');    
        $(".twitter-timeline").contents().find(".timeline-TweetList").css({
          "display": "flex",
          "display": "-webkit-box",
          "display": "-moz-box",
          "display": "-ms-flexbox",
          "display": "-webkit-flex",
          "flex-wrap": "wrap",
          "-webkit-flex-wrap": "wrap",
          "-moz-flex-wrap": "wrap",
          "-ms-flex-wrap":" wrap",
          "-o-flex-wrap": "wrap",
          "margin": "46px 0"
        });
        $(".twitter-timeline").contents().find(".timeline-TweetList > li:nth-child(3n-1)").css({
          "margin": "0 auto 32px",
        });
        $(".twitter-timeline").contents().find(".timeline-TweetList-tweet").css({
          "background": "#fff",
          "box-shadow": "0px 0px 0px 1px rgb(63 89 115 / 8%), 0px 2px 1px rgb(63 89 115 / 14%), 0px 1px 3px rgb(63 89 115 / 25%)",
          "border-radius": "8px",
          "padding": "24px",
          "flex": "0 0 28%",
          "-webkit-flex": "0 0 28%",
          "margin-bottom": "32px",
        });
         $(".twitter-timeline").contents().find(".timeline-Widget").css({
          "font-family":"noto-sans",  
          "max-width": "2000px",
          "margin": "0 auto",
          "background": "none",
          "padding": "0 112px"
        });
        $(".twitter-timeline").contents().find(".timeline-Tweet-text").css({
          "padding": "10px 0",
          "margin": "0"
        });
        $(".twitter-timeline").contents().find(".timeline-Header-title").css({
          "font-weight": "600",
          "font-size": "24px",
          "line-height": "40px",
          "max-width": "55%"
        });
        $(".twitter-timeline").contents().find(".timeline-Header-byline").css({
          "font-weight": "600",
          "font-size": "24px",
          "line-height": "40px"
        });
        $(".twitter-timeline").contents().find(".timeline-LoadMore").css({
          "border-top": "1px solid #00048433",
          "position": "relative"
        });
        $(".twitter-timeline").contents().find(".timeline-LoadMore-prompt").css({
          "position": "absolute",
          "top": "-14px",
          "left": "653px",
          "margin": "0 5px",
          "border": "0",
          "background": "#f2f2f8",
          "color": "#003fe2",
          "padding": "0"
        });
        $(".twitter-timeline").contents().find(".timeline-InformationCircle").css({
          "font-weight": "600",
          "font-size": "14px",
          "line-height": "16px",
          "letter-spacing": "0.5px",
          "color": "#003FE2 !important",
          "border": "1px solid #003FE2",
          "padding": "10px 21px",
          "border-radius": "6px",
          "position": "relative",
          "display": "inline-block",
          "float": "right",
          "top": "2px",
        });
        $(".twitter-timeline").contents().find(".timeline-Viewport").css("overflow", "hidden");
        $(".twitter-timeline").contents().find(".SandboxRoot.env-bp-970").css("margin", "0");
        $(".twitter-timeline").contents().find(".timeline-Tweet-actions").css("margin", "0");
        $(".twitter-timeline").contents().find(".timeline-Tweet-media").css("margin", "10px 0px");
        $(".twitter-timeline").contents().find(".SandboxRoot").css("margin", "0");
        $(".twitter-timeline").contents().find(".timeline-Body").css("border", "0");
        $(".twitter-timeline").contents().find(".timeline-Header").css({
          "background": "#f2f2f8",
          "padding": "0"
        });
        $(".twitter-timeline").contents().find(".timeline-Tweet-actions").css("margin", "0");
        $(".twitter-timeline").contents().find(".timeline-Footer").css("display", "none");
        //media query for smaller desktops
        setTimeout(twitterSmallerDesktop, 1000);
        setTimeout(twitterSmallerDesktop, 2000);
        function twitterSmallerDesktop() {
           if($(window).innerWidth() <= 1300) {
            $(".twitter-timeline").contents().find(".timeline-Widget").css({
              "padding": "0 20px"
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList-tweet").css({
              "width": "290px",
              "max-width": "100%",
              "padding": "10px"
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList-tweet").css({
              "flex": "0 0 30%",
              "-webkit-flex": "0 0 30%",
              "margin-bottom": "32px",
            });
            $(".twitter-timeline").contents().find(".TweetAuthor-name").css("font-size", "14px");
            $(".twitter-timeline").contents().find(".timeline-Tweet-text").css("font-size", "14px");
          }
        }
        //media query for tab 
        setTimeout(twitterTab, 1000);
        setTimeout(twitterTab, 2000);
        function twitterTab() {
          if ($(window).innerWidth() <= 1023) {
           $('.twitter-timeline').contents().find('.timeline-TweetList > li:not(:lt(2))').hide();             
            $(".twitter-timeline").contents().find(".timeline-TweetList-tweet").css({
              "max-width": "45%",
              "padding": "10px",
              "flex": "0 0 45%",
              "-webkit-flex":" 0 0 45%",
              "margin-bottom": "32px",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList > li:nth-child(3n-1)").css({
              "margin": "0",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(1)").css({
              "margin-right": "auto",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(2)").css({
              "margin": "0 0 36px 0",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(4)").css({
              "margin-right": "auto",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(5)").css({
              "margin": "0 0 36px 0",
            });
             $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(6)").css({
              "margin-right": "auto",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(7)").css({
              "margin": "0 0 36px 0",
            });
             $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(8)").css({
               "margin-right": "auto",
                "margin-bottom": "36px",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList li:nth-child(9)").css({
              "margin": "0 0 36px 0",
            });
            $(".twitter-timeline").contents().find(".timeline-TweetList").css({
              "margin": "26px 0px",
            })
            $(".twitter-timeline").contents().find(".TweetAuthor-name").css({
              "font-size": "16px",
              "line-height":"1.31"
            });
            $(".twitter-timeline").contents().find(".timeline-Tweet-text").css({
              "font-size": "16px",
              "line-height":"1.31"
            });
            $(".twitter-timeline").contents().find(".timeline-Widget").css({
              "padding": "0"
            });
             $(".twitter-timeline").contents().find(".timeline-Header-title").css({
              "padding": "0 24px"
            });
            $(".twitter-timeline").contents().find(".timeline-InformationCircle").css({
              "display":"none"
            })
            $(".twitter-timeline").contents().find(".timeline-Viewport").css("padding", "0 24px");
          }
        }
        //media query for mobile-devices
        setTimeout(twitterMobile, 1000);
        setTimeout(twitterMobile, 2000);
        function twitterMobile() {
          if ($(window).innerWidth() <= 767) {
           $('.twitter-timeline').contents().find('.timeline-TweetList > li:not(:lt(3))').hide();
            $(".twitter-timeline").contents().find(".timeline-TweetList-tweet").css({
              "width": "327px",
              "max-width": "100%",
              "padding": "10px",
              "flex": "0 0 95%",
              "-webkit-flex":" 0 0 95%",
            });
            $(".twitter-timeline").contents().find(".TweetAuthor-name").css({
              "font-size": "16px",
              "line-height":"1.31"
            });
            $(".twitter-timeline").contents().find(".timeline-Tweet-text").css({
              "font-size": "16px",
              "line-height":"1.31"
            });
            $(".twitter-timeline").contents().find(".timeline-Widget").css({
              "padding": "0"
            });
             $(".twitter-timeline").contents().find(".timeline-Header-title").css({
              "padding": "0 16px"
            });
            $('.twitter-timeline').contents().find('.timeline-Header-title img').css("display", "none");
            $(".twitter-timeline").contents().find(".timeline-InformationCircle").css({
              "display":"none"
            })
            $(".twitter-timeline").contents().find(".timeline-TweetList-tweet").css({
              "margin-bottom": "8px",
            });
            $(".twitter-timeline").contents().find(".timeline-Viewport").css("padding", "0");
          }
        }
      }
      //toogle focus for sceintific platform pages
      $('.section-right a').click(function () {
        $(".section-right a").removeClass("visited focus");
        $(this).toggleClass("visited");
      });
    }
  };
})(jQuery, Drupal, once);

// display subchild categeory when parent is hovered
jQuery(".main-child").hover(function () {
  jQuery(this).parent().next().children().siblings().children().removeClass("display-child");
  jQuery(this).parent().prev().children().siblings().children().removeClass("display-child"); 
  jQuery(this).siblings().children().addClass("display-child");
});
jQuery(".header-navigation").mouseleave(function () {
    jQuery(".dropdown-right").removeClass('display-child');
});
jQuery(".group-name").hover(function () {
  jQuery(this).prev().children().children().removeClass("display-child");
  jQuery(this).next().children().children().removeClass("display-child");
});
// on hover of the dropdown right display the respective parent as active
jQuery(".dropdown-right").hover(function () {
	jQuery(this).parent().siblings().css( 'color','#335bc3');
});
jQuery(".dropdown-right").mouseleave(function () {
    jQuery(this).parent().siblings().css( 'color','black');
});
//on hover change icon color in header menu  
jQuery(".main-child").hover(function (event) {
jQuery(this).css("color", "#335bc3");
jQuery(this).children().eq(1).css("display", "inline");
});
jQuery(".main-child").mouseleave(function () {
jQuery(this).css("color", "black");
jQuery(this).children().eq(1).css("display", "none");
});
jQuery(".dropdown-right").hover(function (event) {
jQuery(this).parent().siblings().children(".blue-icon").css("display", "inline");
});
jQuery(".dropdown-right").mouseleave(function () {
jQuery(this).parent().siblings().children(".blue-icon").css("display", "none");
});
//congresses header drop-down
jQuery('.congress-dropdown').click(function () {
    jQuery("ul.congresses-select").toggle("congress-dropdown-list");
});

//term-pages filters drop-down
jQuery(".filter-type-list").click(function () {
  jQuery('.filter-type-list > ul').not(jQuery(this).children("ul").toggle()).hide();
});
//close dropdown on click of out side the container
jQuery(document).click(function (e) {
  if (jQuery(e.target).closest(".filter-type-list").length === 0) {
     jQuery(".filter-dropdown-type").css("display","none");
  } 
  });
//submit form on select of type
jQuery(function(){
  jQuery('.filter-dropdown-type li').on('change', function () {
    jQuery('#publications-form').submit();
  });
});
//submit form on select of sort
jQuery(function(){
  jQuery('.filter-dropdown-sort li').on('change',function(){
    jQuery('#publications-form').submit();
  });
});

jQuery(function(){
  jQuery('.form-submit li').on('change',function(){
    jQuery('#publications-form').submit();
  });
});
//modal disable back-scroll
 jQuery(window).on({
    'dialog:aftercreate': function (event, dialog, $element, settings) {
      jQuery('body').css('overflow', 'hidden');
    },
    'dialog:beforeclose': function (event, dialog, $element) {
      jQuery('body').css('overflow', '');
    }
  });
//filters-mobile-background scroll disable
jQuery(".filter-icon").click(function () {
    document.documentElement.style.overflow = "hidden";
    document.body.scroll = "no";
});
jQuery(".filter-close-icon").click(function () {
    document.documentElement.style.overflow = "scroll";
    document.body.scroll = "yes";
});
//metrics tooltip
var toolTip = jQuery('.quick-links').find('#headingmetrics');
if(toolTip.length) {
    jQuery('#headingmetrics').addClass('tootl-tip-display');
}
// onclick of search icon go to search page if it has text
    jQuery('.searchbox-icon img').click(function () {
        if (jQuery('.searchbox-input').val() != "") {
              jQuery('.searchbox').submit();

   }
    });
// for mobile design
    jQuery('.mobile-search-icon').click(function () {
      if (jQuery('.mobile-searchbox-input').val() != "") {
        jQuery('.mobile-searchbox').submit();
      }
    });
   jQuery(".description-show-more").click(function () {
	   console.log("test");
    jQuery(".description-trim").addClass("hidden");
    jQuery(".description-full").removeClass("hidden");
  });