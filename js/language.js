
function setFr() {
  $.ajax({
    url: "include/langues.php",
    type: "post",
    data: { setLangue: "fr" },
    success: function (response) {
      window.location.reload();
    },
  });
}
function setNl() {
  $.ajax({
    url: "include/langues.php",
    type: "post",
    data: { setLangue: "nl" },
    success: function (response) {
      window.location.reload();
    },
  });
}
function setEn() {
  $.ajax({
    url: "include/langues.php",
    type: "post",
    data: { setLangue: "en" },
    success: function (response) {
      window.location.reload();
    },
  });
}
