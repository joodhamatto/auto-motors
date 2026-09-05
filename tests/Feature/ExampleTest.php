<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_portfolio_is_french_by_default(): void
    {
        $this->get('/')->assertOk()->assertSee('Accueil')->assertSee('AUTO MOTORS SARL')->assertSee('Kia');
    }

    public function test_every_whatsapp_button_uses_the_central_whatsapp_setting(): void
    {
        $response = $this->get('/')->assertOk();

        $this->assertSame(2, substr_count($response->getContent(), 'https://wa.me/225708236417'));
        $response->assertDontSee('https://wa.me/225778969396', false);
    }

    public function test_language_choice_is_stored_in_session(): void
    {
        $this->get('/language/en')->assertRedirect();
        $this->get('/')->assertOk()->assertSee('Why choose Auto Motors?')->assertSessionHas('locale', 'en');
    }

    public function test_guests_cannot_access_admin(): void
    {
        User::factory()->create(['is_admin' => true]);
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        User::factory()->create(['is_admin' => true]);
        $this->actingAs(User::factory()->create(['is_admin' => false]))->get('/admin')->assertForbidden();
    }

    public function test_admin_pages_render_for_an_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Content workflow');
        $this->actingAs($admin)->get('/admin/settings')->assertOk()->assertSee('Site &amp; company settings', false);
        $this->actingAs($admin)->get('/admin/products')->assertOk()->assertSee('No records found');
        $this->actingAs($admin)->get('/admin/services/create')->assertOk()
            ->assertSee('French (Default)')->assertSee('English Translation')
            ->assertSee('name="title_fr"', false)->assertSee('name="title_en"', false);
    }

    public function test_admin_sidebar_has_working_navigation_and_secure_logout_markup(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get('/admin')->assertOk()
            ->assertSee('class="admin-sidebar offcanvas-xl offcanvas-start"', false)
            ->assertDontSee('<span class="text-muted small">AUTO MOTORS SARL</span>', false)
            ->assertSee('data-bs-target="#adminSidebar"', false)
            ->assertSee('data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar"', false)
            ->assertSee('id="sidebarCollapse"', false)
            ->assertSee('autoMotors.adminSidebarCollapsed', false)
            ->assertSee('title="Dashboard"', false)
            ->assertSee('bi-chevron-left', false)
            ->assertSee('sidebar-compact-mark', false)
            ->assertSee('aria-label="Sign out" data-sidebar-tooltip', false)
            ->assertSee('href="http://localhost/admin/settings"', false)
            ->assertSee('href="http://localhost/admin/services"', false)
            ->assertSee('action="http://localhost/admin/logout"', false)
            ->assertSee('name="_token"', false);
    }

    public function test_admin_can_login_and_logout(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'email' => 'admin@example.test', 'password' => 'A-secure-test-password']);
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'A-secure-test-password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_admin_can_create_and_deactivate_service(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $data = ['title_fr' => 'Test FR', 'title_en' => 'Test EN', 'description_fr' => 'Description FR', 'description_en' => 'Description EN', 'icon' => 'bi-wrench', 'display_order' => 9, 'is_active' => 1];
        $this->actingAs($admin)->post('/admin/services', $data)->assertRedirect('/admin/services');
        $service = Service::where('title_fr', 'Test FR')->firstOrFail();
        $this->actingAs($admin)->post("/admin/services/{$service->id}/toggle")->assertRedirect();
        $this->assertFalse($service->fresh()->is_active);
    }

    public function test_server_rejects_non_image_upload(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $data = ['title_fr' => 'Test FR', 'title_en' => 'Test EN', 'description_fr' => 'Description FR', 'description_en' => 'Description EN', 'icon' => 'bi-wrench', 'display_order' => 9, 'is_active' => 1, 'image' => UploadedFile::fake()->create('malware.php', 20, 'application/x-php')];
        $this->actingAs($admin)->post('/admin/services', $data)->assertSessionHasErrors('image');
    }
}
