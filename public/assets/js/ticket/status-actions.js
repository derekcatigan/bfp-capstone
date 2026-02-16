let actionUrl = null;

function openConfirm(title, message, url) {
    actionUrl = url;
    $("#confirmTitle").text(title);
    $("#confirmMessage").text(message);
    document.getElementById("confirmModal").showModal();
}

// DRIVER SUBMIT
$(document).on("click", ".btn-submit-ticket", function () {
    openConfirm(
        "Submit Ticket",
        "Are you sure you want to submit this trip ticket?",
        $(this).data("url"),
    );
});

// ADMIN ACTIVATE
$(document).on("click", ".btn-activate-ticket", function () {
    openConfirm(
        "Activate Ticket",
        "Are you sure you want to activate this trip ticket?",
        $(this).data("url"),
    );
});

// CONFIRM YES
$("#confirmYes").on("click", function () {
    $.ajax({
        url: actionUrl,
        type: "POST",
        data: {
            _method: "PATCH",
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            toastr.success(res.message);
            setTimeout(() => location.reload(), 1200);
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON?.message || "Action failed.");
        },
    });

    confirmModal.close();
});
