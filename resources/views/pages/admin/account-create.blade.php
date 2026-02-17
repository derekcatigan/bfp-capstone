{{-- resources\views\pages\admin\account-create.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="max-w-5xl mx-auto p-3">
        {{-- Header Title --}}
        <div class="mb-10">
            <h1 class="text-xl font-bold tracking-widest">
                CREATE USERS FORM
            </h1>
        </div>

        {{-- Create User/Driver Form --}}
        <form id="createUserForm">
            @csrf

            {{-- Account Details --}}
            <div class="p-3 border border-gray-300 rounded-sm shadow mb-3">
                <div class="flex items-center gap-3 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path
                            d="M12 11c1.71 0 3-1.29 3-3s-1.29-3-3-3-3 1.29-3 3 1.29 3 3 3m0-4c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1m1 5h-2c-2.76 0-5 2.24-5 5v.5c0 .83.67 1.5 1.5 1.5h9c.83 0 1.5-.67 1.5-1.5V17c0-2.76-2.24-5-5-5m-5 5c0-1.65 1.35-3 3-3h2c1.65 0 3 1.35 3 3zm-1.5-6c.47 0 .9-.12 1.27-.33a5.03 5.03 0 0 1-.42-4.52C7.09 6.06 6.8 6 6.5 6 5.06 6 4 7.06 4 8.5S5.06 11 6.5 11m-.39 1H5.5C3.57 12 2 13.57 2 15.5v1c0 .28.22.5.5.5H4c0-1.96.81-3.73 2.11-5m11.39-1c1.44 0 2.5-1.06 2.5-2.5S18.94 6 17.5 6c-.31 0-.59.06-.85.15a5.03 5.03 0 0 1-.42 4.52c.37.21.79.33 1.27.33m1 1h-.61A6.97 6.97 0 0 1 20 17h1.5c.28 0 .5-.22.5-.5v-1c0-1.93-1.57-3.5-3.5-3.5">
                        </path>
                    </svg>
                    <h2 class="font-semibold tracking-widest">ACCOUNT DETAILS</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 mb-3">
                    {{-- Firstname --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Firstname: <span class="label text-red-500">Required</span></legend>
                        <input type="text" name="firstname" id="firstname" class="input w-full"
                            placeholder="Enter firstname">
                    </fieldset>

                    {{-- Middlename --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Middlename: <span class="label">Optional</span></legend>
                        <input type="text" name="middlename" id="middlename" class="input w-full"
                            placeholder="Enter middlename">
                    </fieldset>

                    {{-- Lasname --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Lastname: <span class="label text-red-500">Required</span></legend>
                        <input type="text" name="lastname" id="lastname" class="input w-full" placeholder="Enter lastname">
                    </fieldset>

                    {{-- Suffix --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Suffix: <span class="label">Optional</span></legend>
                        <select name="suffix" id="suffix" class="select w-full">
                            <option disabled selected>Select suffix</option>
                            <option value="Sr.">Sr.</option>
                            <option value="Jr.">Jr.</option>
                            <option value="III.">III.</option>
                            <option value="IV.">IV.</option>
                            <option value="V.">V.</option>
                        </select>
                    </fieldset>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 mb-3">
                    {{-- Username --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Username: <span class="label text-red-500">Required</span></legend>
                        <input type="text" name="username" id="username" class="input w-full" placeholder="Enter username">
                    </fieldset>

                    {{-- Email --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Email: <span class="label text-red-500">Required</span>
                        </legend>
                        <input type="email" name="email" id="email" class="input w-full" placeholder="Enter email">
                    </fieldset>

                    {{-- Phone --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Phone: <span class="label text-red-500">Required</span>
                        </legend>
                        <input type="tel" name="phone" id="phone" class="input tabular-nums" placeholder="Phone"
                            pattern="[0-9]*" minlength="11" maxlength="11" />
                    </fieldset>

                    {{-- Role --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Role: <span class="label text-red-500">Required</span>
                        </legend>
                        <select name="role" id="role" class="select w-full">
                            <option disabled selected>Select role</option>
                            @foreach (App\Enum\RoleEnum::cases() as $role)
                                <option value="{{ $role->value }}">{{ $role->label() }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-3">
                    {{-- Password --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Password: <span class="label text-red-500">Required</span></legend>
                        <input type="password" name="password" id="password" class="input w-full"
                            placeholder="Enter password">
                    </fieldset>

                    {{-- Password Confirmation --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Confirm Password: <span class="label text-red-500">Required</span>
                        </legend>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input w-full"
                            placeholder="Confirm password">
                    </fieldset>

                    {{-- Status --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Status: <span class="label text-red-500">Required</span>
                        </legend>
                        <select name="status" id="status" class="select w-full">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </fieldset>
                </div>
            </div>

            {{-- Driver Details --}}
            <div class="p-3 border border-gray-300 rounded-sm shadow mb-3">
                <div class="flex items-center gap-3 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path
                            d="M19.1 7.8c-.38-.5-.97-.8-1.6-.8H15V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2 0 1.65 1.35 3 3 3s3-1.35 3-3h4c0 1.65 1.35 3 3 3s3-1.35 3-3c1.1 0 2-.9 2-2v-3.67c0-.43-.14-.86-.4-1.2zM17.5 9l1.5 2h-4V9zM7 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm2.23-3s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q7.375 15 7 15c-.375 0-.49.04-.72.09-.07.02-.14.05-.21.07-.16.05-.31.11-.45.19-.07.04-.15.08-.22.13-.13.09-.26.18-.38.29-.06.05-.12.1-.18.16-.02.03-.05.04-.08.07h-.77V6h9v10H9.22ZM17 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm3-3h-.77s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q17.375 15 17 15c-.375 0-.47.04-.7.09-.06.01-.12.03-.18.05-.18.06-.36.13-.52.22l-.12.06c-.17.1-.33.21-.48.35v-2.76h5v3Z">
                        </path>
                    </svg>
                    <h2 class="font-semibold tracking-widest">DRIVER DETAILS</h2>
                </div>

                <div role="alert" class="alert alert-info flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path
                            d="M19.96 8.52c.02-.17.04-.35.04-.52 0-2.38-2.14-4.29-4.52-3.96C14.79 2.81 13.47 2 12 2s-2.79.8-3.48 2.04C6.14 3.72 4 5.63 4 8c0 .17.01.35.04.52C2.81 9.21 2 10.53 2 12s.8 2.79 2.04 3.48c-.02.17-.04.35-.04.52 0 2.38 2.14 4.29 4.52 3.96C9.21 21.19 10.53 22 12 22s2.79-.8 3.48-2.04C17.86 20.28 20 18.37 20 16c0-.17-.01-.35-.04-.52C21.19 14.79 22 13.47 22 12s-.8-2.79-2.04-3.48m-1.44 5.4-1.1.29.43 1.05c.09.23.14.48.14.73 0 1.1-.9 2-2 2-.25 0-.5-.05-.73-.15l-1.05-.43-.29 1.1c-.23.87-1.02 1.48-1.92 1.48s-1.69-.61-1.92-1.48l-.29-1.1-1.05.43c-.23.09-.48.15-.73.15-1.1 0-2-.9-2-2 0-.25.05-.5.14-.73l.43-1.05-1.1-.29C4.61 13.69 4 12.9 4 12s.61-1.69 1.48-1.92l1.1-.29-.43-1.05c-.09-.23-.14-.48-.14-.73 0-1.1.9-2 2-2 .25 0 .5.05.73.15l1.05.43.29-1.1c.23-.87 1.02-1.48 1.92-1.48s1.69.61 1.92 1.48l.29 1.1 1.05-.43c.23-.09.48-.15.73-.15 1.1 0 2 .9 2 2 0 .25-.05.5-.14.73l-.43 1.05 1.1.29C19.39 10.31 20 11.1 20 12s-.61 1.69-1.48 1.92">
                        </path>
                        <path d="M11 11.5h2V16h-2zM11 8h2v2h-2z"></path>
                    </svg>
                    <span>This form is restricted to drivers only. Please provide the required driver details.</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                    {{-- Driver Code --}}
                    {{-- <fieldset class="fieldset">
                        <legend class="fieldset-legend">Driver Code: <span class="label text-red-500">Required</span>
                        </legend>
                        <input type="text" name="driver_code" id="driverCode" class="input w-full"
                            placeholder="Enter driver code" readonly>
                    </fieldset> --}}

                    {{-- License Number --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">License Number: <span class="label text-red-500">Required</span>
                        </legend>
                        <input type="text" name="license" id="licenseNumber" class="input w-full"
                            placeholder="Enter license number">
                    </fieldset>

                    {{-- Department --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Department: <span class="label text-red-500">Required</span>
                        </legend>
                        <input type="text" name="department" id="department" class="input w-full"
                            placeholder="Enter department">
                    </fieldset>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-block btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                        <path
                            d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                        </path>
                    </svg>
                    Create Account
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/account/createAccount.js') }}"></script>
@endpush