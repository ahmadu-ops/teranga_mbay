<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerteRequest;
use App\Models\Perte;
use App\Services\PerteService;
use App\Services\VarieteService;
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

        return view('pertes.index', compact('pertes', 'varietes', 'motifs', 'stats', 'statsParMotif'));
    }

    public function create(): View
    {
        $varietes = $this->varieteService->avecStock();
        $motifs = $this->perteService->getMotifs();
        return view('pertes.create', compact('varietes', 'motifs'));
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

    public function show(Perte $perte): View
    {
        $perte = $this->perteService->find($perte->id_perte);
        return view('pertes.show', compact('perte'));
    }

    public function edit(Perte $perte): View
    {
        $varietes = $this->varieteService->all();
        $motifs = $this->perteService->getMotifs();
        return view('pertes.edit', compact('perte', 'varietes', 'motifs'));
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