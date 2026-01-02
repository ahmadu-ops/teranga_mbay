<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerteRequest;
use App\Models\Perte;
use App\Services\PerteService;
use App\Services\VarieteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerteController extends Controller
{
    public function __construct(
        private PerteService $perteService,
        private VarieteService $varieteService
    ) {}

    public function index(Request $request): View
    {
        $pertes = $this->perteService->paginate(
            perPage: 15,
            varieteId: $request->get('variete_id'),
            dateDebut: $request->get('date_debut'),
            dateFin: $request->get('date_fin'),
            motif: $request->get('motif')
        );
        $varietes = $this->varieteService->all();
        $motifs = $this->perteService->getMotifs();
        $stats = $this->perteService->getStats(
            $request->get('date_debut'),
            $request->get('date_fin')
        );
        $statsParMotif = $this->perteService->getStatsParMotif();

        $openCreateModal = $request->has('create');

        return view('pertes.index', compact('pertes', 'varietes', 'motifs', 'stats', 'statsParMotif', 'openCreateModal'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('pertes.index', ['create' => 1]);
    }

    public function store(PerteRequest $request): RedirectResponse
    {
        try {
            $this->perteService->create($request->validated());

            return redirect()
                ->route('pertes.index')
                ->with('success', 'Perte enregistrée avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Perte $perte): View|JsonResponse
    {
        $perte = $this->perteService->find($perte->id_perte);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id_perte' => $perte->id_perte,
                'date_perte' => $perte->date_perte->format('d/m/Y'),
                'date_perte_raw' => $perte->date_perte->format('Y-m-d'),
                'quantite_perdue' => (float) $perte->quantite_perdue,
                'motif_perte' => $perte->motif_perte,
                'motif_icone' => $perte->motif_icone,
                'valeur_estimee' => (float) $perte->valeur_estimee,
                'id_variete' => $perte->id_variete,
                'variete' => [
                    'id_variete' => $perte->variete->id_variete,
                    'nom_variete' => $perte->variete->nom_variete,
                ],
                'produit' => [
                    'id_produit' => $perte->variete->produit->id_produit,
                    'nom_produit' => $perte->variete->produit->nom_produit,
                ],
            ]);
        }

        return view('pertes.show', compact('perte'));
    }

    public function edit(Perte $perte): RedirectResponse
    {
        return redirect()->route('pertes.index', ['edit' => $perte->id_perte]);
    }

    public function update(PerteRequest $request, Perte $perte): RedirectResponse
    {
        try {
            $this->perteService->update($perte, $request->validated());

            return redirect()
                ->route('pertes.index')
                ->with('success', 'Perte mise à jour avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Perte $perte): RedirectResponse
    {
        $this->perteService->delete($perte);

        return redirect()
            ->route('pertes.index')
            ->with('success', 'Perte supprimée avec succès.');
    }
}