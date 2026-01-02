<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Teranga Mbay</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌾</text></svg>">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: true }" class="min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
            <!-- Navbar -->
            @include('components.navbar')

            <!-- Page Content -->
            <main class="flex-1 p-6">
                <!-- Alerts -->
                @include('components.alert')

                <!-- Page Header -->
                @hasSection('header')
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-sillon-500">@yield('header')</h1>
                        @hasSection('subheader')
                            <p class="text-sillon-400 mt-1">@yield('subheader')</p>
                        @endif
                    </div>
                @endif

                <!-- Content -->
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-mbay-100 px-6 py-4">
                <p class="text-sm text-sillon-400 text-center">
                    © {{ date('Y') }} <span class="font-semibold text-mbay-500">Teranga Mbay</span> - Gestion Agricole
                </p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>