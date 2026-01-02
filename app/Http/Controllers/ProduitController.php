<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProduitRequest;
use App\Models\Produit;
use App\Services\ProduitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProduitController extends Controller
{
    public function __construct(
        private ProduitService $produitService
    ) {}

    public function index(Request $request): View
    {
        $produits = $this->produitService->paginate(
            perPage: 15,
            recherche: $request->get('recherche')
        );

        return view('produits.index', compact('produits'));
    }

    public function create(): View
    {
        return view('produits.create');
    }

    public function store(ProduitRequest $request): RedirectResponse
    {
        $this->produitService->create($request->validated());

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Produit $produit): View|JsonResponse
    {
        $produit = $this->produitService->find($produit->id_produit);
        $stats = $this->produitService->getStats($produit);

        // Si requête AJAX, retourner JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id_produit' => $produit->id_produit,
                'nom_produit' => $produit->nom_produit,
                'created_at' => $produit->created_at?->format('d/m/Y H:i'),
                'varietes' => $produit->varietes->map(fn($v) => [
                    'id_variete' => $v->id_variete,
                    'nom_variete' => $v->nom_variete,
                    'description' => $v->description,
                    'prix_actuel' => $v->prixActuel,
                ]),
                'stats' => $stats,
            ]);
        }

        return view('produits.show', compact('produit', 'stats'));
    }

    public function edit(Produit $produit): View
    {
        return view('produits.edit', compact('produit'));
    }

    public function update(ProduitRequest $request, Produit $produit): RedirectResponse
    {
        $this->produitService->update($produit, $request->validated());

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit): RedirectResponse
    {
        $this->produitService->delete($produit);

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}