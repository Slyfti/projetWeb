<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ConnexionUtilisateur;
use App\Models\ObjetConnecte;
use App\Models\CategorieObjet;
use App\Models\ZoneStade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private const POINTS_CREATION = 10;
    private const POINTS_MODIFICATION = 5;
    private const POINTS_SUPPRESSION = 15;

    private function addPointsToUser($userId, $points)
    {
        $user = User::find($userId);
        if ($user) {
            $user->points += $points;
            $user->save();
        }
    }

    private function getUsers()
    {
        return User::select('id', 'pseudo', 'email', 'nom', 'prenom', 'niveau', 'points', 'typeMembre', 'sexe', 'dateNaissance', 'created_at')
            ->orderBy('pseudo')
            ->get();
    }

    public function index()
    {
        try {
            return Inertia::render('dashboard/GestionUtilisateur', [
                'users' => $this->getUsers(),
                'objets' => ObjetConnecte::with(['categorie', 'zone'])->get(),
                'categories' => CategorieObjet::all(),
                'zones' => ZoneStade::all()
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving data:', ['error' => $e->getMessage()]);
            return Inertia::render('dashboard/GestionUtilisateur', [
                'users' => [],
                'objets' => [],
                'categories' => [],
                'zones' => []
            ]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pseudo' => 'required|unique:utilisateurs',
            'email' => 'required|email|unique:utilisateurs',
            'password' => 'required|min:8',
            'nom' => 'nullable|string',
            'prenom' => 'nullable|string',
            'dateNaissance' => 'nullable|date',
            'sexe' => 'nullable|in:Homme,Femme,Autre',
            'typeMembre' => 'required|in:Spectateur,Athlète,Entraîneur,Personnel technique,Sécurité,Administratif',
            'niveau' => 'required|in:Débutant,Intermédiaire,Avancé,Expert',
            'points' => 'required|integer|min:0'
        ], [
            'pseudo.required' => 'Le pseudo est obligatoire',
            'pseudo.unique' => 'Ce pseudo est déjà utilisé',
            'email.required' => 'L\'adresse email est obligatoire',
            'email.email' => 'L\'adresse email n\'est pas valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit faire au moins 8 caractères',
            'nom.string' => 'Le nom doit être une chaîne de caractères',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères',
            'dateNaissance.date' => 'La date de naissance n\'est pas valide',
            'sexe.in' => 'Le sexe doit être Homme, Femme ou Autre',
            'typeMembre.required' => 'Le type de membre est obligatoire',
            'typeMembre.in' => 'Le type de membre n\'est pas valide',
            'niveau.required' => 'Le niveau est obligatoire',
            'niveau.in' => 'Le niveau n\'est pas valide',
            'points.required' => 'Le nombre de points est obligatoire',
            'points.integer' => 'Le nombre de points doit être un nombre entier',
            'points.min' => 'Le nombre de points ne peut pas être négatif'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        // Ajouter des points à l'utilisateur qui crée
        $this->addPointsToUser(auth()->id(), self::POINTS_CREATION);

        return redirect()->route('dashboard');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'pseudo' => 'unique:utilisateurs,pseudo,' . $user->id,
            'email' => 'email|unique:utilisateurs,email,' . $user->id,
            'nom' => 'nullable|string',
            'prenom' => 'nullable|string',
            'dateNaissance' => 'nullable|date',
            'sexe' => 'nullable|in:Homme,Femme,Autre',
            'typeMembre' => 'in:Spectateur,Athlète,Entraîneur,Personnel technique,Sécurité,Administratif',
            'niveau' => 'in:Débutant,Intermédiaire,Avancé,Expert',
            'points' => 'required|integer|min:0'
        ], [
            'pseudo.unique' => 'Ce pseudo est déjà utilisé',
            'email.email' => 'L\'adresse email n\'est pas valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'nom.string' => 'Le nom doit être une chaîne de caractères',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères',
            'dateNaissance.date' => 'La date de naissance n\'est pas valide',
            'sexe.in' => 'Le sexe doit être Homme, Femme ou Autre',
            'typeMembre.in' => 'Le type de membre n\'est pas valide',
            'niveau.in' => 'Le niveau n\'est pas valide',
            'points.required' => 'Le nombre de points est obligatoire',
            'points.integer' => 'Le nombre de points doit être un nombre entier',
            'points.min' => 'Le nombre de points ne peut pas être négatif'
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        
        // Ajouter des points à l'utilisateur qui modifie
        $this->addPointsToUser(auth()->id(), self::POINTS_MODIFICATION);
        
        return redirect()->route('dashboard');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        // Ajouter des points à l'utilisateur qui supprime
        $this->addPointsToUser(auth()->id(), self::POINTS_SUPPRESSION);
        
        return redirect()->route('dashboard');
    }

    public function getPoints(User $user)
    {
        return response()->json([
            'points' => $user->points
        ]);
    }

    public function getLoginHistory(User $user)
    {
        $connexions = ConnexionUtilisateur::where('idUtilisateur', $user->id)
            ->orderBy('dateConnexion', 'desc')
            ->get();

        return Inertia::render('GestionUtilisateur', [
            'users' => $this->getUsers(),
            'connexions' => $connexions
        ]);
    }
} 