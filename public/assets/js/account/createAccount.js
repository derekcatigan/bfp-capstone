// public\assets\js\account\createAccount.js
$("#createUserForm").on("submit", function (e) {
    e.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
        url: "/account/store",
        method: "POST",
        data: formData,
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        success: function (response) {
            if (response.status === "success") {
                toastr.success(response.message);

                $("#createUserForm")[0].reset();
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
