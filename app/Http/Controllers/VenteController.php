<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenteRequest;
use App\Models\Vente;
use App\Services\VenteService;
use App\Services\VarieteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenteController extends Controller
{
    public function __construct(
        private VenteService $venteService,
        private VarieteService $varieteService
    ) {}

    public function index(Request $request): View
    {
        $ventes = $this->venteService->paginate(
            perPage: 15,
            varieteId: $request->get('variete_id'),
            dateDebut: $request->get('date_debut'),
            dateFin: $request->get('date_fin'),
            client: $request->get('client')
        );
        $varietes = $this->varieteService->all();
        $clients = $this->venteService->getClients();
        $stats = $this->venteService->getStats(
            $request->get('date_debut'),
            $request->get('date_fin')
        );

        $openCreateModal = $request->has('create');

        return view('ventes.index', compact('ventes', 'varietes', 'clients', 'stats', 'openCreateModal'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('ventes.index', ['create' => 1]);
    }

    public function store(VenteRequest $request): RedirectResponse
    {
        try {
            $this->venteService->create($request->validated());

            return redirect()
                ->route('ventes.index')
                ->with('success', 'Vente enregistrée avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Vente $vente): View|JsonResponse
    {
        $vente = $this->venteService->find($vente->id_vente);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id_vente' => $vente->id_vente,
                'date_vente' => $vente->date_vente->format('d/m/Y'),
                'date_vente_raw' => $vente->date_vente->format('Y-m-d'),
                'quantite_vendu' => (float) $vente->quantite_vendu,
                'prix_unitaire' => (float) $vente->prix_unitaire,
                'montant_total' => (float) $vente->montant_total,
                'nom_client' => $vente->nom_client,
                'id_variete' => $vente->id_variete,
                'variete' => [
                    'id_variete' => $vente->variete->id_variete,
                    'nom_variete' => $vente->variete->nom_variete,
                ],
                'produit' => [
                    'id_produit' => $vente->variete->produit->id_produit,
                    'nom_produit' => $vente->variete->produit->nom_produit,
                ],
            ]);
        }

        return view('ventes.show', compact('vente'));
    }

    public function edit(Vente $vente): RedirectResponse
    {
        return redirect()->route('ventes.index', ['edit' => $vente->id_vente]);
    }

    public function update(VenteRequest $request, Vente $vente): RedirectResponse
    {
        try {
            $this->venteService->update($vente, $request->validated());

            return redirect()
                ->route('ventes.index')
                ->with('success', 'Vente mise à jour avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Vente $vente): RedirectResponse
    {
        $this->venteService->delete($vente);

        return redirect()
            ->route('ventes.index')
            ->with('success', 'Vente supprimée avec succès.');
    }
}