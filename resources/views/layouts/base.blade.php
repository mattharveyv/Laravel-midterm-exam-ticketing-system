<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f7f6">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Problinx — Smart Ticketing & Service Hub')</title>
    <meta name="description" content="Create, track, and manage support tickets with Problinx.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @yield('body')
    <div id="modal-root"></div>
    <x-toast />
    @php
        $nexaSeed = [
            'tickets' => require resource_path('data/tickets.php'),
            'users' => require resource_path('data/users.php'),
            'agents' => require resource_path('data/agents.php'),
            'teams' => require resource_path('data/teams.php'),
            'articles' => require resource_path('data/articles.php'),
            'notifications' => require resource_path('data/notifications.php'),
            'cannedResponses' => require resource_path('data/canned-responses.php'),
        ];
    @endphp
    <script>window.NEXA_SEED = @json($nexaSeed);</script>
    @if (session()->has('submitted_ticket'))
        <script>window.NEXA_SUBMITTED_TICKET = @json(session('submitted_ticket'));</script>
    @endif
</body>
</html>
