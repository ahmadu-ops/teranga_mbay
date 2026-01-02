<header class="h-16 bg-white border-b border-mbay-100 flex items-center justify-between px-6 sticky top-0 z-30">
    <!-- Left: Breadcrumb -->
    <div class="flex items-center space-x-4">
        <nav class="text-sm">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-mbay-500 hover:text-mbay-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </a>
                </li>
                @hasSection('breadcrumb')
                    <li class="text-sillon-300">/</li>
                    @yield('breadcrumb')
                @endif
            </ol>
        </nav>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center space-x-4">
        <!-- Date du jour -->
        <div class="hidden md:flex items-center space-x-2 text-sm text-sillon-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>{{ now()->translatedFormat('l d F Y') }}</span>
        </div>

        <!-- Quick Actions -->
        <div x-data="{ open: false }" class="relative">
            <button 
                @click="open = !open"
                class="btn-primary flex items-center space-x-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Nouveau</span>
            </button>

            <!-- Dropdown -->
            <div 
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-mbay-100 py-2 z-50"
            >
                <a href="{{ route('recoltes.create') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-sillon-500 hover:bg-mbay-50">
                    <span class="text-lg">🌾</span>
                    <span>Nouvelle Récolte</span>
                </a>
                <a href="{{ route('ventes.create') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-sillon-500 hover:bg-mbay-50">
                    <span class="text-lg">💰</span>
                    <span>Nouvelle Vente</span>
                </a>
                <a href="{{ route('pertes.create') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-sillon-500 hover:bg-mbay-50">
                    <span class="text-lg">📉</span>
                    <span>Nouvelle Perte</span>
                </a>
                <hr class="my-2 border-mbay-100">
                <a href="{{ route('produits.create') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-sillon-500 hover:bg-mbay-50">
                    <span class="text-lg">📦</span>
                    <span>Nouveau Produit</span>
                </a>
                <a href="{{ route('varietes.create') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-sillon-500 hover:bg-mbay-50">
                    <span class="text-lg">🌱</span>
                    <span>Nouvelle Variété</span>
                </a>
            </div>
        </div>
    </div>
</header>