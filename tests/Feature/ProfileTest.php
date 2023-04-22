<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.personal-information.edit'));

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.personal-information.update'), [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+380732202222',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('profile.personal-information.edit'));

        $user->refresh();

        $this->assertSame('Test', $user->first_name);
        $this->assertSame('User', $user->last_name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.personal-information.update'), [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $user->email,
            'phone' => '+380732202222',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('profile.personal-information.edit'));

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('profile.personal-information.destroy'), [
            'password' => 'password',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect('/');

        $this->assertGuest();
        $this->assertSoftDeleted($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('profile.personal-information.edit'))
            ->delete(route('profile.personal-information.destroy'), [
                'password' => 'wrong-password',
            ]);

        $response->assertSessionHasErrors('password')->assertRedirect(route('profile.personal-information.edit'));

        $this->assertNotNull($user->fresh());
    }
}
