<?php

// use App\Models\Utilisateur;

// uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// test('profile page is displayed', function () {
//     $user = Utilisateur::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->get('/settings/profile');

//     $response->assertOk();
// });

// test('profile information can be updated', function () {
//     $user = Utilisateur::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->patch('/settings/profile', [
//             'name' => 'Test Utilisateur',
//             'email' => 'test@example.com',
//         ]);

//     $response
//         ->assertSessionHasNoErrors()
//         ->assertRedirect('/settings/profile');

//     $user->refresh();

//     expect($user->name)->toBe('Test Utilisateur');
//     expect($user->email)->toBe('test@example.com');
//     expect($user->email_verified_at)->toBeNull();
// });

// test('email verification status is unchanged when the email address is unchanged', function () {
//     $user = Utilisateur::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->patch('/settings/profile', [
//             'name' => 'Test Utilisateur',
//             'email' => $user->email,
//         ]);

//     $response
//         ->assertSessionHasNoErrors()
//         ->assertRedirect('/settings/profile');

//     expect($user->refresh()->email_verified_at)->not->toBeNull();
// });

// test('user can delete their account', function () {
//     $user = Utilisateur::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->delete('/settings/profile', [
//             'password' => 'password',
//         ]);

//     $response
//         ->assertSessionHasNoErrors()
//         ->assertRedirect('/');

//     $this->assertGuest();
//     expect($user->fresh())->toBeNull();
// });

// test('correct password must be provided to delete account', function () {
//     $user = Utilisateur::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->from('/settings/profile')
//         ->delete('/settings/profile', [
//             'password' => 'wrong-password',
//         ]);

//     $response
//         ->assertSessionHasErrors('password')
//         ->assertRedirect('/settings/profile');

//     expect($user->fresh())->not->toBeNull();
// });