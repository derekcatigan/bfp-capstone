// public\assets\js\storage\storage.js
function submitStorage(formId) {
    $(document)
        .off("submit", formId)
        .on("submit", formId, function (e) {
            e.preventDefault();

            const form = $(this);
            const type = form.find("input[name='transaction_type']").val();
            const amount =
                parseFloat(form.find("input[name='amount']").val()) || 0;
            const currentLevel = parseFloat($("#currentLevel").text()) || 0;

            // Capacity validation ONLY for adding
            if (type === "added" && currentLevel + amount > TANK_CAPACITY) {
                toastr.error(
                    `Cannot add ${amount} L. Exceeds tank capacity (${TANK_CAPACITY} L).`,
                );
                return;
            }

            $.ajax({
                url: "/fuel-storage/store",
                method: "POST",
                data: form.serialize(),
                success: function (res) {
                    toastr.success(res.message);

                    form[0].reset();
                    addStockModal.close();
                    removeStockModal.close();

                    loadTables();
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message ?? "Error occurred");
                },
            });
        });
}

function formatDateTime(dateString) {
    const date = new Date(dateString);

    const options = {
        year: "numeric",
        month: "long",
        day: "2-digit",
        hour: "numeric",
        minute: "2-digit",
        second: "2-digit",
        hour12: true,
    };

    return new Intl.DateTimeFormat("en-US", options).format(date);
}

function formatDateTime(dateString) {
    const date = new Date(dateString);

    const options = {
        year: "numeric",
        month: "long",
        day: "2-digit",
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
    };

    return new Intl.DateTimeFormat("en-US", options).format(date);
}

const TANK_CAPACITY = 1000;

function updateFuelVisual(currentLevel) {
    // Ensure currentLevel is a number
    currentLevel = Number(currentLevel) || 0;

    const percentage = Math.min(
        (currentLevel / TANK_CAPACITY) * 100,
        100,
    ).toFixed(1);

    $("#fuelLevelBar").css("height", `${percentage}%`);
    $("#currentLevel").text(currentLevel.toFixed(2) + " L");
    $("#availableSpace").text((TANK_CAPACITY - currentLevel).toFixed(2) + " L");
    $("#tankCapacity").text(TANK_CAPACITY + " L");
    $("#fillPercentage").text(percentage + "%");
}

function loadTables() {
    $.get("/fuel-storage/list", function (res) {
        // Populate Recent Updates
        let recentHtml = "";
        res.recent.forEach((r) => {
            recentHtml += `
            <tr>
                <td>${formatDateTime(r.transaction_datetime)}</td>
                <td>${r.container_type}</td>
                <td class="${r.transaction_type === "added" ? "text-success" : "text-error"}">
                    ${r.transaction_type.toUpperCase()}
                </td>
                <td>${r.amount}</td>
                <td>${r.running_balance}</td>
                <td>${r.note ?? "No Notes"}</td>
            </tr>`;
        });
        $("#recentUpdatesTable").html(recentHtml);

        // Populate Complete History
        let historyHtml = "";
        res.history.forEach((r) => {
            historyHtml += `
            <tr>
                <td>${formatDateTime(r.transaction_datetime)}</td>
                <td>${r.container_type}</td>
                <td class="${r.transaction_type === "added" ? "text-success" : "text-error"}">
                    ${r.transaction_type.toUpperCase()}
                </td>
                <td>${r.amount}</td>
                <td>${r.running_balance}</td>
                <td>${r.note ?? "No Notes"}</td>
            </tr>`;
        });
        $("#historyTable").html(historyHtml);

        // ✅ Use currentLevel from backend
        const currentLevel = res.currentLevel;
        updateFuelVisual(currentLevel);
    });
}

submitStorage("#addStockForm");
submitStorage("#removeStockForm");

$(document).ready(function () {
    loadTables();
});
