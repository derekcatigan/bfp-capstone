{{-- resources\views\pages\admin\account-list.blade.php --}}
@extends('layouts.layout')

@section('content')

    <div class="p-3">
        <h1 class="text-2xl font-bold">Manage Accounts</h1>
    </div>

    <div class="flex justify-between mb-3 p-3">
        <form id="searchForm" method="GET" class="w-full flex justify-between gap-1 mb-3">
            <input type="text" name="search" id="searchInput" placeholder="Search by name or email"
                value="{{ request('search') }}" class="input w-full" />

            <select name="position" id="positionFilter" class="select select-bordered w-full">
                <option value="">All Positions</option>
                <option value="Administrator" {{ request('position') == 'Administrator' ? 'selected' : '' }}>Administrator
                </option>
                <option value="Driver" {{ request('position') == 'Driver' ? 'selected' : '' }}>Driver</option>
                <option value="User" {{ request('position') == 'User' ? 'selected' : '' }}>User</option>
            </select>

            <button type="submit" class="btn btn-primary ml-2">Filter</button>

            <!-- Reset Button -->
            <a href="{{ route('account.index') }}" class="btn btn-warning">Reset</a>
        </form>
    </div>


    <div class="m-3 p-3 h-190 rounded shadow-xl border border-gray-300">
        <div class="overflow-x-auto border border-gray-300 shadow rounded h-160 mb-3">
            <table class="table">
                <!-- Head -->
                <thead class="text-white bg-gray-700">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->profile->first_name }} {{ $user->profile->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->profile->badgeClass() }}">
                                    {{ $user->profile->position }}
                                </span>
                            </td>

                            <td>{{ $user->profile->department }}</td>
                            <td>{{ $user->created_at->format('F j, Y g:i A') }}</td>
                            <td>
                                <button class="btn btn-sm btn-soft btn-primary view-account" data-id="{{ $user->id }}">
                                    View
                                </button>
                                <button class="btn btn-sm btn-soft btn-error delete-btn"
                                    data-id="{{ $user->id }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>

    {{-- View Modal --}}
    <dialog id="accountModal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">

            <h3 class="font-bold text-lg mb-4">Account Details</h3>

            <form id="accountForm">
                <input type="hidden" id="user_id">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="label">First Name</label>
                        <input type="text" id="first_name" class="input input-bordered w-full" disabled>
                    </div>

                    <div>
                        <label class="label">Last Name</label>
                        <input type="text" id="last_name" class="input input-bordered w-full" disabled>
                    </div>

                    <div>
                        <label class="label">Email</label>
                        <input type="email" id="email" class="input input-bordered w-full" disabled>
                    </div>

                    <div>
                        <label class="label">Phone</label>
                        <input type="text" id="phone" class="input input-bordered w-full" disabled>
                    </div>

                    <div>
                        <label class="label">Department</label>
                        <select id="department" class="select select-bordered w-full" disabled>
                            <option value="">Select Department</option>
                            <option value="Fire Operations">Fire Operations</option>
                            <option value="Admin Office">Admin Office</option>
                            <option value="Rescue Unit">Rescue Unit</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div>
                        <label class="label">Role</label>
                        <select id="role" class="select select-bordered w-full" disabled>
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="driver">Driver</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                </div>

                <div class="modal-action">

                    <button type="button" id="editBtn" class="btn btn-warning">Edit</button>
                    <button type="submit" id="saveBtn" class="btn btn-success hidden">Save</button>
                    <button type="button" id="cancelBtn" class="btn hidden">Cancel</button>

                    <form method="dialog">
                        <button class="btn">Close</button>
                    </form>

                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <input type="checkbox" id="deleteModal" class="modal-toggle" />
    <div class="modal text-black">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Deletion</h3>
            <p class="py-4">Are you sure you want to delete this user?</p>
            <div class="modal-action">
                <label for="deleteModal" class="btn">Cancel</label>
                <button id="confirmDeleteBtn" class="btn btn-error">Delete</button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/account/listAccount.js') }}"></script>
@endsection