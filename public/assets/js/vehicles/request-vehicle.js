function loadVehicles() {
    $.ajax({
        url: "/vehicles/available",
        type: "GET",
        beforeSend: function () {
            $("#vehicleSelect").html(
                '<option value="">Loading vehicles...</option>',
            );
        },
        success: function (vehicles) {
            let options = '<option value="">Select Vehicle</option>';

            if (vehicles.length === 0) {
                options = '<option value="">No Available Vehicles</option>';
            } else {
                vehicles.forEach((vehicle) => {
                    options += `
                            <option value="${vehicle.id}">
                                ${vehicle.plate_number} - ${vehicle.model}
                            </option>
                        `;
                });
            }

            $("#vehicleSelect").html(options);
        },
        error: function () {
            toastr.error("Failed to load vehicles.");
        },
    });
}

// Make function global so button can call it
window.openVehicleModal = function () {
    loadVehicles();
    document.getElementById("vehicle_modal").showModal();
};

// Submit via AJAX
$("#vehicleRepairForm").submit(function (e) {
    e.preventDefault();

    $.ajax({
        url: "/vehicle/request-repair/store",
        type: "POST",
        data: $(this).serialize(),
        beforeSend: function () {
            $("button[type='submit']").prop("disabled", true);
        },
        success: function (response) {
            toastr.success(response.message);
            $("#vehicle_modal")[0].close();
            $("#vehicleRepairForm")[0].reset();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                toastr.error(xhr.responseJSON.message);
            } else {
                toastr.error("Something went wrong.");
            }
        },
        complete: function () {
            $("button[type='submit']").prop("disabled", false);
        },
    });
});
