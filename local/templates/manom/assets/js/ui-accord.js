'use strict';

// $(function () {
//   $("#accordion").accordion({
//     active: 1,
//     collapsible: true
//   });

// });

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function () {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.hight === "100%") {
      panel.style.display = "0";
    } else {
      panel.style.display = "100%";
    }
  });
}
