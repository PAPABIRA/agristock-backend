<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'montant_total',
        'statut',
        'nom_client',
        'telephone',
        'email',
        'region',
        'adresse',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}
