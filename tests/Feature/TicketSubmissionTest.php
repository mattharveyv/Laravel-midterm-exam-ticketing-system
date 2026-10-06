<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_submission_is_saved_and_redirects_with_ticket_details(): void
    {
        $response = $this->post(route('user.tickets.store'), [
            'customer_name' => 'Jordan Customer',
            'customer_email' => 'jordan@example.com',
            'subject' => 'Cannot connect to VPN',
            'category' => 'Network',
            'priority' => 'High',
            'department' => 'IT Support',
            'device' => 'Laptop',
            'location' => 'Remote',
            'description' => 'The VPN disconnects immediately after login.',
        ]);

        $response->assertRedirectToRoute('user.tickets.submitted', ['id' => 'NX-'.now()->format('Y').'-000001']);
        $response->assertSessionHas('submitted_ticket', fn (array $ticket): bool => $ticket['category'] === 'Network' && $ticket['priority'] === 'High'
        );
        $this->assertDatabaseHas('tickets', [
            'customer_email' => 'jordan@example.com',
            'subject' => 'Cannot connect to VPN',
            'status' => 'New',
        ]);
    }

    public function test_admin_ticket_list_shows_persisted_tickets(): void
    {
        Ticket::create([
            'ticket_number' => 'NX-2026-009876',
            'customer_name' => 'Jordan Customer',
            'customer_email' => 'jordan@example.com',
            'subject' => 'VPN access is unavailable',
            'category' => 'Network',
            'priority' => 'High',
            'status' => 'New',
            'agent' => 'Unassigned',
            'team' => 'IT Support',
            'description' => 'Connection fails after authentication.',
        ]);

        $this->get(route('admin.tickets'))
            ->assertSee('NX-2026-009876')
            ->assertSee('VPN access is unavailable')
            ->assertSee('jordan@example.com');
    }

    public function test_invalid_submission_does_not_create_a_ticket(): void
    {
        $response = $this->post(route('user.tickets.store'), []);

        $response->assertRedirectBackWithErrors(['customer_name', 'customer_email', 'subject', 'category', 'priority', 'department', 'description']);
        $this->assertDatabaseCount('tickets', 0);
    }
}
