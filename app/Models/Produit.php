<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'categorie',
        'prix_unitaire',
        'unite',
        'quantite',
        'seuil_minimum',
        'emplacement',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}
