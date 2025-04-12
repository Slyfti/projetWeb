<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    protected $primaryKey = 'idTicket';
    public $timestamps = true;

    protected $fillable = [
        'idUtilisateur',
        'idEvenements',
        'numeroTicket',
        'statut',
        'dateAchat',
        'dateUtilisation',
        'typePlace',
        'numeroPlace',
        'prixPaye',
        'notes'
    ];

    protected $casts = [
        'dateAchat' => 'datetime',
        'dateUtilisation' => 'datetime',
        'prixPaye' => 'decimal:2'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idUtilisateur', 'id');
    }

    /**
     * Relation avec l'événement
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'idEvenements', 'idEvenements');
    }
} 