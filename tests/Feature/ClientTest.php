<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_clients_index_requires_auth(): void
    {
        $response = $this->get(route('clients.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_clients_index_displays_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('clients.index'));
        $response->assertOk();
        $response->assertViewIs('clients.index');
    }

    public function test_client_can_be_created(): void
    {
        $response = $this->actingAs($this->user)->post(route('clients.store'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'date_of_birth' => '1990-05-15',
            'gender' => 'female',
            'phone' => '555-0100',
            'email' => 'jane@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);
    }

    public function test_client_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('clients.store'), []);
        $response->assertSessionHasErrors(['first_name', 'last_name', 'date_of_birth', 'gender', 'phone']);
    }

    public function test_client_can_be_updated(): void
    {
        $client = Client::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'date_of_birth' => '1985-03-20',
            'gender' => 'male',
            'phone' => '555-0200',
        ]);

        $response = $this->actingAs($this->user)->put(route('clients.update', $client), [
            'first_name' => 'Johnny',
            'last_name' => 'Smith',
            'date_of_birth' => '1985-03-20',
            'gender' => 'male',
            'phone' => '555-0201',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'first_name' => 'Johnny',
            'phone' => '555-0201',
        ]);
    }

    public function test_client_can_be_soft_deleted(): void
    {
        $client = Client::create([
            'first_name' => 'Mark',
            'last_name' => 'Jones',
            'date_of_birth' => '1978-11-10',
            'gender' => 'male',
            'phone' => '555-0300',
        ]);

        $response = $this->actingAs($this->user)->delete(route('clients.destroy', $client));
        $response->assertRedirect();
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }

    public function test_client_show_displays_profile(): void
    {
        $client = Client::create([
            'first_name' => 'Alice',
            'last_name' => 'Wong',
            'date_of_birth' => '1992-07-04',
            'gender' => 'female',
            'phone' => '555-0400',
        ]);

        $response = $this->actingAs($this->user)->get(route('clients.show', $client));
        $response->assertOk();
        $response->assertSee('Alice Wong');
    }

    public function test_client_search_filters_results(): void
    {
        Client::create(['first_name' => 'Alpha', 'last_name' => 'One', 'date_of_birth' => '2000-01-01', 'gender' => 'male', 'phone' => '111']);
        Client::create(['first_name' => 'Beta', 'last_name' => 'Two', 'date_of_birth' => '2000-01-01', 'gender' => 'female', 'phone' => '222']);

        $response = $this->actingAs($this->user)->get(route('clients.index', ['search' => 'Alpha']));
        $response->assertOk();
        $response->assertSee('Alpha One');
        $response->assertDontSee('Beta Two');
    }
}
