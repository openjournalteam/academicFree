$(document).ready(function () {
  removeCitationByCitationStyleLanguagePlugin();
});

function removeCitationByCitationStyleLanguagePlugin() {
  if ($(".item.citation").length) {
    $(".item.citation").remove();
  }
}
