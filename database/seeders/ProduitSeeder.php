<?php

namespace Database\Seeders;

use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $producteur = User::where('role', 'producteur')->first();

        $produits = [
            ['nom' => 'Riz blanc premium', 'categorie' => 'Céréales', 'prix_unitaire' => 500, 'quantite' => 1200, 'seuil_minimum' => 200, 'emplacement' => 'Entrepôt A', 'unite' => 'kg'],
            ['nom' => 'Mil traditionnel', 'categorie' => 'Céréales', 'prix_unitaire' => 400, 'quantite' => 800, 'seuil_minimum' => 200, 'emplacement' => 'Entrepôt A', 'unite' => 'kg'],
            ['nom' => 'Maïs jaune', 'categorie' => 'Céréales', 'prix_unitaire' => 350, 'quantite' => 950, 'seuil_minimum' => 200, 'emplacement' => 'Entrepôt B', 'unite' => 'kg'],
            ['nom' => 'Arachide coque', 'categorie' => 'Oléagineux', 'prix_unitaire' => 800, 'quantite' => 150, 'seuil_minimum' => 200, 'emplacement' => 'Entrepôt C', 'unite' => 'kg'],
            ['nom' => 'Niébé local', 'categorie' => 'Légumineuses', 'prix_unitaire' => 600, 'quantite' => 450, 'seuil_minimum' => 200, 'emplacement' => 'Entrepôt B', 'unite' => 'kg'],
            ['nom' => 'Tomates fraîches', 'categorie' => 'Légumes', 'prix_unitaire' => 300, 'quantite' => 200, 'seuil_minimum' => 100, 'emplacement' => 'Chambre froide', 'unite' => 'kg'],
        ];

        foreach ($produits as $data) {
            $produit = Produit::create([
                ...$data,
                'user_id' => $producteur->id,
            ]);

            Stock::create([
                'produit_id'          => $produit->id,
                'quantite_disponible' => $data['quantite'],
                'seuil_minimum'       => $data['seuil_minimum'],
                'statut'              => $data['quantite'] <= $data['seuil_minimum'] ? 'stock_bas' : 'en_stock',
            ]);
        }
    }
}

