@extends('layouts.app')
@section('title', 'My Tickets — NEXA Desk')
@section('page-title', 'My tickets')
@section('content')
@php($tickets = array_values(array_filter(require resource_path('data/tickets.php'), fn ($ticket) => $ticket['email'] === 'user@nexadesk.com')))
<div class="page-heading"><div><span class="eyebrow">YOUR SUPPORT HISTORY</span><h1>My tickets</h1><p>Keep track of every request and support conversation.</p></div><x-button href="{{ route('user.tickets.create') }}" variant="primary">＋ Create ticket</x-button></div>
<div class="ticket-filter-row"><x-search-bar id="ticket-search" placeholder="Search ticket number or subject"/><x-filter-dropdown id="ticket-status-filter" label="All statuses" :options="['New', 'Open', 'In Progress', 'Waiting', 'Resolved', 'Closed']"/><x-filter-dropdown id="ticket-priority-filter" label="All priorities" :options="['Low', 'Medium', 'High', 'Critical']"/><button class="filter-mobile-button" data-action="open-filters">Filters <span>⌄</span></button></div>
<div class="ticket-card-list" id="my-ticket-list">@foreach ($tickets as $ticket)<x-ticket-card :ticket="$ticket"/>@endforeach</div><x-empty-state title="No tickets found" description="Try changing your search or filters."/>
@endsection
