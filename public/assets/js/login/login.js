// public\assets\js\login\login.js
$("#loginForm").on("submit", function (e) {
    e.preventDefault();

    const form = $(this);

    $.ajax({
        url: "/login",
        type: "POST",
        data: form.serialize(),
        success: function (response) {
            if (response.status === "success" && response.redirect) {
                toastr.success(response.message);
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 1000);
            } else {
                toastr.error("Unexpected response.");
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
                toastr.error(xhr.responseJSON.message);
            } else {
                toastr.error("Something went wrong.");
            }
        },
    });
});
