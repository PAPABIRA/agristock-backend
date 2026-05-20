<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('stock', 'user')->get();
        return response()->json($produits);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string',
            'categorie'    => 'required|string',
            'prix_unitaire'=> 'required|numeric',
            'quantite'     => 'required|integer',
            'seuil_minimum'=> 'required|integer',
            'emplacement'  => 'nullable|string',
            'unite'        => 'nullable|string',
        ]);

        $produit = Produit::create([
            'nom'           => $request->nom,
            'categorie'     => $request->categorie,
            'prix_unitaire' => $request->prix_unitaire,
            'quantite'      => $request->quantite,
            'seuil_minimum' => $request->seuil_minimum,
            'emplacement'   => $request->emplacement,
            'unite'         => $request->unite ?? 'kg',
            'user_id'       => auth()->id(),
        ]);

        // Créer le stock associé
        $stock = Stock::create([
            'produit_id'          => $produit->id,
            'quantite_disponible' => $request->quantite,
            'seuil_minimum'       => $request->seuil_minimum,
            'statut'              => $request->quantite <= $request->seuil_minimum ? 'stock_bas' : 'en_stock',
        ]);

        return response()->json([
            'message' => 'Produit créé avec succès',
            'produit' => $produit->load('stock'),
        ], 201);
    }

    public function show($id)
    {
        $produit = Produit::with('stock', 'user')->findOrFail($id);
        return response()->json($produit);
    }

    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);

        $produit->update($request->all());

        // Mettre à jour le stock
        if ($produit->stock) {
            $produit->stock->update([
                'quantite_disponible' => $request->quantite ?? $produit->stock->quantite_disponible,
                'seuil_minimum'       => $request->seuil_minimum ?? $produit->stock->seuil_minimum,
            ]);
            $produit->stock->updateStatut();
        }

        return response()->json([
            'message' => 'Produit mis à jour',
            'produit' => $produit->load('stock'),
        ]);
    }

    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();
        return response()->json(['message' => 'Produit supprimé']);
    }

    public function alertes()
    {
        $alertes = Stock::with('produit')
            ->where('statut', 'stock_bas')
            ->orWhere('statut', 'rupture')
            ->get();
        return response()->json($alertes);
    }
}
