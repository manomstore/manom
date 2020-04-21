'use strict';

$(function () {
  $("#accordion").accordion({
    heightStyle: "content",
    collapsible: true
  });

  if (isMobile()) {
    $("#accord-mobile").accordion({
      heightStyle: "content",
      collapsible: true
    });
  }

});


