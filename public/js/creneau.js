$("#add-creneau").click(function () {
  console.log("aaaaaaaaaa");
  const index = +$("#widgets-counter").val();

  const tmpl = $("#annonce_creneaus")
    .data("prototype")
    .replace(/__name__/g, index);

  $("#annonce_creneaus").append(tmpl);
  $("#widgets-counter").val(index + 1);
  handleDeleteButtons();
});

function handleDeleteButtons() {
  $('button[data-action="delete"]').click(function () {
    const target = this.dataset.target;
    $(target).remove();
  });
}
function updateCounter() {
  const count = +$("#annonce_creneaus div.form-group").length;
  $("#widgets-counter").val(count);
}

handleDeleteButtons();
updateCounter();
