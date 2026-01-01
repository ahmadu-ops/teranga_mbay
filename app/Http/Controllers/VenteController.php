<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenteRequest;
use App\Models\Vente;
use App\Services\VenteService;
use App\Services\VarieteService;
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
        $varietes = $this->varieteService->avecStock();
        $clients = $this->venteService->getClients();
        $stats = $this->venteService->getStats(
            $request->get('date_debut'),
            $request->get('date_fin')
        );

        return view('ventes.index', compact('ventes', 'varietes', 'clients', 'stats'));
    }

    public function create(): View
    {
        $varietes = $this->varieteService->avecStock();
        return view('ventes.create', compact('varietes'));
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

    public function show(Vente $vente): View
    {
        $vente = $this->venteService->find($vente->id_vente);
        return view('ventes.show', compact('vente'));
    }

    public function edit(Vente $vente): View
    {
        $varietes = $this->varieteService->avecStock();
        return view('ventes.edit', compact('vente', 'varietes'));
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