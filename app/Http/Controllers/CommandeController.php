<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Stock;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $commandes = Commande::with('lignesCommandes.produit', 'user')->get();
        } elseif ($user->role === 'client') {
            $commandes = Commande::with('lignesCommandes.produit')
                ->where('user_id', $user->id)->get();
        } else {
            $commandes = Commande::with('lignesCommandes.produit', 'user')->get();
        }

        return response()->json($commandes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_client' => 'required|string',
            'telephone'  => 'required|string',
            'email'      => 'required|email',
            'region'     => 'required|string',
            'adresse'    => 'required|string',
            'panier'     => 'required|array',
        ]);

        // Calculer le montant total
        $montantTotal = collect($request->panier)->sum(function ($item) {
            return $item['prix_unitaire'] * $item['quantite'];
        });

        // Créer la commande
        $commande = Commande::create([
            'user_id'      => auth()->id(),
            'montant_total'=> $montantTotal,
            'statut'       => 'en_attente',
            'nom_client'   => $request->nom_client,
            'telephone'    => $request->telephone,
            'email'        => $request->email,
            'region'       => $request->region,
            'adresse'      => $request->adresse,
        ]);

        // Créer les lignes de commande
        foreach ($request->panier as $item) {
            LigneCommande::create([
                'commande_id'   => $commande->id,
                'produit_id'    => $item['id'],
                'quantite'      => $item['quantite'],
                'prix_unitaire' => $item['prix_unitaire'],
                'sous_total'    => $item['prix_unitaire'] * $item['quantite'],
            ]);

            // Mettre à jour le stock
            $stock = Stock::where('produit_id', $item['id'])->first();
            if ($stock) {
                $stock->quantite_disponible -= $item['quantite'];
                $stock->updateStatut();
            }
        }

        return response()->json([
            'message'  => 'Commande créée avec succès',
            'commande' => $commande->load('lignesCommandes.produit'),
        ], 201);
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,expediee,livree',
        ]);

        $commande = Commande::findOrFail($id);
        $commande->update(['statut' => $request->statut]);

        return response()->json([
            'message'  => 'Statut mis à jour',
            'commande' => $commande,
        ]);
    }

    public function show($id)
    {
        $commande = Commande::with('lignesCommandes.produit', 'user')->findOrFail($id);
        return response()->json($commande);
    }
}
