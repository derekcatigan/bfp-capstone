{{-- resources\views\pages\shared\profile.blade.php --}}
@extends('layouts.layout')

@section('content')

    <div class="max-w-6xl mx-auto mt-8 grid md:grid-cols-3 gap-6">

        {{-- LEFT PROFILE CARD --}}
        <div class="card bg-linear-to-br from-base-100 to-base-200 border border-base-300 shadow-2xl overflow-hidden">

            {{-- HEADER STRIP --}}
            <div class="h-16 bg-primary relative">
                <div class="absolute -bottom-12 left-1/2 -translate-x-1/2">

                    {{-- Avatar --}}
                    <div class="avatar">
                        <div
                            class="w-24 rounded-full ring-4 ring-white shadow-xl bg-primary text-white flex items-center justify-center text-3xl font-bold">
                            {{ strtoupper(substr($user->profile->first_name ?? $user->username, 0, 1)) }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body items-center text-center pt-14">

                {{-- NAME --}}
                <h2 class="text-2xl font-extrabold tracking-wide">
                    {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                </h2>

                {{-- POSITION BADGE --}}
                <div class="badge badge-lg {{ $user->profile->badgeClass() }} mt-1 px-4 py-3 shadow">
                    {{ $user->profile->position ?? 'User' }}
                </div>

                {{-- EMPLOYEE CODE --}}
                <p class="text-xs text-gray-500 tracking-widest mt-1">
                    ID: {{ $user->employee_code ?? 'N/A' }}
                </p>

                <div class="divider my-2"></div>

                {{-- CONTACT INFO --}}
                <div class="w-full text-sm space-y-3">

                    <div class="flex justify-between items-center border-b pb-1">
                        <span class="text-gray-500">Username</span>
                        <span class="font-medium">{{ $user->username }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b pb-1">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium text-right break-all">{{ $user->email }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b pb-1">
                        <span class="text-gray-500">Phone</span>
                        <span class="font-medium">{{ $user->phone }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Department</span>
                        <span class="font-medium text-right">{{ $user->profile->department ?? '—' }}</span>
                    </div>

                </div>

            </div>
        </div>

        <div class="md:col-span-2 space-y-6">

            {{-- PROFILE INFORMATION --}}
            <div class="card bg-white border border-gray-300 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Profile Information</h2>

                    <form id="profileForm" class="grid md:grid-cols-2 gap-4">
                        @csrf

                        <div>
                            <label class="label">First Name</label>
                            <input type="text" name="first_name" class="input input-bordered w-full"
                                value="{{ $user->profile->first_name }}">
                        </div>

                        <div>
                            <label class="label">Last Name</label>
                            <input type="text" name="last_name" class="input input-bordered w-full"
                                value="{{ $user->profile->last_name }}">
                        </div>

                        <div>
                            <label class="label">Middle Name</label>
                            <input type="text" name="middle_name" class="input input-bordered w-full"
                                value="{{ $user->profile->middle_name }}">
                        </div>

                        <div>
                            <label class="label">Suffix</label>
                            <input type="text" name="suffix" class="input input-bordered w-full"
                                value="{{ $user->profile->suffix }}">
                        </div>

                        <div>
                            <label class="label">Email</label>
                            <input type="email" name="email" class="input input-bordered w-full" value="{{ $user->email }}">
                        </div>

                        <div>
                            <label class="label">Phone</label>
                            <input type="text" name="phone" class="input input-bordered w-full" value="{{ $user->phone }}">
                        </div>

                        <div class="md:col-span-2">
                            <button class="btn btn-primary">Save Changes</button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- CHANGE PASSWORD --}}
            <div class="card bg-white border border-gray-300 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-error">Change Password</h2>

                    <form id="passwordForm" class="space-y-4">
                        @csrf

                        <div>
                            <label class="label">Current Password</label>
                            <input type="password" name="current_password" class="input input-bordered w-full">
                        </div>

                        <div>
                            <label class="label">New Password</label>
                            <input type="password" name="new_password" class="input input-bordered w-full">
                        </div>

                        <div>
                            <label class="label">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" class="input input-bordered w-full">
                        </div>

                        <button class="btn btn-error">Update Password</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection