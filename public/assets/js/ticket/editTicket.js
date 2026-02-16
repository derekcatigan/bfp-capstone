$("#editTicketForm").on("submit", function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = form.serialize();
    let updateUrl = form.data("update-url");
    let indexUrl = form.data("index-url");

    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    $.ajax({
        url: updateUrl,
        type: "POST",
        data: formData,
        success: function (response) {
            if (response.success) {
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href = indexUrl;
                }, 3000);
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
