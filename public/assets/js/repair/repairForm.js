$("#repairForm").submit(function (e) {
    e.preventDefault();

    let formData = $(this).serialize();

    $.ajax({
        url: "/repair/vehicle/store",
        type: "POST",
        data: formData,
        success: function (res) {
            if (res.success) {
                toastr.success(res.message);
                $("#repairForm")[0].reset();
            }
        },
        error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            if (errors) {
                for (let key in errors) {
                    toastr.error(errors[key][0]);
                }
            } else {
                toastr.error("Something went wrong!");
            }
        },
    });
});
