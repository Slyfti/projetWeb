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

    // Seuils de points pour chaque niveau
    const SEUIL_DEBUTANT = 0;
    const SEUIL_INTERMEDIAIRE = 100;
    const SEUIL_AVANCE = 300;
    const SEUIL_EXPERT = 600;

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

    /**
     * Vérifie si l'utilisateur a suffisamment de points pour atteindre un niveau donné
     */
    public function peutAtteindreNiveau($niveau)
    {
        $pointsRequis = match($niveau) {
            'Expert' => self::SEUIL_EXPERT,
            'Avancé' => self::SEUIL_AVANCE,
            'Intermédiaire' => self::SEUIL_INTERMEDIAIRE,
            'Débutant' => self::SEUIL_DEBUTANT,
            default => 0
        };

        return $this->points >= $pointsRequis;
    }

    /**
     * Met à jour le niveau de l'utilisateur en fonction de ses points
     */
    public function updateNiveau()
    {
        $points = $this->points;
        $nouveauNiveau = 'Débutant';

        if ($points >= self::SEUIL_EXPERT) {
            $nouveauNiveau = 'Expert';
        } elseif ($points >= self::SEUIL_AVANCE) {
            $nouveauNiveau = 'Avancé';
        } elseif ($points >= self::SEUIL_INTERMEDIAIRE) {
            $nouveauNiveau = 'Intermédiaire';
        }

        if ($this->niveau !== $nouveauNiveau && $this->peutAtteindreNiveau($nouveauNiveau)) {
            $this->niveau = $nouveauNiveau;
            $this->save();
        }
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            $user->updateNiveau();
        });
    }
}
