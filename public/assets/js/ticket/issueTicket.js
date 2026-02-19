// public\assets\js\ticket\issueTicket.js

// Live preview update
$("#ticketForm input, #ticketForm textarea, #ticketForm select").on(
    "input change",
    function () {
        let name = $(this).attr("name");
        let value;

        if ($(this).is("select")) {
            value = $(this).find("option:selected").text();
        } else {
            value = $(this).val();
        }

        $("#preview_" + name).text(value);
    },
);

// Auto fill balance tank based on selected vehicle
$("#vehicle_id").on("change", function () {
    let selected = $(this).find("option:selected");
    let fuelLevel = selected.data("fuel");

    if (fuelLevel !== undefined) {
        $("#balance_tank").val(fuelLevel).trigger("input");
    }
});

// Ticket Form
$("#ticketForm").on("submit", function (e) {
    e.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
        url: "/ticket/store",
        method: "POST",
        data: formData,
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        success: function (response) {
            if (response.status === "success") {
                toastr.success(response.message);

                $("#ticketForm")[0].reset();
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
