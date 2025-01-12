function sendMail(event) {
  event.preventDefault();

  fetch("https://formspree.io/f/meqbpdao", {
    method: "POST",
    body: new FormData(event.target),
    headers: { 'Accept': 'application/json' }
  }).then(() => {
    // success ----------------------------------------------
    openInfo('Deine Nachricht wurde versendet');
    event.target.reset();
  }).catch((error) => {
    // error ------------------------------------------------
    openInfo(`
      Beim versenden Deiner Nachricht ist ein Fehler aufgetreten.<br>
      <b>${error.message}</b><br>
      Versuche es später noch einmal.
      `);
  });
}


function openInfo(message) {
  document.getElementById('contact_dialog').innerHTML = `<span>${message}</span>`;
  document.getElementById('contact_overlay').classList.remove('hide');
}


function closeInfo() {
  document.getElementById('contact_overlay').classList.add('hide');
  document.getElementById('contact_dialog').innerHTML = '';
}