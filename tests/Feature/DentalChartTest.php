<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\TeethMaster;
use App\Models\User;
use Database\Seeders\TeethMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DentalChartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'dentist']);
        $this->client = Client::create([
            'first_name' => 'Chart',
            'last_name' => 'Test',
            'date_of_birth' => '1988-06-15',
            'gender' => 'female',
            'phone' => '555-9999',
        ]);
    }

    public function test_dental_chart_requires_auth(): void
    {
        $response = $this->get(route('clients.chart', $this->client));
        $response->assertRedirect(route('login'));
    }

    public function test_dental_chart_loads_for_authenticated_user(): void
    {
        $this->seed(TeethMasterSeeder::class);

        $response = $this->actingAs($this->user)->get(route('clients.chart', $this->client));
        $response->assertOk();
        $response->assertViewIs('clients.chart');
        $response->assertSee('Dental Chart');
        $response->assertSee('Chart Test');
    }

    public function test_teeth_master_seeder_creates_32_teeth(): void
    {
        $this->seed(TeethMasterSeeder::class);
        $this->assertDatabaseCount('teeth_master', 32);
    }

    public function test_teeth_master_has_correct_quadrants(): void
    {
        $this->seed(TeethMasterSeeder::class);

        $this->assertEquals(8, TeethMaster::where('quadrant', 'upper_right')->count());
        $this->assertEquals(8, TeethMaster::where('quadrant', 'upper_left')->count());
        $this->assertEquals(8, TeethMaster::where('quadrant', 'lower_left')->count());
        $this->assertEquals(8, TeethMaster::where('quadrant', 'lower_right')->count());
    }

    public function test_teeth_master_has_correct_numbering(): void
    {
        $this->seed(TeethMasterSeeder::class);

        for ($i = 1; $i <= 32; $i++) {
            $this->assertDatabaseHas('teeth_master', ['tooth_number' => $i]);
        }
    }

    public function test_tooth_1_is_upper_right_wisdom(): void
    {
        $this->seed(TeethMasterSeeder::class);

        $tooth = TeethMaster::where('tooth_number', 1)->first();
        $this->assertEquals('upper_right', $tooth->quadrant);
        $this->assertEquals('Wisdom Tooth', $tooth->standard_name);
        $this->assertEquals('3rd Molar', $tooth->alternate_name);
        $this->assertEquals('molar', $tooth->tooth_type);
    }

    public function test_tooth_9_is_upper_left_central_incisor(): void
    {
        $this->seed(TeethMasterSeeder::class);

        $tooth = TeethMaster::where('tooth_number', 9)->first();
        $this->assertEquals('upper_left', $tooth->quadrant);
        $this->assertEquals('Central Incisor', $tooth->standard_name);
        $this->assertEquals('incisor', $tooth->tooth_type);
    }

    public function test_chart_view_receives_required_data(): void
    {
        $this->seed(TeethMasterSeeder::class);

        $response = $this->actingAs($this->user)->get(route('clients.chart', $this->client));
        $response->assertViewHas('client');
        $response->assertViewHas('teethMaster');
        $response->assertViewHas('toothStatuses');
        $response->assertViewHas('dentists');
    }

    public function test_pdf_report_can_be_generated(): void
    {
        $this->seed(TeethMasterSeeder::class);

        $response = $this->actingAs($this->user)->get(route('clients.report.pdf', $this->client));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
