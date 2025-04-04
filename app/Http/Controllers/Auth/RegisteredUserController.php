<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pseudo' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.Utilisateur::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Utilisateur::unguard();

        $user = Utilisateur::create([
            'nom' => $request->name,
            'prenom' => $request->prenom,
            'pseudo' => $request->pseudo,
            'dateNaissance' => $request->dateNaissance,
            'sexe' => $request->sexe,
            'typeMembre' => $request->typeMembre,
            'photo' => $request->photo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
