<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

Route::view('/', 'landing')->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/forgot-password', 'auth.forgot-password')->name('forgot-password');
Route::view('/admin/login', 'auth.admin-login')->name('admin.login');

Route::view('/user/dashboard', 'user.dashboard')->name('user.dashboard');
Route::view('/user/tickets', 'user.tickets.index')->name('user.tickets');
Route::view('/user/tickets/create', 'user.tickets.create')->name('user.tickets.create');
Route::post('/user/tickets', [TicketController::class, 'store'])->name('user.tickets.store');
Route::view('/user/tickets/submitted', 'user.tickets.submitted')->name('user.tickets.submitted');
Route::get('/user/tickets/{ticketId}', static function (string $ticketId): View {
    return view('user.tickets.show', ['ticketId' => $ticketId]);
})->name('user.tickets.show');
Route::view('/user/knowledge-base', 'user.knowledge-base.index')->name('user.knowledge');
Route::get('/user/knowledge-base/{slug}', static function (string $slug): View {
    return view('user.knowledge-base.show', ['slug' => $slug]);
})->name('user.knowledge.show');
Route::view('/user/notifications', 'user.notifications')->name('user.notifications');
Route::view('/user/profile', 'user.profile')->name('user.profile');
Route::view('/user/settings', 'user.settings')->name('user.settings');

Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets');
Route::get('/admin/tickets/kanban', static function (): View {
    $tickets = require resource_path('data/tickets.php');
    $lanes = array_fill_keys(['New', 'Open', 'In Progress', 'Waiting', 'Escalated', 'Resolved'], []);

    foreach ($tickets as $ticket) {
        if (isset($lanes[$ticket['status']])) {
            $lanes[$ticket['status']][] = $ticket;
        }
    }

    return view('admin.tickets.kanban', [
        'lanes' => $lanes,
    ]);
})->name('admin.kanban');
Route::get('/admin/tickets/{ticketId}', static function (string $ticketId): View {
    return view('admin.tickets.show', ['ticketId' => $ticketId]);
})->name('admin.tickets.show');
Route::view('/admin/customers', 'admin.customers.index')->name('admin.customers');
Route::get('/admin/customers/{customerId}', static function (string $customerId): View {
    return view('admin.customers.show', ['customerId' => $customerId]);
})->name('admin.customers.show');
Route::view('/admin/agents', 'admin.agents.index')->name('admin.agents');
Route::get('/admin/agents/{agentId}', static function (string $agentId): View {
    return view('admin.agents.show', ['agentId' => $agentId]);
})->name('admin.agents.show');
Route::view('/admin/teams', 'admin.teams.index')->name('admin.teams');
Route::view('/admin/sla', 'admin.sla.index')->name('admin.sla');
Route::view('/admin/knowledge-base', 'admin.knowledge-base.index')->name('admin.knowledge-base');
Route::view('/admin/canned-responses', 'admin.canned-responses.index')->name('admin.canned-responses');
Route::view('/admin/reports', 'admin.reports.index')->name('admin.reports');
Route::view('/admin/audit-logs', 'admin.audit-logs.index')->name('admin.audit-logs');
Route::view('/admin/settings', 'admin.settings.index')->name('admin.settings');
