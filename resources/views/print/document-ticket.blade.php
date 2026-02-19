{{-- resources\views\print\document-ticket.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets/css/preview-section.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    {{-- Preview Section --}}
    <div class="hidden lg:flex lg:justify-center min-w-50 bg-gray-200 rounded shadow p-3 h-250 overflow-auto">
        {{-- Paper --}}
        <div class="bg-white shadow-xl p-10 w-200 h-300 overflow-hidden" id="documentTicket">
            {{-- Header --}}
            <div class="flex justify-center gap-5">
                {{-- Seal Logo --}}
                <div>
                    <img src="{{ asset('assets/logos/MaasinSeal.png') }}" alt="Maasin Seal Logo"
                        class="w-24 h-auto object-cover">
                </div>

                {{-- Header Title --}}
                <div class="text-center text-sm">
                    <p>Republic of the Philippines</p>
                    <p>Province of Southern Leyte</p>
                    <p class="font-bold text-lg">CITY OF MAASIN</p>
                    <p>Office of the <span class="font-semibold underline">MAASIN CITY FIRE STATION</span></p>
                </div>

                {{-- Appendix A --}}
                <div>
                    <p class="font-semibold">Appendix A</p>
                </div>
            </div>

            {{-- Control No --}}
            <div class="flex justify-end mt-5">
                <p class="text-sm">Control No: <span class="form-line text-center font-semibold" style="width: 130px;"
                        id="preview_control_no"></span>
                </p>
            </div>

            <div class="text-center my-5">
                <h1 class="font-bold text-lg">DRIVER'S TRIP TICKET</h1>
                <span id="preview_date" class="text-sm">(Date)</span>
            </div>

            <div class="mb-5">
                {{-- Section A --}}
                <div>
                    <span class="font-semibold text-sm">A. To be filled by the Administrative Official Authorizing
                        Official Travel:</span>
                </div>
                {{-- Contents --}}
                <div class="text-sm pl-3">
                    <ul class="list-decimal list-inside">
                        <li>Name of Driver of the Vehicle: <span class="form-line text-center"
                                id="preview_driver_id"></span>
                        </li>
                        <li>Government car to be used. Plate No.: <span class="form-line text-center"
                                id="preview_vehicle_id"></span>
                        </li>
                        <li>Name of Authorized Passenger: <span class="form-line text-center"
                                id="preview_authorized_passenger"></span>
                        </li>
                        <li>Place or places to be visited/inspected: <span
                                class="form-line overflow-hidden whitespace-nowrap text-ellipsis text-xs text-left"
                                id="preview_places_visit" style="width: 100%;"></span>
                        </li>
                        <li>Purpose: <span class="form-line" id="preview_purpose" style="width: 100%;"></span></li>
                    </ul>
                </div>

                <div class="flex justify-end mt-16">
                    <div class="flex flex-col text-center text-sm">
                        <span class="form-line"></span>
                        <p>Head of Office or his Duly</p>
                        <p>Authorized Representative</p>
                    </div>
                </div>

                {{-- Section B --}}
                <div>
                    <span class="font-semibold text-sm">B. To be filled by the Driver:</span>
                </div>

                {{-- Contents --}}
                <div class="text-sm pl-3">
                    <ul class="list-decimal list-inside">
                        <li>Time of departure from Office/Garage: <span class="form-line text-center"
                                id="preview_time_departed"></span>
                        </li>
                        <li>Time of arrival at (per No. 4 above): <span class="form-line text-center"
                                id="preview_time_arrival_destination"></span></li>
                        <li>Time of departure from (per No. 4 above): <span class="form-line text-center"
                                id="preview_time_departure_destination"></span></li>
                        <li>Time of arrival back to Office/Garage: <span class="form-line text-center"
                                id="time_arrival_garage"></span>
                        </li>
                        <li>Approximate distance travelled (to and from): <span class="form-line text-center"
                                id="preview_distance"></span></li>
                        <li>
                            Gasoline issued, purchase and consumed:
                            <div class="text-sm pl-5">
                                <ol class="list-[lower-alpha] list-inside">
                                    <li>Balance in Tank: <span class="form-line text-center"
                                            id="preview_balance_tank"></span>liters
                                    </li>
                                    <li>Issued by Office from Stock: <span class="form-line text-center"
                                            id="preview_issued_stock"></span>liters</li>
                                    <li>Add purchased during trip (TOTAL): <span class="form-line text-center"
                                            id="preview_purchased_trip"></span>liters</li>
                                    <li>Deduct used during the trip (to and from): <span class="form-line text-center"
                                            id="preview_deduct_trip"></span>liters
                                    </li>
                                    <li>Balance in tank at the end of trip:
                                        <span class="form-line text-center" id="preview_end_balance"></span> liters
                                    </li>
                                </ol>
                            </div>
                        </li>
                        <li>
                            Gear oil issued: <span class="form-line text-center" id="preview_gear_oil"></span>
                        </li>
                        <li>
                            Lub. oil issued: <span class="form-line text-center" id="preview_lub_oil"></span>
                        </li>
                        <li>
                            Grease oil issued: <span class="form-line text-center" id="preview_grease_issued"></span>
                        </li>
                        <li>
                            Speedometer readings, if any:
                            <div class="text-sm pl-5">
                                <ol class="list-[lower-alpha] list-inside">
                                    <li>At beginning of trip: <span class="form-line text-center"
                                            id="preview_speedometer_start"></span>
                                    </li>
                                    <li>At end of trip: <span class="form-line text-center"
                                            id="preview_speedometer_end"></span>
                                    </li>
                                    <li>
                                        Distance travelled (per No. 5 above): <span class="form-line text-center"
                                            id="preview_distance"></span>
                                    </li>
                                    <li>Deduct used during the trip (to and from): <span
                                            class="form-line text-center"></span>
                                    </li>
                                </ol>
                            </div>
                        </li>
                        <li>
                            Remarks: <span class="form-line" style="width: 500px;" id="preview_remarks"></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="text-center mb-3">
                <p class="text-xs italic mb-3">I hereby certify to the correctness of the above statement of record of
                    travel.
                </p>
                <span class="form-line text-sm" id="preview_driver_name"></span>
                <p class="font-bold mb-5">Driver</p>
                <p class="text-xs italic">I hereby certify that I used this car on official business as stated above.
                </p>
            </div>

            <div class="flex justify-evenly items-center gap-3 text-xs">
                <div class="text-center space-y-1">
                    <span class="form-line text-xs" style="width: 220px;">
                        <span id="preview_passenger_name1"></span>
                        <span id="preview_passenger_date1"></span>
                    </span>
                    <p>Name of Passenger/Date</p>
                </div>
                <div class="text-center space-y-1">
                    <span class="form-line text-xs" style="width: 220px;">
                        <span id="preview_passenger_name2"></span>
                        <span id="preview_passenger_date2"></span>
                    </span>
                    <p>Name of Passenger/Date</p>
                </div>
                <div class="text-center space-y-1">
                    <span class="form-line text-xs" style="width: 220px;">
                        <span id="preview_passenger_name3"></span>
                        <span id="preview_passenger_date3"></span>
                    </span>
                    <p>Name of Passenger/Date</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        const viteCss = "{{ Vite::asset('resources/css/app.css') }}";
    </script>
</body>

</html>