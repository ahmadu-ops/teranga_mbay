@extends('layouts.app')

@section('title', 'Variétés')
@section('header', 'Gestion des Variétés')
@section('subheader', 'Liste des variétés de produits cultivés')

@section('breadcrumb')
    <li class="text-sillon-500">Variétés</li>
@endsection

@section('content')
<div x-data="varietesManager()" x-cloak>
    
    <!-- Barre d'actions -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Filtres -->
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <!-- Recherche -->
                    <div class="relative flex-1 max-w-xs">
                        <form method="GET" action="{{ route('varietes.index') }}" id="filterForm">
                            <input 
                                type="text" 
                                name="recherche"
                                value="{{ request('recherche') }}"
                                placeholder="Rechercher une variété..."
                                class="form-input pl-10"
                            >
                            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-sillon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                    </div>

                    <!-- Filtre par produit -->
                    <div class="w-full sm:w-48">
                        <select 
                            name="produit_id" 
                            class="form-select"
                            onchange="document.getElementById('filterForm').submit()"
                        >
                            <option value="">Tous les produits</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id_produit }}" {{ request('produit_id') == $produit->id_produit ? 'selected' : '' }}>
                                    {{ $produit->nom_produit }}
                                </option>
                            @endforeach
                        </select>
                        </form>
                    </div>
                </div>

                <!-- Bouton Ajouter -->
                <button 
                    @click="openCreateModal()"
                    class="btn-primary"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle Variété
                </button>
            </div>
        </div>
    </div>

    <!-- Tableau des variétés -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-semibold text-sillon-500">
                🌱 Liste des Variétés 
                <span class="text-sm font-normal text-sillon-400">({{ $varietes->total() }} résultats)</span>
            </h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Variété</th>
                        <th>Produit</th>
                        <th class="text-right">Prix/kg</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Valeur</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($varietes as $variete)
                        <tr>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-mbay-100 flex items-center justify-center">
                                        <span class="text-xl">🌱</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sillon-600">{{ $variete->nom_variete }}</p>
                                        <p class="text-xs text-sillon-400">{{ Str::limit($variete->description, 30) ?: 'Aucune description' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-info">{{ $variete->produit->nom_produit }}</span>
                            </td>
                            <td class="text-right font-medium">
                                @if($variete->prixActuel)
                                    {{ number_format($variete->prixActuel->prix_kg, 0, ',', ' ') }} F
                                @else
                                    <span class="text-sillon-400">-</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @php $stock = $variete->stock_disponible; @endphp
                                @if($stock > 50)
                                    <span class="badge-success">{{ number_format($stock, 2, ',', ' ') }} kg</span>
                                @elseif($stock > 10)
                                    <span class="badge-warning">{{ number_format($stock, 2, ',', ' ') }} kg</span>
                                @elseif($stock > 0)
                                    <span class="badge-danger">{{ number_format($stock, 2, ',', ' ') }} kg</span>
                                @else
                                    <span class="text-sillon-400">0 kg</span>
                                @endif
                            </td>
                            <td class="text-right font-semibold text-mbay-600">
                                {{ number_format($variete->valeur_stock, 0, ',', ' ') }} F
                            </td>
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Voir -->
                                    <button 
                                        @click="openShowModal({{ $variete->id_variete }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Voir"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Modifier -->
                                    <button 
                                        @click="openEditModal({{ json_encode([
                                            'id' => $variete->id_variete,
                                            'nom_variete' => $variete->nom_variete,
                                            'description' => $variete->description,
                                            'id_produit' => $variete->id_produit,
                                        ]) }})"
                                        class="p-2 text-teranga-600 hover:bg-teranga-50 rounded-lg transition-colors"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Modifier Prix -->
                                    <button 
                                        @click="openPrixModal({{ $variete->id_variete }}, '{{ addslashes($variete->nom_variete) }}', {{ $variete->prixActuel?->prix_kg ?? 0 }})"
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                        title="Modifier le prix"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>

                                    <!-- Supprimer -->
                                    <button 
                                        @click="openDeleteModal({{ $variete->id_variete }}, '{{ addslashes($variete->nom_variete) }}')"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Supprimer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8">
                                <div class="flex flex-col items-center text-sillon-400">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p>Aucune variété trouvée</p>
                                    <button @click="openCreateModal()" class="mt-2 text-mbay-500 hover:text-mbay-600">
                                        Créer la première variété
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($varietes->hasPages())
            <div class="px-6 py-4 border-t border-mbay-100">
                {{ $varietes->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- ==================== MODALS ==================== -->

    <!-- Modal Create -->
    <div 
        x-show="showCreateModal" 
        x-transition.opacity
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="min-h-screen px-4 flex items-center justify-center">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-sillon-500/75" @click="closeCreateModal()"></div>

            <!-- Modal -->
            <div 
                x-show="showCreateModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-xl shadow-xl w-full max-w-lg z-10"
                @click.stop
            >
                <form action="{{ route('varietes.store') }}" method="POST">
                    @csrf
                    
                    <div class="px-6 py-4 border-b border-mbay-100 bg-mbay-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sillon-500">🌱 Nouvelle Variété</h3>
                            <button type="button" @click="closeCreateModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-4 space-y-4">
                        <!-- Produit -->
                        <div>
                            <label for="create_id_produit" class="form-label">Produit <span class="text-red-500">*</span></label>
                            <select id="create_id_produit" name="id_produit" class="form-select" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->id_produit }}">{{ $produit->nom_produit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nom variété -->
                        <div>
                            <label for="create_nom_variete" class="form-label">Nom de la Variété <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="create_nom_variete"
                                name="nom_variete" 
                                class="form-input"
                                placeholder="Ex: Chou vert, Carotte nantaise..."
                                required
                            >
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="create_description" class="form-label">Description</label>
                            <textarea 
                                id="create_description"
                                name="description" 
                                class="form-input"
                                rows="2"
                                placeholder="Description de la variété..."
                            ></textarea>
                        </div>

                        <!-- Prix initial -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="create_prix_kg" class="form-label">Prix initial (FCFA/kg)</label>
                                <input 
                                    type="number" 
                                    id="create_prix_kg"
                                    name="prix_kg" 
                                    class="form-input"
                                    placeholder="0"
                                    min="0"
                                    step="1"
                                >
                            </div>
                            <div>
                                <label for="create_date_debut" class="form-label">Date de début</label>
                                <input 
                                    type="date" 
                                    id="create_date_debut"
                                    name="date_debut" 
                                    class="form-input"
                                    value="{{ date('Y-m-d') }}"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closeCreateModal()" class="btn-outline">Annuler</button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div 
        x-show="showEditModal" 
        x-transition.opacity
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="fixed inset-0 bg-sillon-500/75" @click="closeEditModal()"></div>

            <div 
                x-show="showEditModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-xl shadow-xl w-full max-w-lg z-10"
                @click.stop
            >
                <form :action="editFormAction" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-6 py-4 border-b border-mbay-100 bg-teranga-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sillon-500">✏️ Modifier la Variété</h3>
                            <button type="button" @click="closeEditModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="edit_id_produit" class="form-label">Produit <span class="text-red-500">*</span></label>
                            <select id="edit_id_produit" name="id_produit" class="form-select" x-model="editData.id_produit" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->id_produit }}">{{ $produit->nom_produit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="edit_nom_variete" class="form-label">Nom de la Variété <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="edit_nom_variete"
                                name="nom_variete" 
                                class="form-input"
                                x-model="editData.nom_variete"
                                required
                            >
                        </div>

                        <div>
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea 
                                id="edit_description"
                                name="description" 
                                class="form-input"
                                rows="2"
                                x-model="editData.description"
                            ></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closeEditModal()" class="btn-outline">Annuler</button>
                        <button type="submit" class="btn-warning">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Prix -->
    <div 
        x-show="showPrixModal" 
        x-transition.opacity
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="fixed inset-0 bg-sillon-500/75" @click="closePrixModal()"></div>

            <div 
                x-show="showPrixModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-xl shadow-xl w-full max-w-md z-10"
                @click.stop
            >
                <form :action="prixFormAction" method="POST">
                    @csrf
                    
                    <div class="px-6 py-4 border-b border-mbay-100 bg-green-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sillon-500">💰 Modifier le Prix</h3>
                            <button type="button" @click="closePrixModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-4 space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-sillon-400">Variété</p>
                            <p class="text-lg font-semibold text-sillon-600" x-text="prixNomVariete"></p>
                            <p class="text-sm text-sillon-400 mt-1">Prix actuel: <span class="font-medium" x-text="prixActuel + ' FCFA/kg'"></span></p>
                        </div>

                        <div>
                            <label for="prix_kg" class="form-label">Nouveau Prix (FCFA/kg) <span class="text-red-500">*</span></label>
                            <input 
                                type="number" 
                                id="prix_kg"
                                name="prix_kg" 
                                class="form-input text-2xl text-center font-bold"
                                x-model="nouveauPrix"
                                min="1"
                                step="1"
                                required
                            >
                        </div>

                        <div>
                            <label for="date_debut" class="form-label">Date d'entrée en vigueur <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                id="date_debut"
                                name="date_debut" 
                                class="form-input"
                                value="{{ date('Y-m-d') }}"
                                required
                            >
                        </div>

                        <input type="hidden" name="id_variete" :value="prixVarieteId">
                    </div>

                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closePrixModal()" class="btn-outline">Annuler</button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Appliquer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Show -->
    <div 
        x-show="showShowModal" 
        x-transition.opacity
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="min-h-screen px-4 py-8 flex items-center justify-center">
            <div class="fixed inset-0 bg-sillon-500/75" @click="closeShowModal()"></div>

            <div 
                x-show="showShowModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl z-10 max-h-[85vh] overflow-y-auto"
                @click.stop
            >
                <div class="px-6 py-4 border-b border-mbay-100 bg-blue-50 rounded-t-xl sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-sillon-500">👁️ Détails de la Variété</h3>
                        <button type="button" @click="closeShowModal()" class="text-sillon-400 hover:text-sillon-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Loading -->
                    <div x-show="showLoading" class="flex items-center justify-center py-12">
                        <svg class="animate-spin h-10 w-10 text-mbay-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div x-show="!showLoading">
                        <!-- Header -->
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-16 h-16 rounded-xl bg-mbay-100 flex items-center justify-center">
                                <span class="text-3xl">🌱</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-sillon-600" x-text="showData.variete?.nom_variete"></h4>
                                <p class="text-sm text-sillon-400">
                                    <span class="badge-info" x-text="showData.produit?.nom_produit"></span>
                                </p>
                                <p class="text-sm text-sillon-400 mt-1" x-text="showData.variete?.description || 'Aucune description'"></p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-green-600" x-text="showData.prix_actuel ? showData.prix_actuel.toLocaleString('fr-FR') : '0'"></p>
                                <p class="text-xs text-sillon-400">Prix (F/kg)</p>
                            </div>
                            <div class="bg-mbay-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-mbay-600" x-text="(showData.total_recolte || 0).toLocaleString('fr-FR')"></p>
                                <p class="text-xs text-sillon-400">Récolté (kg)</p>
                            </div>
                            <div class="bg-teranga-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-teranga-600" x-text="(showData.total_vendu || 0).toLocaleString('fr-FR')"></p>
                                <p class="text-xs text-sillon-400">Vendu (kg)</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-blue-600" x-text="(showData.stock_disponible || 0).toLocaleString('fr-FR')"></p>
                                <p class="text-xs text-sillon-400">Stock (kg)</p>
                            </div>
                        </div>

                        <!-- Stats secondaires -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-red-50 rounded-lg p-4 text-center">
                                <p class="text-xl font-bold text-red-600" x-text="(showData.total_perdu || 0).toLocaleString('fr-FR')"></p>
                                <p class="text-xs text-sillon-400">Perdu (kg)</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4 text-center">
                                <p class="text-xl font-bold text-purple-600" x-text="(showData.valeur_stock || 0).toLocaleString('fr-FR') + ' F'"></p>
                                <p class="text-xs text-sillon-400">Valeur Stock</p>
                            </div>
                            <div class="bg-sillon-50 rounded-lg p-4 text-center">
                                <p class="text-xl font-bold text-sillon-600" x-text="(showData.chiffre_affaires || 0).toLocaleString('fr-FR') + ' F'"></p>
                                <p class="text-xs text-sillon-400">Chiffre d'Affaires</p>
                            </div>
                        </div>

                        <!-- Historique des prix -->
                        <div>
                            <h5 class="font-semibold text-sillon-500 mb-3">📈 Historique des Prix</h5>
                            <template x-if="showData.historique_prix && showData.historique_prix.length > 0">
                                <div class="space-y-2">
                                    <template x-for="(prix, index) in showData.historique_prix" :key="index">
                                        <div class="flex items-center justify-between p-3 rounded-lg" :class="prix.date_fin === null ? 'bg-green-50 border border-green-200' : 'bg-gray-50'">
                                            <div class="flex items-center space-x-2">
                                                <span x-show="prix.date_fin === null" class="badge-success">Actuel</span>
                                                <span class="font-semibold text-sillon-600" x-text="prix.prix_kg?.toLocaleString('fr-FR') + ' FCFA/kg'"></span>
                                            </div>
                                            <div class="text-sm text-sillon-400">
                                                <span x-text="prix.date_debut"></span>
                                                <span x-show="prix.date_fin"> → <span x-text="prix.date_fin"></span></span>
                                                <span x-show="!prix.date_fin"> → En cours</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!showData.historique_prix || showData.historique_prix.length === 0">
                                <p class="text-sillon-400 text-center py-4">Aucun historique de prix</p>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3 sticky bottom-0">
                    <button type="button" @click="closeShowModal()" class="btn-outline">Fermer</button>
                    <button 
                        type="button" 
                        @click="closeShowModal(); openPrixModal(showData.variete?.id_variete, showData.variete?.nom_variete, showData.prix_actuel || 0)"
                        class="btn-primary"
                    >
                        💰 Modifier le Prix
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div 
        x-show="showDeleteModal" 
        x-transition.opacity
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="fixed inset-0 bg-sillon-500/75" @click="closeDeleteModal()"></div>

            <div 
                x-show="showDeleteModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-xl shadow-xl w-full max-w-md z-10"
                @click.stop
            >
                <form :action="deleteFormAction" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="px-6 py-4 border-b border-red-100 bg-red-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-red-700">⚠️ Confirmer la Suppression</h3>
                            <button type="button" @click="closeDeleteModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-center text-sillon-600">
                            Êtes-vous sûr de vouloir supprimer la variété 
                            <strong class="text-red-600" x-text="deleteNomVariete"></strong> ?
                        </p>
                        <p class="text-center text-sm text-sillon-400 mt-2">
                            ⚠️ Toutes les récoltes, ventes et pertes associées seront supprimées.
                        </p>
                    </div>

                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closeDeleteModal()" class="btn-outline">Annuler</button>
                        <button type="submit" class="btn-danger">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function varietesManager() {
    return {
        showCreateModal: false,
        showEditModal: false,
        editData: { id: null, nom_variete: '', description: '', id_produit: '' },
        editFormAction: '',
        showPrixModal: false,
        prixVarieteId: null,
        prixNomVariete: '',
        prixActuel: 0,
        nouveauPrix: 0,
        prixFormAction: '',
        showShowModal: false,
        showLoading: false,
        showData: {},
        showDeleteModal: false,
        deleteNomVariete: '',
        deleteFormAction: '',

        openCreateModal() { this.showCreateModal = true; },
        closeCreateModal() { this.showCreateModal = false; },

        openEditModal(data) {
            this.editData = { ...data };
            this.editFormAction = `/varietes/${data.id}`;
            this.showEditModal = true;
        },
        closeEditModal() {
            this.showEditModal = false;
            this.editData = { id: null, nom_variete: '', description: '', id_produit: '' };
        },

        openPrixModal(id, nom, prixActuel) {
            this.prixVarieteId = id;
            this.prixNomVariete = nom;
            this.prixActuel = prixActuel;
            this.nouveauPrix = prixActuel;
            this.prixFormAction = `/varietes/${id}/prix`;
            this.showPrixModal = true;
        },
        closePrixModal() { this.showPrixModal = false; },

        async openShowModal(id) {
            this.showShowModal = true;
            this.showLoading = true;
            this.showData = {};

            try {
                const response = await fetch(`/varietes/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (response.ok) {
                    this.showData = await response.json();
                }
            } catch (error) {
                console.error('Erreur:', error);
            } finally {
                this.showLoading = false;
            }
        },
        closeShowModal() {
            this.showShowModal = false;
            this.showData = {};
        },

        openDeleteModal(id, nom) {
            this.deleteNomVariete = nom;
            this.deleteFormAction = `/varietes/${id}`;
            this.showDeleteModal = true;
        },
        closeDeleteModal() { this.showDeleteModal = false; }
    }
}
</script>
@endpush