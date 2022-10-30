jQuery(".filter-icon").click(function () {
  jQuery('.filers-ul').toggle("show-filters");
  jQuery('.term-publicatations').addClass("term-popup");
});
jQuery(".close-icon").click(function () {
  jQuery('.filers-ul').removeClass("show-filters");
  jQuery('.term-publicatations').removeClass("term-popup");
});

// jQuery('.filter-dropdown-sort li').on('change',function(){
//     jQuery(".term-publicatations").next().addClass("filters-selected");
// });
