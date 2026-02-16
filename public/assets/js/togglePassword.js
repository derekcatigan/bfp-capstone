// public\assets\js\togglePassword.js
$("#togglePassword").on("change", function () {
    const passwordInput = $("#password");

    if (this.checked) {
        passwordInput.attr("type", "text");
    } else {
        passwordInput.attr("type", "password");
    }
});
