<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ConnexionUtilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::select('id', 'pseudo', 'email', 'nom', 'prenom', 'niveau', 'points', 'typeMembre')
                ->orderBy('pseudo')
                ->get();

            return Inertia::render('Dashboard', [
                'users' => $users
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving users:', ['error' => $e->getMessage()]);
            return Inertia::render('Dashboard', [
                'users' => []
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
            'niveau' => 'required|in:Débutant,Intermédiaire,Avancé,Expert'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->back();
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
            'niveau' => 'in:Débutant,Intermédiaire,Avancé,Expert'
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return redirect()->back()->with('success', 'Utilisateur modifié avec succès');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }

    public function updateAccessLevel(Request $request, User $user)
    {
        $validated = $request->validate([
            'niveau' => 'required|in:Débutant,Intermédiaire,Avancé,Expert'
        ]);

        $user->update($validated);

        return redirect()->back();
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

        return Inertia::render('Dashboard', [
            'users' => User::select('id', 'pseudo', 'email', 'nom', 'prenom', 'niveau', 'points', 'typeMembre')
                ->orderBy('pseudo')
                ->get(),
            'connexions' => $connexions
        ]);
    }

    public function updateUserType(Request $request, User $user)
    {
        $validated = $request->validate([
            'typeMembre' => 'required|in:Spectateur,Athlète,Entraîneur,Personnel technique,Sécurité,Administratif'
        ]);

        $user->update($validated);

        return redirect()->back();
    }
} 