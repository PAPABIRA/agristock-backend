<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'quantite_disponible',
        'seuil_minimum',
        'statut',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function updateStatut()
    {
        if ($this->quantite_disponible <= 0) {
            $this->statut = 'rupture';
        } elseif ($this->quantite_disponible <= $this->seuil_minimum) {
            $this->statut = 'stock_bas';
        } else {
            $this->statut = 'en_stock';
        }
        $this->save();
    }
}
