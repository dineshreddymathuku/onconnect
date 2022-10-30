jQuery(".mobile-hamburger").click(function () {
    jQuery(".mobile-menu-popup").css({
    "visibility": "visible",
        "opacity": "1",
})
});
jQuery(".mobile-close").click(function () {
    jQuery(".mobile-menu-popup").css({
    "visibility": "hidden",
    "opacity": "0",
})
});
//display first child on click on parent
jQuery(".hover-menu-list-mobile").click(function (event) {
event.preventDefault();
jQuery(".hover-menu-list-mobile").removeClass("mobile-hide");
jQuery(this).siblings().children().addClass("mobile-show");
jQuery(".header-mobile-main-menu").css("padding","0");
jQuery(".hover-menu-list-mobile").addClass("mobile-hide");
});
jQuery(".hover-menu-list-mobile").click(function (event) {
    event.preventDefault();
    jQuery(".diseaseareas-products").addClass("mobile-hide");
});
//subchild popup
jQuery(".mobile-right-arrow").click(function (event) {
jQuery(".mobile-right-arrow img").css("display", "none");
jQuery(this).siblings().addClass("mobile-show").removeClass('mobile-hide');
jQuery(".mobile-secondary-menu-parent").css({
    "padding":"0",
    "display":"none"
});
});
jQuery(".mobile-right-arrow").click(function (event) {
jQuery(this).siblings().addClass("mobile-show").removeClass('mobile-hide');
jQuery(".mobile-secondary-menu-parent").css({
    "padding":"0",
    "display":"none"
});
jQuery(".back-arrow").css("display","none");
jQuery(this).children().addClass("mobile-hide")
});

// back functionalty
jQuery(".back-arrow-2").click(function (event) {
    event.preventDefault();
    jQuery('.mobile-right-arrow img').css("display","block")
    jQuery(this).parent().addClass("mobile-hide").removeClass('mobile-show');
    jQuery(this).parent().siblings('a').addClass("mobile-show").removeClass('mobile-hide');
    jQuery(".mobile-secondary-menu-parent").css({
        "display":"inline-block"
    });
    jQuery(".back-arrow").css("display","block");
});
jQuery(".back-arrow").click(function (event) {
    event.preventDefault();
    jQuery(this).parent().parent().removeClass("mobile-show");
    jQuery(".hover-menu-list-mobile").removeClass("mobile-hide");
    jQuery(".hover-menu-list-mobile").addClass("hover-menu-list-mobile");
    jQuery(".header-mobile-main-menu").css("padding-bottom","28px");
});
// for disease areas
jQuery(".mobile-right-arrow-diseaseareas").click(function (event) {
    jQuery(".back-arrow").css("display", "none");
    jQuery(".Indications").css("display", "none");
    jQuery(".mobile-right-arrow-diseaseareas img").css("display", "none");
    jQuery(".group-name-mobile").css("display", "none");
    jQuery(this).siblings().addClass("mobile-show").removeClass('mobile-hide');
    jQuery(".hover-menu-list-mobile-second").css({
        "padding":"0",
        "display":"block",
    });
    jQuery(".mobile-secondary-menu-parent-diseaseareas").css({
        "padding":"0",
        "display":"none"
    });
});

jQuery(".back-arrow-2").click(function (event) {
    event.preventDefault();
    jQuery(".back-arrow").css("display", "block");
    jQuery(".group-name-mobile").css("display", "block");
    jQuery(".Indications").css("display", "block");
    jQuery('.mobile-right-arrow-diseaseareas img').css("display","block")
    jQuery(this).parent().addClass("mobile-hide").removeClass('mobile-show');
    jQuery(this).parent().siblings('a').addClass("mobile-show").removeClass('mobile-hide');
    jQuery(".hover-menu-list-mobile-second").css({
        "padding": "10px 0 16px 24px",
        "display":"flex",
    });
    jQuery(".mobile-secondary-menu-parent-diseaseareas").css({
        "display":"inline-block"
    });
    jQuery(".back-arrow").css("display","block");
});

//disabled background scroll
jQuery(".mobile-hamburger").click(function () {
    document.documentElement.style.overflow = "hidden";
    document.body.scroll = "no";
});
jQuery(".mobile-close").click(function () {
    document.documentElement.style.overflow = "scroll";
    document.body.scroll = "yes";
});
jQuery("#mobile-hamburger").click(function () {
jQuery(".quick-links").css("z-index","0")
});
jQuery(".mobile-close").click(function () {
jQuery(".quick-links").css("z-index","10")
});

//for filters
jQuery(".filter-icon").click(function () {
    jQuery(".term-popup").css({
        "visibility": "visible",
        "opacity": "1",
    })
});
jQuery(".filter-close-icon").click(function () {
jQuery(".term-popup").css({
    "visibility": "hidden",
    "opacity": "0",
})
});

// search filters popup
jQuery(".search-icon").click(function () {
jQuery(".mobile-search-categeory").css({
    "visibility": "visible",
    "opacity": "1",
})
});
jQuery(".search-close").click(function () {
    jQuery(".mobile-search-categeory").css({
    "visibility": "hidden",
    "opacity": "0",
})
});

