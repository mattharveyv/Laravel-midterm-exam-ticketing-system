@extends('layouts.app')
@section('title', 'Notifications — NEXA Desk')
@section('page-title', 'Notifications')
@section('content')
@php($notifications = require resource_path('data/notifications.php'))
<div class="page-heading"><div><span class="eyebrow">YOUR UPDATES</span><h1>Notifications</h1><p>See replies, assignments, and changes to your requests.</p></div><button class="btn btn-secondary" data-action="mark-all-read">Mark all as read</button></div>
<section class="panel"><div class="panel-heading notification-toolbar"><div class="tabs" role="tablist"><button class="tab active" data-notification-filter="All">All</button><button class="tab" data-notification-filter="Unread">Unread</button><button class="tab" data-notification-filter="Ticket reply">Replies</button><button class="tab" data-notification-filter="Status changed">Status changes</button></div><span id="unread-count">4 unread</span></div><div class="notification-list" id="notification-list">@foreach ($notifications as $index => $notification)<x-notification-item :notification="$notification" :index="$index"/>@endforeach</div></section>
@endsection
