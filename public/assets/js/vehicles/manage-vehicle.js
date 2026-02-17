// public\assets\js\vehicles\manage-vehicle.js

$(".btn-view").on("click", function () {
    const vehicleId = $(this).closest(".card-body").data("id");

    $.get(`/vehicle/${vehicleId}`, function (res) {
        const v = res.vehicle;

        // TEXT DATA
        $("#modalPlate").text(v.plate_number ?? "N/A");
        $("#modalMakeModel").text(
            `${v.make ?? "Unknown"} ${v.model ?? ""} (${v.year ?? "-"})`,
        );

        // IMAGE + FALLBACK LOGIC
        const image = $("#modalImage");
        const fallback = $("#imageFallback");

        if (v.image) {
            const path = "/storage/" + v.image;

            image.attr("src", path).removeClass("hidden");
            fallback.addClass("hidden");

            // If file exists in DB but missing in storage
            image.off("error").on("error", function () {
                image.addClass("hidden");
                fallback.removeClass("hidden");
            });
        } else {
            image.addClass("hidden");
            fallback.removeClass("hidden");
        }

        // Populate fallback card
        $("#fallbackPlate").text(v.plate_number ?? "Unknown Vehicle");
        $("#fallbackMake").text(`${v.make ?? ""} ${v.model ?? ""}`);
        $("#fallbackStatus").text("Image Not Set");

        // BADGES / DETAILS
        $("#modalType").text(v.vehicle_type ?? "N/A");
        $("#modalColor").text(v.color ?? "N/A");
        $("#modalEngine").text(v.engine_number ?? "Not registered");
        $("#modalChassis").text(v.chassis_number ?? "Not registered");
        $("#modalStatus").text(v.status ?? "Unknown");

        // NOTES FALLBACK
        $("#modalDescription").text(
            v.description
                ? v.description
                : "No notes available for this vehicle.",
        );

        // FUEL
        let fuelPercentage = parseFloat(res.fuelPercentage ?? 0).toFixed(1);

        $("#fuelGauge").val(fuelPercentage);
        $("#modalFuel").text(fuelPercentage + "%");

        let gauge = $("#fuelGauge");
        gauge.removeClass("progress-success progress-warning progress-error");

        if (fuelPercentage > 50) gauge.addClass("progress-success");
        else if (fuelPercentage > 20) gauge.addClass("progress-warning");
        else gauge.addClass("progress-error");

        // OPEN MODAL
        document.getElementById("vehicleModal").showModal();
    });
});

$("#editVehicleForm").on("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            if (res.success) {
                toastr.success(res.message);
            }
        },
        error: function (err) {
            if (err.status === 422) {
                let errors = err.responseJSON.errors;
                $.each(errors, function (key, messages) {
                    toastr.error(messages[0]);
                });
            } else {
                toastr.error("Something went wrong!");
            }
        },
    });
});
