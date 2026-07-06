(function () {
  "use strict";

  function $(id) {
    return document.getElementById(id);
  }

  var bookBtn = $("bookBtn");
  var alertBtn = $("alertBtn");
  var infoAlert = $("infoAlert");

  function showMessage(msg) {
    if (!infoAlert) return;
    infoAlert.textContent = msg;
    infoAlert.classList.remove("d-none");
  }

  if (bookBtn) {
    bookBtn.addEventListener("click", function () {
      showMessage(
        "Appointment button clicked. Wire this to your booking form/page next."
      );
    });
  }

  if (alertBtn) {
    alertBtn.addEventListener("click", function () {
      showMessage("Local main.js loaded and running successfully.");
    });
  }
})();

