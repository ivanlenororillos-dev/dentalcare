<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Dentist;
use App\Models\ToothHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToothHistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'dentist']);
        $this->client = Client::create([
            'first_name' => 'Test',
            'last_name' => 'Patient',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '555-0000',
        ]);
    }

    public function test_tooth_history_index_returns_json(): void
    {
        ToothHistory::create([
            'client_id' => $this->client->id,
            'tooth_number' => 14,
            'procedure_type' => 'filling',
            'status' => 'filled',
            'date_of_procedure' => '2025-06-15',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/clients/{$this->client->id}/teeth/14/history");

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['procedure_type' => 'filling']);
    }

    public function test_tooth_history_can_be_stored(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/clients/{$this->client->id}/teeth/8/history", [
                'procedure_type' => 'crown',
                'status' => 'crowned',
                'date_of_procedure' => '2025-12-01',
                'detailed_notes' => 'Porcelain crown placed.',
            ]);

        $response->assertCreated();
        $response->assertJsonFragment(['status' => 'crowned']);
        $this->assertDatabaseHas('tooth_history', [
            'client_id' => $this->client->id,
            'tooth_number' => 8,
            'procedure_type' => 'crown',
        ]);
    }

    public function test_tooth_history_store_validates_input(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson("/clients/{$this->client->id}/teeth/5/history", []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['procedure_type', 'status', 'date_of_procedure']);
    }

    public function test_tooth_history_can_be_updated(): void
    {
        $record = ToothHistory::create([
            'client_id' => $this->client->id,
            'tooth_number' => 3,
            'procedure_type' => 'examination',
            'status' => 'cavity',
            'date_of_procedure' => '2025-01-10',
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/clients/{$this->client->id}/teeth/3/history/{$record->id}", [
                'procedure_type' => 'filling',
                'status' => 'filled',
                'date_of_procedure' => '2025-02-15',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('tooth_history', [
            'id' => $record->id,
            'procedure_type' => 'filling',
            'status' => 'filled',
        ]);
    }

    public function test_tooth_history_can_be_deleted(): void
    {
        $record = ToothHistory::create([
            'client_id' => $this->client->id,
            'tooth_number' => 19,
            'procedure_type' => 'cleaning',
            'status' => 'healthy',
            'date_of_procedure' => '2025-06-01',
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/clients/{$this->client->id}/teeth/19/history/{$record->id}");

        $response->assertOk();
        $response->assertJsonFragment(['message' => 'Record deleted.']);
        $this->assertDatabaseMissing('tooth_history', ['id' => $record->id]);
    }

    public function test_tooth_history_returns_new_status_after_delete(): void
    {
        ToothHistory::create([
            'client_id' => $this->client->id,
            'tooth_number' => 10,
            'procedure_type' => 'examination',
            'status' => 'cavity',
            'date_of_procedure' => '2025-01-01',
        ]);

        $later = ToothHistory::create([
            'client_id' => $this->client->id,
            'tooth_number' => 10,
            'procedure_type' => 'filling',
            'status' => 'filled',
            'date_of_procedure' => '2025-03-01',
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/clients/{$this->client->id}/teeth/10/history/{$later->id}");

        $response->assertOk();
        $response->assertJsonFragment(['new_status' => 'cavity']);
    }

    public function test_tooth_history_with_dentist(): void
    {
        $dentist = Dentist::create([
            'name' => 'Dr. Smith',
            'license_number' => 'DDS-12345',
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/clients/{$this->client->id}/teeth/1/history", [
                'procedure_type' => 'extraction',
                'status' => 'extracted',
                'dentist_id' => $dentist->id,
                'date_of_procedure' => '2025-11-20',
                'detailed_notes' => 'Impacted wisdom tooth removed.',
            ]);

        $response->assertCreated();
        $response->assertJsonFragment(['dentist_name' => 'Dr. Smith']);
    }
}
