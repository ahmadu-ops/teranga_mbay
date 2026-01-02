<aside 
    class="fixed left-0 top-0 h-full bg-white border-r border-mbay-100 shadow-sm z-40 transition-all duration-300"
    :class="sidebarOpen ? 'w-64' : 'w-20'"
>
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-mbay-100 bg-mbay-500">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <span class="text-3xl">🌾</span>
            <span 
                class="text-xl font-bold text-white transition-opacity duration-300"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
            >
                Teranga Mbay
            </span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2">
        <!-- Dashboard -->
        <a 
            href="{{ route('dashboard') }}" 
            class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span :class="sidebarOpen ? '' : 'hidden'">Dashboard</span>
        </a>

        <!-- Separator -->
        <div class="pt-4 pb-2" :class="sidebarOpen ? '' : 'hidden'">
            <p class="text-xs font-semibold text-sillon-300 uppercase tracking-wider px-4">Catalogue</p>
        </div>

        <!-- Produits -->
        <a 
            href="{{ route('produits.index') }}" 
            class="sidebar-link {{ request()->routeIs('produits.*') ? 'active' : '' }}"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span :class="sidebarOpen ? '' : 'hidden'">Produits</span>
        </a>

        <!-- Variétés -->
        <a 
            href="{{ route('varietes.index') }}" 
            class="sidebar-link {{ request()->routeIs('varietes.*') ? 'active' : '' }}"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span :class="sidebarOpen ? '' : 'hidden'">Variétés</span>
        </a>

        <!-- Separator -->
        <div class="pt-4 pb-2" :class="sidebarOpen ? '' : 'hidden'">
            <p class="text-xs font-semibold text-sillon-300 uppercase tracking-wider px-4">Opérations</p>
        </div>

        <!-- Récoltes -->
        <a 
            href="{{ route('recoltes.index') }}" 
            class="sidebar-link {{ request()->routeIs('recoltes.*') ? 'active' : '' }}"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
            <span :class="sidebarOpen ? '' : 'hidden'">Récoltes</span>
        </a>

        <!-- Ventes -->
        <a 
            href="{{ route('ventes.index') }}" 
            class="sidebar-link {{ request()->routeIs('ventes.*') ? 'active' : '' }}"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span :class="sidebarOpen ? '' : 'hidden'">Ventes</span>
        </a>

        <!-- Pertes -->
        <a 
            href="{{ route('pertes.index') }}" 
            class="sidebar-link {{ request()->routeIs('pertes.*') ? 'active' : '' }}"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span :class="sidebarOpen ? '' : 'hidden'">Pertes</span>
        </a>
    </nav>

    <!-- Toggle Button -->
    <div class="absolute bottom-4 left-0 right-0 px-4">
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="w-full flex items-center justify-center py-2 rounded-lg bg-mbay-50 text-mbay-600 hover:bg-mbay-100 transition-colors"
        >
            <svg 
                class="w-6 h-6 transition-transform duration-300" 
                :class="sidebarOpen ? '' : 'rotate-180'"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>
</aside>