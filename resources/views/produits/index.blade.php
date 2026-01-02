@extends('layouts.app')

@section('title', 'Produits')
@section('header', 'Gestion des Produits')
@section('subheader', 'Liste des produits cultivés sur l\'exploitation')

@section('breadcrumb')
    <li class="text-sillon-500">Produits</li>
@endsection

@section('content')
<div x-data="produitsManager()" x-cloak>
    
    <!-- Barre d'actions -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Recherche -->
                <div class="relative flex-1 max-w-md">
                    <form method="GET" action="{{ route('produits.index') }}">
                        <input 
                            type="text" 
                            name="recherche"
                            value="{{ request('recherche') }}"
                            placeholder="Rechercher un produit..."
                            class="form-input pl-10"
                        >
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-sillon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </form>
                </div>

                <!-- Bouton Ajouter -->
                <button 
                    @click="openCreateModal()"
                    class="btn-primary"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau Produit
                </button>
            </div>
        </div>
    </div>

    <!-- Tableau des produits -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-semibold text-sillon-500">
                📦 Liste des Produits 
                <span class="text-sm font-normal text-sillon-400">({{ $produits->total() }} résultats)</span>
            </h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du Produit</th>
                        <th class="text-center">Variétés</th>
                        <th>Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produits as $produit)
                        <tr>
                            <td class="font-mono text-sm text-sillon-400">{{ $produit->id_produit }}</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-mbay-100 flex items-center justify-center">
                                        <span class="text-xl">📦</span>
                                    </div>
                                    <span class="font-medium text-sillon-600">{{ $produit->nom_produit }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-success">{{ $produit->varietes->count() }}</span>
                            </td>
                            <td class="text-sm text-sillon-400">
                                {{ $produit->created_at ? $produit->created_at->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Voir -->
                                    <button 
                                        @click="openShowModal({{ $produit->id_produit }})"
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
                                        @click="openEditModal({{ $produit->id_produit }}, '{{ addslashes($produit->nom_produit) }}')"
                                        class="p-2 text-teranga-600 hover:bg-teranga-50 rounded-lg transition-colors"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Supprimer -->
                                    <button 
                                        @click="openDeleteModal({{ $produit->id_produit }}, '{{ addslashes($produit->nom_produit) }}')"
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
                            <td colspan="5" class="text-center py-8">
                                <div class="flex flex-col items-center text-sillon-400">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p>Aucun produit trouvé</p>
                                    <button @click="openCreateModal()" class="mt-2 text-mbay-500 hover:text-mbay-600">
                                        Créer le premier produit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($produits->hasPages())
            <div class="px-6 py-4 border-t border-mbay-100">
                {{ $produits->links() }}
            </div>
        @endif
    </div>

    <!-- ==================== MODALS ==================== -->

    <!-- Modal Create -->
    <div 
        x-show="showCreateModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div 
                class="fixed inset-0 bg-sillon-500 bg-opacity-75 transition-opacity"
                @click="closeCreateModal()"
            ></div>

            <!-- Modal Content -->
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-auto z-50"
            >
                <form action="{{ route('produits.store') }}" method="POST">
                    @csrf
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-mbay-100 bg-mbay-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sillon-500">
                                📦 Nouveau Produit
                            </h3>
                            <button type="button" @click="closeCreateModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <label for="create_nom_produit" class="form-label">Nom du Produit <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="create_nom_produit"
                                name="nom_produit" 
                                class="form-input"
                                placeholder="Ex: Chou, Carotte, Tomate..."
                                required
                                x-ref="createInput"
                            >
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closeCreateModal()" class="btn-outline">
                            Annuler
                        </button>
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
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div 
                class="fixed inset-0 bg-sillon-500 bg-opacity-75 transition-opacity"
                @click="closeEditModal()"
            ></div>

            <!-- Modal Content -->
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-auto z-50"
            >
                <form :action="editFormAction" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-mbay-100 bg-teranga-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sillon-500">
                                ✏️ Modifier le Produit
                            </h3>
                            <button type="button" @click="closeEditModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <label for="edit_nom_produit" class="form-label">Nom du Produit <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="edit_nom_produit"
                                name="nom_produit" 
                                class="form-input"
                                x-model="editNomProduit"
                                required
                            >
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closeEditModal()" class="btn-outline">
                            Annuler
                        </button>
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

    <!-- Modal Show -->
    <div 
        x-show="showShowModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div 
                class="fixed inset-0 bg-sillon-500 bg-opacity-75 transition-opacity"
                @click="closeShowModal()"
            ></div>

            <!-- Modal Content -->
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full mx-auto z-50"
            >
                <!-- Header -->
                <div class="px-6 py-4 border-b border-mbay-100 bg-blue-50 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-sillon-500">
                            👁️ Détails du Produit
                        </h3>
                        <button type="button" @click="closeShowModal()" class="text-sillon-400 hover:text-sillon-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="px-6 py-4">
                    <!-- Loading -->
                    <div x-show="showLoading" class="flex items-center justify-center py-8">
                        <svg class="animate-spin h-8 w-8 text-mbay-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div x-show="!showLoading">
                        <!-- Infos principales -->
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-16 h-16 rounded-xl bg-mbay-100 flex items-center justify-center">
                                <span class="text-3xl">📦</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-sillon-600" x-text="showData.nom_produit"></h4>
                                <p class="text-sm text-sillon-400">ID: <span x-text="showData.id_produit"></span></p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                            <div class="bg-mbay-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-mbay-600" x-text="showData.stats?.nombre_varietes || 0"></p>
                                <p class="text-xs text-sillon-400">Variétés</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-green-600" x-text="(showData.stats?.total_recolte || 0).toLocaleString('fr-FR')"></p>
                                <p class="text-xs text-sillon-400">Récolté (kg)</p>
                            </div>
                            <div class="bg-teranga-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-teranga-600" x-text="(showData.stats?.total_vendu || 0).toLocaleString('fr-FR')"></p>
                                <p class="text-xs text-sillon-400">Vendu (kg)</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-blue-600" x-text="(showData.stats?.stock_total || 0).toLocaleString('fr-FR')"></p>
                                <p class="text-xs text-sillon-400">Stock (kg)</p>
                            </div>
                        </div>

                        <!-- Variétés -->
                        <div>
                            <h5 class="font-semibold text-sillon-500 mb-3">🌱 Variétés</h5>
                            <template x-if="showData.varietes && showData.varietes.length > 0">
                                <div class="space-y-2">
                                    <template x-for="variete in showData.varietes" :key="variete.id_variete">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="font-medium text-sillon-600" x-text="variete.nom_variete"></p>
                                                <p class="text-xs text-sillon-400" x-text="variete.description || 'Aucune description'"></p>
                                            </div>
                                            <span class="badge-success" x-text="(variete.prix_actuel?.prix_kg || 0) + ' F/kg'"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!showData.varietes || showData.varietes.length === 0">
                                <p class="text-sillon-400 text-center py-4">Aucune variété enregistrée</p>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                    <button type="button" @click="closeShowModal()" class="btn-outline">
                        Fermer
                    </button>
                    <button 
                        type="button" 
                        @click="closeShowModal(); openEditModal(showData.id_produit, showData.nom_produit)"
                        class="btn-warning"
                    >
                        ✏️ Modifier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div 
        x-show="showDeleteModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div 
                class="fixed inset-0 bg-sillon-500 bg-opacity-75 transition-opacity"
                @click="closeDeleteModal()"
            ></div>

            <!-- Modal Content -->
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-auto z-50"
            >
                <form :action="deleteFormAction" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-red-100 bg-red-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-red-700">
                                ⚠️ Confirmer la Suppression
                            </h3>
                            <button type="button" @click="closeDeleteModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-center text-sillon-600">
                            Êtes-vous sûr de vouloir supprimer le produit 
                            <strong class="text-red-600" x-text="deleteNomProduit"></strong> ?
                        </p>
                        <p class="text-center text-sm text-sillon-400 mt-2">
                            ⚠️ Cette action supprimera également toutes les variétés, récoltes, ventes et pertes associées.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closeDeleteModal()" class="btn-outline">
                            Annuler
                        </button>
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
function produitsManager() {
    return {
        // Create Modal
        showCreateModal: false,
        
        // Edit Modal
        showEditModal: false,
        editId: null,
        editNomProduit: '',
        editFormAction: '',
        
        // Show Modal
        showShowModal: false,
        showLoading: false,
        showData: {},
        
        // Delete Modal
        showDeleteModal: false,
        deleteId: null,
        deleteNomProduit: '',
        deleteFormAction: '',

        // Create
        openCreateModal() {
            this.showCreateModal = true;
            this.$nextTick(() => {
                this.$refs.createInput?.focus();
            });
        },
        closeCreateModal() {
            this.showCreateModal = false;
        },

        // Edit
        openEditModal(id, nom) {
            this.editId = id;
            this.editNomProduit = nom;
            this.editFormAction = `/produits/${id}`;
            this.showEditModal = true;
        },
        closeEditModal() {
            this.showEditModal = false;
            this.editId = null;
            this.editNomProduit = '';
        },

        // Show
        async openShowModal(id) {
            this.showShowModal = true;
            this.showLoading = true;
            this.showData = {};

            try {
                const response = await fetch(`/produits/${id}`, {
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

        // Delete
        openDeleteModal(id, nom) {
            this.deleteId = id;
            this.deleteNomProduit = nom;
            this.deleteFormAction = `/produits/${id}`;
            this.showDeleteModal = true;
        },
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deleteId = null;
            this.deleteNomProduit = '';
        }
    }
}
</script>
@endpush