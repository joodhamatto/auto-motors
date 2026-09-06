<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_setup_is_accessible_before_an_administrator_exists(): void
    {
        $this->get('/setup')->assertOk()->assertSee('Create administrator');
    }

    public function test_admin_login_redirects_to_setup_before_an_administrator_exists(): void
    {
        $this->get('/admin/login')->assertRedirect('/setup');
    }

    public function test_admin_dashboard_redirects_to_setup_before_an_administrator_exists(): void
    {
        $this->get('/admin')->assertRedirect('/setup');
    }

    public function test_setup_creates_an_administrator_and_redirects_to_login(): void
    {
        $response = $this->post('/setup', $this->validSetupData());

        $admin = User::where('email', 'owner@example.test')->firstOrFail();
        $response->assertRedirect('/admin/login')->assertSessionHas('success', 'Administrator account created. Please sign in.');
        $this->assertGuest();
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('Very-Strong1Password!', $admin->password));
    }

    public function test_setup_is_blocked_after_an_administrator_exists(): void
    {
        User::factory()->create(['is_admin' => true]);
        $this->get('/setup')->assertRedirect('/admin/login');
        $this->post('/setup')->assertRedirect('/admin/login');
    }

    public function test_setup_rejects_invalid_details(): void
    {
        $this->post('/setup', [
            'admin_name' => 'Owner',
            'admin_email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors(['admin_email', 'password']);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_normal_admin_login_works_after_setup(): void
    {
        $this->post('/setup', $this->validSetupData())->assertRedirect('/admin/login');

        $this->post('/admin/login', [
            'email' => 'owner@example.test',
            'password' => 'Very-Strong1Password!',
        ])->assertRedirect('/admin');

        $this->assertAuthenticated();
        $this->get('/admin')->assertOk();
    }

    private function validSetupData(): array
    {
        return [
            'admin_name' => 'Business Owner',
            'admin_email' => 'owner@example.test',
            'password' => 'Very-Strong1Password!',
            'password_confirmation' => 'Very-Strong1Password!',
        ];
    }
}
