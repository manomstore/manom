'use strict';

$(function () {
  $("#accordion").accordion({
    heightStyle: "content",
    collapsible: true
  });

  if (window.isMobile()) {
    $("#accord-mobile").accordion({
      heightStyle: "content",
      collapsible: true
    });
  }

});


