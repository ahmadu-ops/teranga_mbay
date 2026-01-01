<?php

namespace App\Http\Controllers;

use App\Http\Requests\VarieteRequest;
use App\Http\Requests\PrixRequest;
use App\Models\Variete;
use App\Services\VarieteService;
use App\Services\ProduitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VarieteController extends Controller
{
    public function __construct(
        private VarieteService $varieteService,
        private ProduitService $produitService
    ) {}

    public function index(Request $request): View
    {
        $varietes = $this->varieteService->paginate(
            perPage: 15,
            produitId: $request->get('produit_id'),
            recherche: $request->get('recherche')
        );
        $produits = $this->produitService->all();

        return view('varietes.index', compact('varietes', 'produits'));
    }

    public function create(): View
    {
        $produits = $this->produitService->all();
        return view('varietes.create', compact('produits'));
    }

    public function store(VarieteRequest $request): RedirectResponse
    {
        $this->varieteService->create($request->validated());

        return redirect()
            ->route('varietes.index')
            ->with('success', 'Variété créée avec succès.');
    }

    public function show(Variete $variete): View
    {
        $details = $this->varieteService->getDetails($variete);
        return view('varietes.show', $details);
    }

    public function edit(Variete $variete): View
    {
        $produits = $this->produitService->all();
        return view('varietes.edit', compact('variete', 'produits'));
    }

    public function update(VarieteRequest $request, Variete $variete): RedirectResponse
    {
        $this->varieteService->update($variete, $request->validated());

        return redirect()
            ->route('varietes.index')
            ->with('success', 'Variété mise à jour avec succès.');
    }

    public function destroy(Variete $variete): RedirectResponse
    {
        $this->varieteService->delete($variete);

        return redirect()
            ->route('varietes.index')
            ->with('success', 'Variété supprimée avec succès.');
    }

    /**
     * Mettre à jour le prix
     */
    public function updatePrix(PrixRequest $request, Variete $variete): RedirectResponse
    {
        $this->varieteService->updatePrix(
            $variete,
            $request->prix_kg,
            $request->date_debut
        );

        return redirect()
            ->route('varietes.show', $variete)
            ->with('success', 'Prix mis à jour avec succès.');
    }
}