@extends('layouts.app')

@section('title', 'Récoltes')
@section('header', 'Gestion des Récoltes')
@section('subheader', 'Suivi des récoltes de l\'exploitation')

@section('breadcrumb')
    <li class="text-sillon-500">Récoltes</li>
@endsection

@section('content')
<div x-data="recoltesManager()" x-cloak>
    
    <!-- Stats rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="card p-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-lg bg-mbay-100 flex items-center justify-center">
                    <span class="text-2xl">🌾</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-mbay-600">{{ number_format($stats['total_kg'], 2, ',', ' ') }}</p>
                    <p class="text-sm text-sillon-400">Total récolté (kg)</p>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-lg bg-teranga-100 flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-teranga-600">{{ $stats['nombre_recoltes'] }}</p>
                    <p class="text-sm text-sillon-400">Nombre de récoltes</p>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="text-2xl">⚖️</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['moyenne_kg'] ?? 0, 2, ',', ' ') }}</p>
                    <p class="text-sm text-sillon-400">Moyenne par récolte (kg)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre d'actions -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('recoltes.index') }}" id="filterForm" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Filtres -->
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <!-- Filtre par variété -->
                    <div class="w-full sm:w-56">
                        <select 
                            name="variete_id" 
                            class="form-select"
                            onchange="document.getElementById('filterForm').submit()"
                        >
                            <option value="">Toutes les variétés</option>
                            @foreach($varietes as $variete)
                                <option value="{{ $variete->id_variete }}" {{ request('variete_id') == $variete->id_variete ? 'selected' : '' }}>
                                    {{ $variete->produit->nom_produit }} - {{ $variete->nom_variete }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date début -->
                    <div class="w-full sm:w-40">
                        <input 
                            type="date" 
                            name="date_debut"
                            value="{{ request('date_debut') }}"
                            class="form-input"
                            placeholder="Date début"
                            onchange="document.getElementById('filterForm').submit()"
                        >
                    </div>

                    <!-- Date fin -->
                    <div class="w-full sm:w-40">
                        <input 
                            type="date" 
                            name="date_fin"
                            value="{{ request('date_fin') }}"
                            class="form-input"
                            placeholder="Date fin"
                            onchange="document.getElementById('filterForm').submit()"
                        >
                    </div>

                    @if(request()->hasAny(['variete_id', 'date_debut', 'date_fin']))
                        <a href="{{ route('recoltes.index') }}" class="btn-outline text-sm">
                            ✕ Réinitialiser
                        </a>
                    @endif
                </div>

                <!-- Bouton Ajouter -->
                <button 
                    type="button"
                    @click="openCreateModal()"
                    class="btn-primary"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle Récolte
                </button>
            </form>
        </div>
    </div>

    <!-- Tableau des récoltes -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-semibold text-sillon-500">
                🌾 Liste des Récoltes 
                <span class="text-sm font-normal text-sillon-400">({{ $recoltes->total() }} résultats)</span>
            </h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Variété</th>
                        <th class="text-right">Quantité</th>
                        <th class="text-right">Valeur estimée</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recoltes as $recolte)
                        <tr>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-mbay-100 flex items-center justify-center">
                                        <span class="text-lg">📅</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sillon-600">{{ $recolte->date_recolte->format('d/m/Y') }}</p>
                                        <p class="text-xs text-sillon-400">{{ $recolte->date_recolte->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-info">{{ $recolte->variete->produit->nom_produit }}</span>
                            </td>
                            <td class="font-medium">{{ $recolte->variete->nom_variete }}</td>
                            <td class="text-right">
                                <span class="text-lg font-semibold text-mbay-600">{{ number_format($recolte->quantite_recolte, 2, ',', ' ') }}</span>
                                <span class="text-sm text-sillon-400">kg</span>
                            </td>
                            <td class="text-right font-medium text-sillon-600">
                                {{ number_format($recolte->valeur_estimee, 0, ',', ' ') }} F
                            </td>
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Voir -->
                                    <button 
                                        @click="openShowModal({{ $recolte->id_recolte }})"
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
                                            'id' => $recolte->id_recolte,
                                            'date_recolte' => $recolte->date_recolte->format('Y-m-d'),
                                            'quantite_recolte' => $recolte->quantite_recolte,
                                            'id_variete' => $recolte->id_variete,
                                        ]) }})"
                                        class="p-2 text-teranga-600 hover:bg-teranga-50 rounded-lg transition-colors"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Supprimer -->
                                    <button 
                                        @click="openDeleteModal({{ $recolte->id_recolte }}, '{{ $recolte->variete->nom_variete }}', '{{ $recolte->date_recolte->format('d/m/Y') }}', {{ $recolte->quantite_recolte }})"
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
                                    <p>Aucune récolte trouvée</p>
                                    <button @click="openCreateModal()" class="mt-2 text-mbay-500 hover:text-mbay-600">
                                        Enregistrer la première récolte
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($recoltes->hasPages())
            <div class="px-6 py-4 border-t border-mbay-100">
                {{ $recoltes->withQueryString()->links() }}
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
            <div class="fixed inset-0 bg-sillon-500/75" @click="closeCreateModal()"></div>

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
                <form action="{{ route('recoltes.store') }}" method="POST">
                    @csrf
                    
                    <div class="px-6 py-4 border-b border-mbay-100 bg-mbay-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sillon-500">🌾 Nouvelle Récolte</h3>
                            <button type="button" @click="closeCreateModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-4 space-y-4">
                        <!-- Variété -->
                        <div>
                            <label for="create_id_variete" class="form-label">Variété <span class="text-red-500">*</span></label>
                            <select id="create_id_variete" name="id_variete" class="form-select" required>
                                <option value="">Sélectionner une variété</option>
                                @foreach($varietes as $variete)
                                    <option value="{{ $variete->id_variete }}">
                                        {{ $variete->produit->nom_produit }} - {{ $variete->nom_variete }}
                                        @if($variete->prixActuel)
                                            ({{ number_format($variete->prixActuel->prix_kg, 0, ',', ' ') }} F/kg)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="create_date_recolte" class="form-label">Date de récolte <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                id="create_date_recolte"
                                name="date_recolte" 
                                class="form-input"
                                value="{{ date('Y-m-d') }}"
                                required
                            >
                        </div>

                        <!-- Quantité -->
                        <div>
                            <label for="create_quantite_recolte" class="form-label">Quantité récoltée (kg) <span class="text-red-500">*</span></label>
                            <input 
                                type="number" 
                                id="create_quantite_recolte"
                                name="quantite_recolte" 
                                class="form-input text-xl font-bold text-center"
                                placeholder="0.00"
                                step="0.01"
                                min="0.01"
                                required
                            >
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" @click="closeCreateModal()" class="btn-outline">Annuler</button>
                        <button type="submit" class="btn-primary">
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
                            <h3 class="text-lg font-semibold text-sillon-500">✏️ Modifier la Récolte</h3>
                            <button type="button" @click="closeEditModal()" class="text-sillon-400 hover:text-sillon-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-4 space-y-4">
                        <!-- Variété -->
                        <div>
                            <label for="edit_id_variete" class="form-label">Variété <span class="text-red-500">*</span></label>
                            <select id="edit_id_variete" name="id_variete" class="form-select" x-model="editData.id_variete" required>
                                <option value="">Sélectionner une variété</option>
                                @foreach($varietes as $variete)
                                    <option value="{{ $variete->id_variete }}">
                                        {{ $variete->produit->nom_produit }} - {{ $variete->nom_variete }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="edit_date_recolte" class="form-label">Date de récolte <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                id="edit_date_recolte"
                                name="date_recolte" 
                                class="form-input"
                                x-model="editData.date_recolte"
                                required
                            >
                        </div>

                        <!-- Quantité -->
                        <div>
                            <label for="edit_quantite_recolte" class="form-label">Quantité récoltée (kg) <span class="text-red-500">*</span></label>
                            <input 
                                type="number" 
                                id="edit_quantite_recolte"
                                name="quantite_recolte" 
                                class="form-input text-xl font-bold text-center"
                                x-model="editData.quantite_recolte"
                                step="0.01"
                                min="0.01"
                                required
                            >
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
                class="relative bg-white rounded-xl shadow-xl w-full max-w-md z-10"
                @click.stop
            >
                <div class="px-6 py-4 border-b border-mbay-100 bg-blue-50 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-sillon-500">👁️ Détails de la Récolte</h3>
                        <button type="button" @click="closeShowModal()" class="text-sillon-400 hover:text-sillon-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Loading -->
                    <div x-show="showLoading" class="flex items-center justify-center py-8">
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
                                <span class="text-3xl">🌾</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-sillon-600" x-text="showData.variete?.nom_variete"></h4>
                                <p class="text-sm text-sillon-400">
                                    <span class="badge-info" x-text="showData.produit?.nom_produit"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Détails -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-sillon-400">📅 Date</span>
                                <span class="font-semibold text-sillon-600" x-text="showData.date_recolte"></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-mbay-50 rounded-lg">
                                <span class="text-sillon-400">⚖️ Quantité</span>
                                <span class="font-bold text-xl text-mbay-600" x-text="(showData.quantite_recolte || 0).toLocaleString('fr-FR') + ' kg'"></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-teranga-50 rounded-lg">
                                <span class="text-sillon-400">💰 Prix unitaire</span>
                                <span class="font-semibold text-teranga-600" x-text="(showData.prix_unitaire || 0).toLocaleString('fr-FR') + ' F/kg'"></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border-2 border-green-200">
                                <span class="text-sillon-400">💵 Valeur estimée</span>
                                <span class="font-bold text-xl text-green-600" x-text="(showData.valeur_estimee || 0).toLocaleString('fr-FR') + ' F'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-mbay-100 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                    <button type="button" @click="closeShowModal()" class="btn-outline">Fermer</button>
                    <button 
                        type="button" 
                        @click="closeShowModal(); openEditModal({
                            id: showData.id_recolte,
                            date_recolte: showData.date_recolte_raw,
                            quantite_recolte: showData.quantite_recolte,
                            id_variete: showData.id_variete
                        })"
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
                            Supprimer la récolte de <strong class="text-red-600" x-text="deleteQuantite + ' kg'"></strong> 
                            de <strong x-text="deleteVariete"></strong> 
                            du <strong x-text="deleteDate"></strong> ?
                        </p>
                        <p class="text-center text-sm text-sillon-400 mt-2">
                            ⚠️ Le stock disponible sera diminué de cette quantité.
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
function recoltesManager() {
    return {
        showCreateModal: false,
        showEditModal: false,
        editData: { id: null, date_recolte: '', quantite_recolte: '', id_variete: '' },
        editFormAction: '',
        showShowModal: false,
        showLoading: false,
        showData: {},
        showDeleteModal: false,
        deleteVariete: '',
        deleteDate: '',
        deleteQuantite: 0,
        deleteFormAction: '',

        openCreateModal() { this.showCreateModal = true; },
        closeCreateModal() { this.showCreateModal = false; },

        openEditModal(data) {
            this.editData = { ...data };
            this.editFormAction = `/recoltes/${data.id}`;
            this.showEditModal = true;
        },
        closeEditModal() {
            this.showEditModal = false;
            this.editData = { id: null, date_recolte: '', quantite_recolte: '', id_variete: '' };
        },

        async openShowModal(id) {
            this.showShowModal = true;
            this.showLoading = true;
            this.showData = {};

            try {
                const response = await fetch(`/recoltes/${id}`, {
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

        openDeleteModal(id, variete, date, quantite) {
            this.deleteVariete = variete;
            this.deleteDate = date;
            this.deleteQuantite = quantite;
            this.deleteFormAction = `/recoltes/${id}`;
            this.showDeleteModal = true;
        },
        closeDeleteModal() { this.showDeleteModal = false; }
    }
}
</script>
@endpush