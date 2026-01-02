<?php

namespace App\Http\Controllers;

use App\Http\Requests\VarieteRequest;
use App\Http\Requests\PrixRequest;
use App\Models\Variete;
use App\Services\VarieteService;
use App\Services\ProduitService;
use Illuminate\Http\JsonResponse;
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

    public function show(Variete $variete): View|JsonResponse
    {
        $details = $this->varieteService->getDetails($variete);

        // Si requête AJAX, retourner JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'variete' => [
                    'id_variete' => $variete->id_variete,
                    'nom_variete' => $variete->nom_variete,
                    'description' => $variete->description,
                ],
                'produit' => [
                    'id_produit' => $variete->produit->id_produit,
                    'nom_produit' => $variete->produit->nom_produit,
                ],
                'prix_actuel' => $details['prix_actuel'],
                'historique_prix' => $details['historique_prix']->map(fn($p) => [
                    'prix_kg' => $p->prix_kg,
                    'date_debut' => $p->date_debut?->format('d/m/Y'),
                    'date_fin' => $p->date_fin?->format('d/m/Y'),
                ]),
                'total_recolte' => $details['total_recolte'],
                'total_vendu' => $details['total_vendu'],
                'total_perdu' => $details['total_perdu'],
                'stock_disponible' => $details['stock_disponible'],
                'valeur_stock' => $details['valeur_stock'],
                'chiffre_affaires' => $details['chiffre_affaires'],
            ]);
        }

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
            ->route('varietes.index')
            ->with('success', 'Prix mis à jour avec succès.');
    }
}