// public\assets\js\vehicles\vehicleCreate.js
$("#vehicleForm").on("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "/vehicle/store",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (response) {
            toastr.success(response.message);
            $("#vehicleForm")[0].reset();
        },

        error: function (xhr) {
            // validation errors
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function (key, value) {
                    toastr.error(value[0]);
                });
            } else {
                toastr.error("Server error");
            }
        },
    });
});
