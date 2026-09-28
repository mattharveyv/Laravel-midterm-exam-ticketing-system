@extends('layouts.app')
@section('title', 'Kanban Board — NEXA Desk')
@section('page-title', 'Kanban board')
@section('content')
<div class="page-heading"><div><span class="eyebrow">WORK IN MOTION</span><h1>Ticket kanban</h1><p>Drag requests between stages to update their status.</p></div><a class="btn btn-secondary" href="{{ route('admin.tickets') }}">← Ticket table</a></div>
<div class="kanban-board" id="kanban-board">
	@foreach ($lanes as $status => $laneTickets)
		<section class="kanban-column" data-kanban-column="{{ $status }}">
			<div class="kanban-column-head"><strong>{{ strtoupper($status) }}</strong><span>{{ count($laneTickets) }}</span></div>
			<div class="kanban-dropzone" data-drop-status="{{ $status }}">
				@foreach ($laneTickets as $ticket)
					<article class="kanban-card" draggable="true" data-kanban-ticket="{{ $ticket['id'] }}">
						<a href="{{ url('/admin/tickets/'.rawurlencode($ticket['id'])) }}">{{ $ticket['id'] }}</a>
						<h3>{{ $ticket['subject'] }}</h3>
						<div><x-priority-badge :priority="$ticket['priority']"/><small>{{ $ticket['team'] }}</small></div>
						<footer><x-avatar :name="$ticket['agent'] === 'Unassigned' ? 'NEXA' : $ticket['agent']" size="tiny"/><small>{{ $ticket['agent'] }}</small></footer>
					</article>
				@endforeach
			</div>
		</section>
	@endforeach
</div>
@endsection
