<?php
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');
    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test Utilisateur',
        'prenom' => 'Test Prenom',
        'pseudo' => 'TestPseudo',
        'dateNaissance' => '1990-01-01',
        'sexe' => 'Femme',             // Capitalized to match the form
        'typeMembre' => 'Entraîneur',  // With accent to match the form
        'photo' => null,               // If this is optional or handle file upload separately
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});