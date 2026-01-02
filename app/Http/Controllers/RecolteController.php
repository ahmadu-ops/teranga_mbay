<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecolteRequest;
use App\Models\Recolte;
use App\Services\RecolteService;
use App\Services\VarieteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecolteController extends Controller
{
    public function __construct(
        private RecolteService $recolteService,
        private VarieteService $varieteService
    ) {}

    public function index(Request $request): View
    {
        $recoltes = $this->recolteService->paginate(
            perPage: 15,
            varieteId: $request->get('variete_id'),
            dateDebut: $request->get('date_debut'),
            dateFin: $request->get('date_fin')
        );
        $varietes = $this->varieteService->all();
        $stats = $this->recolteService->getStats(
            $request->get('date_debut'),
            $request->get('date_fin')
        );

        return view('recoltes.index', compact('recoltes', 'varietes', 'stats'));
    }

    public function create(): View
    {
        $varietes = $this->varieteService->all();
        return view('recoltes.create', compact('varietes'));
    }

    public function store(RecolteRequest $request): RedirectResponse
    {
        $this->recolteService->create($request->validated());

        return redirect()
            ->route('recoltes.index')
            ->with('success', 'Récolte enregistrée avec succès.');
    }

    public function show(Recolte $recolte): View|JsonResponse
    {
        $recolte = $this->recolteService->find($recolte->id_recolte);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id_recolte' => $recolte->id_recolte,
                'date_recolte' => $recolte->date_recolte->format('d/m/Y'),
                'date_recolte_raw' => $recolte->date_recolte->format('Y-m-d'),
                'quantite_recolte' => (float) $recolte->quantite_recolte,
                'id_variete' => $recolte->id_variete,
                'variete' => [
                    'id_variete' => $recolte->variete->id_variete,
                    'nom_variete' => $recolte->variete->nom_variete,
                ],
                'produit' => [
                    'id_produit' => $recolte->variete->produit->id_produit,
                    'nom_produit' => $recolte->variete->produit->nom_produit,
                ],
                'prix_unitaire' => $recolte->variete->prix_actuel_value ?? 0,
                'valeur_estimee' => $recolte->valeur_estimee,
            ]);
        }

        return view('recoltes.show', compact('recolte'));
    }

    public function edit(Recolte $recolte): View
    {
        $varietes = $this->varieteService->all();
        return view('recoltes.edit', compact('recolte', 'varietes'));
    }

    public function update(RecolteRequest $request, Recolte $recolte): RedirectResponse
    {
        $this->recolteService->update($recolte, $request->validated());

        return redirect()
            ->route('recoltes.index')
            ->with('success', 'Récolte mise à jour avec succès.');
    }

    public function destroy(Recolte $recolte): RedirectResponse
    {
        $this->recolteService->delete($recolte);

        return redirect()
            ->route('recoltes.index')
            ->with('success', 'Récolte supprimée avec succès.');
    }
}