<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduits  = Produit::count();
        $totalCommandes = Commande::count();
        $totalRevenus   = Commande::where('statut', 'livree')->sum('montant_total');
        $totalAlertes   = Stock::where('statut', 'stock_bas')
                            ->orWhere('statut', 'rupture')->count();
        $totalProducteurs = User::where('role', 'producteur')->count();

        // Ventes par mois (6 derniers mois)
        $ventes = Commande::selectRaw('MONTH(created_at) as mois, SUM(montant_total) as total')
            ->where('statut', 'livree')
            ->whereYear('created_at', date('Y'))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Stock par produit
        $stocks = Stock::with('produit')
            ->orderBy('quantite_disponible', 'desc')
            ->take(5)
            ->get()
            ->map(fn($s) => [
                'produit'  => $s->produit->nom,
                'quantite' => $s->quantite_disponible,
            ]);

        return response()->json([
            'produits'    => $totalProduits,
            'commandes'   => $totalCommandes,
            'revenus'     => $totalRevenus,
            'alertes'     => $totalAlertes,
            'producteurs' => $totalProducteurs,
            'ventes'      => $ventes,
            'stocks'      => $stocks,
        ]);
    }
}
