<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'pseudo',
        'email',
        'password', // Utilisez uniquement password
        'nom',
        'prenom',
        'dateNaissance',
        'age',
        'sexe',
        'typeMembre',
        'niveau',
        'points',
        'photo',
        'derniereConnexion',
        'estActif',
        'estVerifie'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts()
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dateNaissance' => 'date',
            'dateInscription' => 'datetime',
            'derniereConnexion' => 'datetime',
            'estActif' => 'boolean',
            'estVerifie' => 'boolean',
            'points' => 'float',
        ];
    }

    // Relations (conservées inchangées)
    public function connexions()
    {
        return $this->hasMany(ConnexionUtilisateur::class, 'idUtilisateur');
    }

    public function actions()
    {
        return $this->hasMany(ActionUtilisateur::class, 'idUtilisateur');
    }

    public function historiquesObjets()
    {
        return $this->hasMany(HistoriqueObjet::class, 'idUtilisateur');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
