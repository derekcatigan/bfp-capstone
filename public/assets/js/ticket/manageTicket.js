// public\assets\js\ticket\manageTicket.js
function formatFullDate(dateString) {
    if (!dateString) return "";

    const date = new Date(dateString);

    // Options for full month format
    const options = { year: "numeric", month: "long", day: "numeric" };

    return date.toLocaleDateString("en-US", options);
}

function formatTime12Hour(time) {
    if (!time) return "";

    // If it includes seconds (HH:mm:ss), remove seconds
    time = time.substring(0, 5);

    let [hour, minute] = time.split(":");
    hour = parseInt(hour);

    let ampm = hour >= 12 ? "PM" : "AM";
    hour = hour % 12;
    hour = hour ? hour : 12; // 0 becomes 12

    return `${hour}:${minute} ${ampm}`;
}

$(document).on("click", ".btn-view-ticket", function () {
    let id = $(this).data("id");

    $.get(`/tickets/${id}`, function (ticket) {
        // ADMIN INFO
        $("#preview_control_no").text(ticket.control_no);
        $("#preview_date").text(formatFullDate(ticket.ticket_date));

        $("#preview_driver_id").text(
            ticket.driver?.profile?.first_name +
                " " +
                ticket.driver?.profile?.last_name,
        );

        $("#preview_vehicle_id").text(ticket.vehicle?.plate_number);
        $("#preview_authorized_passenger").text(ticket.authorized_passenger);
        $("#preview_places_visit").text(ticket.place);
        $("#preview_purpose").text(ticket.purpose);

        // TIME
        $("#preview_time_departed").text(
            formatTime12Hour(ticket.time_departed_garage),
        );

        $("#preview_time_arrival_destination").text(
            formatTime12Hour(ticket.time_arrival_destination),
        );

        $("#preview_time_departure_destination").text(
            formatTime12Hour(ticket.time_departure_destination),
        );

        $("#time_arrival_garage").text(
            formatTime12Hour(ticket.time_arrival_garage),
        );

        // NUMERIC
        $("#preview_distance").text(ticket.approx_distance);
        $("#preview_balance_tank").text(ticket.balance_tank);
        $("#preview_issued_stock").text(ticket.issued_stock);
        $("#preview_purchased_trip").text(ticket.purchased_trip);
        $("#preview_deduct_trip").text(ticket.deduct_trip);
        $("#preview_gear_oil").text(ticket.gear_oil_issued);
        $("#preview_lub_oil").text(ticket.lub_oil_issued);
        $("#preview_grease_issued").text(ticket.grease_issued);

        // SPEEDO
        $("#preview_speedometer_start").text(ticket.speedometer_start);
        $("#preview_speedometer_end").text(ticket.speedometer_end);

        // REMARKS
        $("#preview_remarks").text(ticket.remarks);

        // PASSENGERS
        $("#preview_passenger_name1").text(ticket.passenger_name1);
        $("#preview_passenger_date1").text(ticket.passenger_date1);

        $("#preview_passenger_name2").text(ticket.passenger_name2);
        $("#preview_passenger_date2").text(ticket.passenger_date2);

        $("#preview_passenger_name3").text(ticket.passenger_name3);
        $("#preview_passenger_date3").text(ticket.passenger_date3);

        // DRIVER SIGN
        $("#preview_driver_name").text(
            ticket.driver?.profile?.first_name +
                " " +
                ticket.driver?.profile?.last_name,
        );

        // OPEN MODAL
        document.getElementById("ticketPreviewModal").showModal();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const printBtn = document.getElementById("btnPrintTicket");

    printBtn.addEventListener("click", function () {
        const ticketContent =
            document.getElementById("documentTicket").innerHTML;

        // viteCss comes from Blade
        const printWindow = window.open("", "", "width=800,height=600");

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Ticket</title>
                    <link rel="stylesheet" href="${viteCss}">
                    <link rel="stylesheet" href="${window.location.origin}/assets/css/preview-section.css">
                    <style>
                        @media print {
                            @page { size: Legal; margin: 15mm; }
                            body { margin: 0; padding: 0; font-family: sans-serif; }
                            #documentTicket {
                                width: 100%; height: auto; overflow: visible !important;
                                page-break-inside: avoid;
                            }
                            .hidden { display: block !important; }
                            button, .modal-action { display: none !important; }
                        }
                    </style>
                </head>
                <body>
                    ${ticketContent}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();

        printWindow.onload = function () {
            printWindow.print();
            printWindow.close();
        };
    });
});
