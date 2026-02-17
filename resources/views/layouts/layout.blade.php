{{-- resources\views\layouts\layout.blade.php --}}
@php
    $unreadCount = auth()->check()
        ? auth()->user()->inbox()->unread()->count()
        : 0;

    $notifications = auth()->check()
        ? auth()->user()->inbox()->latest()->take(10)->get()
        : collect();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bureau of Fire Protection | Maasin')</title>
    <link rel="shortcut icon" href="{{ asset('assets/icons/BFPIcon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Font - Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    {{-- Boxicons --}}
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @yield('head')
</head>

<body>

    <div class="drawer lg:drawer-open">
        <input id="sidebar-toggle" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar Section -->
            <div class="navbar border-b border-blue-500 w-full">
                <div class="flex-none lg:hidden">
                    <label for="sidebar-toggle" aria-label="open sidebar" class="btn btn-square btn-ghost">
                        <svg class="h-[1.5em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none"
                            stroke="currentColor" class="my-1.5 inline-block size-4">
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                            </path>
                            <path d="M9 4v16"></path>
                            <path d="M14 10l2 2l-2 2"></path>
                        </svg>
                    </label>
                </div>
                <div class="mx-2 flex-1 px-2">
                    <img src="{{ asset('assets/logos/BFPLOGO.png') }}" alt="BFP Maasin Logo"
                        class="w-16 h-auto object-cover">
                </div>
                <div class="flex-none lg:block">
                    <ul class="menu menu-horizontal items-center gap-1">
                        <!-- Navbar menu content here -->
                        <li>
                            {{-- Notification --}}
                            @include('includes.notification')
                        </li>
                        <li>
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button">
                                    {{-- Profile Avatar --}}
                                    <div class="avatar">
                                        <div class="w-10 rounded">
                                            <img src="https://img.daisyui.com/images/profile/demo/batperson@192.webp" />
                                        </div>
                                    </div>
                                </div>
                                <ul tabindex="-1"
                                    class="dropdown-content menu space-y-1 bg-white border border-gray-300 text-black rounded z-1 w-52 p-2 shadow-sm">
                                    <li>
                                        <a href="{{ route('profile.index') }}">
                                            <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                                <path
                                                    d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5">
                                                </path>
                                            </svg>
                                            Profile
                                        </a>
                                    </li>
                                    <li class="block lg:hidden">
                                        <a href="{{ route('notification.index') }}">
                                            <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                                <path
                                                    d="M19 12.59V10c0-3.22-2.18-5.93-5.14-6.74C13.57 2.52 12.85 2 12 2s-1.56.52-1.86 1.26C7.18 4.08 5 6.79 5 10v2.59L3.29 14.3a1 1 0 0 0-.29.71v2c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-2c0-.27-.11-.52-.29-.71zM19 16H5v-.59l1.71-1.71a1 1 0 0 0 .29-.71v-3c0-2.76 2.24-5 5-5s5 2.24 5 5v3c0 .27.11.52.29.71L19 15.41zm-4.18 4H9.18c.41 1.17 1.51 2 2.82 2s2.41-.83 2.82-2">
                                                </path>
                                            </svg>
                                            Notification

                                            {{-- Notification Indicator --}}
                                            <span class="indicator-item badge badge-sm badge-primary">
                                                {{ $unreadCount }}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                                <path
                                                    d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4m0 6c-1.08 0-2-.92-2-2s.92-2 2-2 2 .92 2 2-.92 2-2 2">
                                                </path>
                                                <path
                                                    d="m20.42 13.4-.51-.29c.05-.37.08-.74.08-1.11s-.03-.74-.08-1.11l.51-.29c.96-.55 1.28-1.78.73-2.73l-1-1.73a2.006 2.006 0 0 0-2.73-.73l-.53.31c-.58-.46-1.22-.83-1.9-1.11v-.6c0-1.1-.9-2-2-2h-2c-1.1 0-2 .9-2 2v.6c-.67.28-1.31.66-1.9 1.11l-.53-.31c-.96-.55-2.18-.22-2.73.73l-1 1.73c-.55.96-.22 2.18.73 2.73l.51.29c-.05.37-.08.74-.08 1.11s.03.74.08 1.11l-.51.29c-.96.55-1.28 1.78-.73 2.73l1 1.73c.55.95 1.77 1.28 2.73.73l.53-.31c.58.46 1.22.83 1.9 1.11v.6c0 1.1.9 2 2 2h2c1.1 0 2-.9 2-2v-.6a8.7 8.7 0 0 0 1.9-1.11l.53.31c.95.55 2.18.22 2.73-.73l1-1.73c.55-.96.22-2.18-.73-2.73m-2.59-2.78c.11.45.17.92.17 1.38s-.06.92-.17 1.38a1 1 0 0 0 .47 1.11l1.12.65-1 1.73-1.14-.66c-.38-.22-.87-.16-1.19.14-.68.65-1.51 1.13-2.38 1.4-.42.13-.71.52-.71.96v1.3h-2v-1.3c0-.44-.29-.83-.71-.96-.88-.27-1.7-.75-2.38-1.4a1.01 1.01 0 0 0-1.19-.15l-1.14.66-1-1.73 1.12-.65c.39-.22.58-.68.47-1.11-.11-.45-.17-.92-.17-1.38s.06-.93.17-1.38A1 1 0 0 0 5.7 9.5l-1.12-.65 1-1.73 1.14.66c.38.22.87.16 1.19-.14.68-.65 1.51-1.13 2.38-1.4.42-.13.71-.52.71-.96v-1.3h2v1.3c0 .44.29.83.71.96.88.27 1.7.75 2.38 1.4.32.31.81.36 1.19.14l1.14-.66 1 1.73-1.12.65c-.39.22-.58.68-.47 1.11Z">
                                                </path>
                                            </svg>
                                            Settings
                                        </a>
                                    </li>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-block btn-error">Logout</button>
                                    </form>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Main Content --}}
            <main class="min-h-screen">
                @yield('content')
            </main>

        </div>

        {{-- Sidebar Section --}}
        <div class="drawer-side">
            <label for="sidebar-toggle" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu space-y-2 bg-white border-r border-gray-300 min-h-full w-80 p-4">
                <!-- Sidebar content here -->

                {{-- Sidebar links for admin --}}
                @include('includes.sidebar-admin')

                {{-- Sidebar links for user & driver --}}
                @include('includes.sidebar-user')
            </ul>
        </div>
    </div>


    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- jQuery --}}
    <script src="{{ asset('assets/js/jquery-4.0.0.min.js') }}"></script>
    @stack('scripts')
</body>

</html>