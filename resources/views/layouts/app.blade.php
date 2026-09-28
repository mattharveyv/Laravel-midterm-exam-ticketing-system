@extends('layouts.base')

@section('body')
    @php($isAdmin = request()->is('admin/*'))
    <div class="app-shell" id="app-shell" data-area="{{ $isAdmin ? 'admin' : 'user' }}" data-ticket-id="@yield('ticket-id')">
        <x-sidebar :admin="$isAdmin" />
        <div class="app-main">
            <x-navbar :admin="$isAdmin" />
            <main class="page-content" id="page-content">
                @yield('content')
            </main>
        </div>
        <div class="drawer-scrim" data-action="close-drawer"></div>
    </div>
@endsection
