<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'utilisateurs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pseudo',
        'email',
        'password',
        'nom',
        'prenom',
        'dateNaissance',
        'age',
        'sexe',
        'typeMembre',
        'niveau',
        'points',
        'photo',
        'estActif',
        'estVerifie'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dateNaissance' => 'date',
        'dateInscription' => 'datetime',
        'derniereConnexion' => 'datetime',
        'estActif' => 'boolean',
        'estVerifie' => 'boolean',
        'points' => 'float',
    ];

    public function connexions()
    {
        return $this->hasMany(ConnexionUtilisateur::class, 'idUtilisateur');
    }
}
