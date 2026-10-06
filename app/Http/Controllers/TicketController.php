<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['IT Support', 'Hardware', 'Software', 'Network', 'Account', 'Billing', 'Security', 'Bug Report', 'Feature Request', 'General Inquiry'])],
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High', 'Critical'])],
            'department' => ['required', 'string', 'max:100'],
            'device' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
        ]);

        $ticket = Ticket::create([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'team' => $validated['department'],
            'device' => $validated['device'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'],
        ]);

        $ticket->ticket_number = sprintf('NX-%s-%06d', now()->format('Y'), $ticket->getKey());
        $ticket->save();

        return redirect()
            ->route('user.tickets.submitted', ['id' => $ticket->ticket_number])
            ->with('submitted_ticket', [
                'id' => $ticket->ticket_number,
                'category' => $ticket->category,
                'priority' => $ticket->priority,
                'team' => $ticket->team,
            ]);
    }

    public function index(): View
    {
        $databaseTickets = Ticket::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(static fn (Ticket $ticket): array => [
                'id' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'category' => $ticket->category,
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'agent' => $ticket->agent,
                'team' => $ticket->team,
                'email' => $ticket->customer_email,
                'updated' => $ticket->updated_at->diffForHumans(),
                'created' => $ticket->created_at->format('M j, Y'),
                'description' => $ticket->description,
                'messages' => [[
                    'author' => $ticket->customer_name,
                    'role' => 'Customer',
                    'text' => $ticket->description,
                    'time' => $ticket->created_at->format('g:i A'),
                ]],
            ])
            ->all();

        $tickets = array_merge($databaseTickets, require resource_path('data/tickets.php'));

        return view('admin.tickets.index', compact('tickets'));
    }
}
