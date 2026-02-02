@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Tableau de Bord')
@section('subheader', 'Vue d\'ensemble de votre exploitation agricole')

@section('content')
    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Produits -->
        <div class="kpi-card">
            <div class="kpi-icon bg-mbay-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['produits']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['produits']['label'] }}</p>
            </div>
        </div>

        <!-- Variétés -->
        <div class="kpi-card">
            <div class="kpi-icon bg-teranga-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['varietes']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['varietes']['label'] }}</p>
            </div>
        </div>

        <!-- Total Récolté -->
        <div class="kpi-card">
            <div class="kpi-icon bg-mbay-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['recoltes']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['recoltes']['label'] }}</p>
            </div>
        </div>

        <!-- Chiffre d'Affaires -->
        <div class="kpi-card">
            <div class="kpi-icon bg-sillon-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['chiffre_affaires']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['chiffre_affaires']['label'] }}</p>
            </div>
        </div>
    </div>

    <!-- Deuxième ligne KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ventes -->
        <div class="kpi-card">
            <div class="kpi-icon bg-teranga-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['ventes']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['ventes']['label'] }}</p>
            </div>
        </div>

        <!-- Pertes -->
        <div class="kpi-card">
            <div class="kpi-icon bg-red-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['pertes']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['pertes']['label'] }}</p>
            </div>
        </div>

        <!-- Stock -->
        <div class="kpi-card">
            <div class="kpi-icon bg-blue-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['stock']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['stock']['label'] }}</p>
            </div>
        </div>

        <!-- Valeur Stock -->
        <div class="kpi-card">
            <div class="kpi-icon bg-purple-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
            </div>
            <div>
                <p class="kpi-value">{{ $kpis['valeur_stock']['valeur'] }}</p>
                <p class="kpi-label">{{ $kpis['valeur_stock']['label'] }}</p>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique Récoltes/Ventes -->
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-sillon-500">📊 Récoltes & Ventes Mensuelles</h3>
            </div>
            <div class="card-body">
                <canvas id="chartRecoltes" height="300"></canvas>
            </div>
        </div>

        <!-- Graphique Répartition Stock -->
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-sillon-500">🥧 Répartition du Stock par Produit</h3>
            </div>
            <div class="card-body">
                <canvas id="chartStock" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Ventes -->
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-sillon-500">🏆 Top 5 Ventes</h3>
                <a href="{{ route('ventes.index') }}" class="text-sm text-mbay-500 hover:text-mbay-600">Voir tout →</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Variété</th>
                            <th class="text-right">Quantité</th>
                            <th class="text-right">Revenus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topVentes as $vente)
                            <tr>
                                <td>
                                    <div>
                                        <p class="font-medium">{{ $vente->nom_variete }}</p>
                                        <p class="text-xs text-sillon-400">{{ $vente->nom_produit }}</p>
                                    </div>
                                </td>
                                <td class="text-right">{{ number_format($vente->total_vendu_kg, 2, ',', ' ') }} kg</td>
                                <td class="text-right font-semibold text-mbay-600">{{ number_format($vente->total_revenus, 0, ',', ' ') }} F</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-sillon-400">Aucune vente enregistrée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Rentabilité -->
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-sillon-500">💰 Top 5 Rentabilité</h3>
                <a href="{{ route('varietes.index') }}" class="text-sm text-mbay-500 hover:text-mbay-600">Voir tout →</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Variété</th>
                            <th class="text-right">Revenus</th>
                            <th class="text-right">Bénéfice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentabilite as $item)
                            <tr>
                                <td>
                                    <div>
                                        <p class="font-medium">{{ $item->nom_variete }}</p>
                                        <p class="text-xs text-sillon-400">{{ $item->nom_produit }}</p>
                                    </div>
                                </td>
                                <td class="text-right">{{ number_format($item->revenus_ventes, 0, ',', ' ') }} F</td>
                                <td class="text-right">
                                    <span class="{{ $item->benefice_net >= 0 ? 'text-mbay-600' : 'text-red-600' }} font-semibold">
                                        {{ number_format($item->benefice_net, 0, ',', ' ') }} F
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-sillon-400">Aucune donnée disponible</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mouvements Récents -->
    <div class="card mb-8">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-semibold text-sillon-500">📋 Mouvements Récents</h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Variété</th>
                        <th class="text-right">Quantité</th>
                        <th class="text-right">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mouvements as $mouvement)
                        <tr>
                            <td>
                                @switch($mouvement->type_mouvement)
                                    @case('RECOLTE')
                                        <span class="badge-success">🌾 Récolte</span>
                                        @break
                                    @case('VENTE')
                                        <span class="badge-warning">💰 Vente</span>
                                        @break
                                    @case('PERTE')
                                        <span class="badge-danger">📉 Perte</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ \Carbon\Carbon::parse($mouvement->date_mouvement)->format('d/m/Y') }}</td>
                            <td>{{ $mouvement->nom_produit }}</td>
                            <td>{{ $mouvement->nom_variete }}</td>
                            <td class="text-right {{ $mouvement->quantite >= 0 ? 'text-mbay-600' : 'text-red-600' }}">
                                {{ $mouvement->quantite >= 0 ? '+' : '' }}{{ number_format($mouvement->quantite, 2, ',', ' ') }} kg
                            </td>
                            <td class="text-right">
                                @if($mouvement->montant)
                                    {{ number_format($mouvement->montant, 0, ',', ' ') }} F
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-sillon-400">Aucun mouvement enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock Actuel -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-semibold text-sillon-500">📦 Stock Actuel</h3>
            <a href="{{ route('varietes.index') }}" class="text-sm text-mbay-500 hover:text-mbay-600">Gérer →</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Variété</th>
                        <th class="text-right">Récolté</th>
                        <th class="text-right">Vendu</th>
                        <th class="text-right">Perdu</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Prix/kg</th>
                        <th class="text-right">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks->take(10) as $stock)
                        <tr>
                            <td class="font-medium">{{ $stock->nom_produit }}</td>
                            <td>{{ $stock->nom_variete }}</td>
                            <td class="text-right">{{ number_format($stock->total_recolte, 2, ',', ' ') }}</td>
                            <td class="text-right text-teranga-600">{{ number_format($stock->total_vendu, 2, ',', ' ') }}</td>
                            <td class="text-right text-red-500">{{ number_format($stock->total_perdu, 2, ',', ' ') }}</td>
                            <td class="text-right">
                                @if($stock->stock_disponible > 50)
                                    <span class="badge-success">{{ number_format($stock->stock_disponible, 2, ',', ' ') }} kg</span>
                                @elseif($stock->stock_disponible > 10)
                                    <span class="badge-warning">{{ number_format($stock->stock_disponible, 2, ',', ' ') }} kg</span>
                                @else
                                    <span class="badge-danger">{{ number_format($stock->stock_disponible, 2, ',', ' ') }} kg</span>
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($stock->prix_actuel ?? 0, 0, ',', ' ') }} F</td>
                            <td class="text-right font-semibold text-mbay-600">{{ number_format($stock->valeur_stock ?? 0, 0, ',', ' ') }} F</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-sillon-400">Aucun stock disponible</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données des graphiques passées depuis le contrôleur
        const graphiqueRecoltes = @json($graphiqueRecoltes);
        const graphiqueVentes = @json($graphiqueVentes);
        const repartitionStock = @json($repartitionStock);

        // Graphique Récoltes / Ventes
        const ctxRecoltes = document.getElementById('chartRecoltes').getContext('2d');
        new Chart(ctxRecoltes, {
            type: 'bar',
            data: {
                labels: graphiqueRecoltes.labels.length > 0 ? graphiqueRecoltes.labels : graphiqueVentes.labels,
                datasets: [
                    {
                        label: 'Récoltes (kg)',
                        data: graphiqueRecoltes.data,
                        backgroundColor: '#2E7D32',
                        borderColor: '#1B5E20',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Ventes (kg)',
                        data: graphiqueVentes.quantites,
                        backgroundColor: '#F9A825',
                        borderColor: '#F57F17',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString('fr-FR') + ' kg';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' kg';
                            }
                        }
                    }
                }
            }
        });

        // Graphique Répartition Stock
        const ctxStock = document.getElementById('chartStock').getContext('2d');
        new Chart(ctxStock, {
            type: 'doughnut',
            data: {
                labels: repartitionStock.labels,
                datasets: [{
                    data: repartitionStock.stocks,
                    backgroundColor: [
                        '#1E88E5', // Bleu
                        '#E53935', // Rouge
                        '#43A047', // Vert
                        '#FB8C00', // Orange
                        '#8E24AA', // Violet
                        '#FDD835', // Jaune
                        '#6D4C41', // Marron
                        '#00ACC1', // Cyan
                        '#D81B60', // Rose
                        '#3949AB'  // Indigo
                        ],

                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': ' + value.toLocaleString('fr-FR') + ' kg ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush