// public\assets\js\account\listAccount.js
let userIdToDelete = null;
const modal = document.getElementById("accountModal");
let originalData = {};
let editMode = false;

/* OPEN MODAL + LOAD DATA */
$(document).on("click", ".view-account", function () {
    let id = $(this).data("id");

    $.get(`/account/${id}`, function (data) {
        $("#user_id").val(data.id);
        $("#first_name").val(data.first_name);
        $("#last_name").val(data.last_name);
        $("#email").val(data.email);
        $("#phone").val(data.phone);
        $("#role").val(data.role);
        $("#department").val(data.department);

        originalData = data;

        setViewMode();
        modal.showModal();
    });
});

/* SWITCH TO EDIT MODE */
$("#editBtn").click(function () {
    editMode = true;
    $("#accountForm input, #accountForm select").prop("disabled", false);

    $("#editBtn").addClass("hidden");
    $("#saveBtn, #cancelBtn").removeClass("hidden");
});

/* CANCEL EDIT */
$("#cancelBtn").click(function () {
    Object.keys(originalData).forEach((key) => {
        $("#" + key).val(originalData[key]);
    });

    setViewMode();
});

/* SAVE */
$("#accountForm").submit(function (e) {
    e.preventDefault();

    let id = $("#user_id").val();

    $.ajax({
        url: `/account/${id}`,
        type: "PUT",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            first_name: $("#first_name").val(),
            last_name: $("#last_name").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            department: $("#department").val(),
            role: $("#role").val(),
        },
        success: function () {
            location.reload();
        },
    });
});

/* VIEW MODE */
function setViewMode() {
    editMode = false;
    $("#accountForm input, #accountForm select").prop("disabled", true);

    $("#editBtn").removeClass("hidden");
    $("#saveBtn, #cancelBtn").addClass("hidden");
}

// === Delete Button ===
$(".delete-btn").on("click", function () {
    userIdToDelete = $(this).data("id");
    $("#deleteModal").prop("checked", true);
});

$("#confirmDeleteBtn").on("click", function () {
    if (!userIdToDelete) return;

    $.ajax({
        url: `/account/${userIdToDelete}`,
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        success: function (response) {
            if (response.status === "success") {
                toastr.success(response.message);
                location.reload();
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
                toastr.error(xhr.responseJSON.message);
            } else {
                toastr.error("Something went wrong.");
            }
        },
        complete: function () {
            $("#deleteModal").prop("checked", false);
            userIdToDelete = null;
        },
    });
});
