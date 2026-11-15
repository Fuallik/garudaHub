// Konfirmasi sebelum submit: <form data-confirm="Pesan konfirmasi">
document.addEventListener("submit", function (event) {
    var message = event.target.getAttribute("data-confirm");

    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});
