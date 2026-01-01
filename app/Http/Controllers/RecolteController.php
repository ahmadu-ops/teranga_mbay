<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecolteRequest;
use App\Models\Recolte;
use App\Services\RecolteService;
use App\Services\VarieteService;
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

    public function show(Recolte $recolte): View
    {
        $recolte = $this->recolteService->find($recolte->id_recolte);
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