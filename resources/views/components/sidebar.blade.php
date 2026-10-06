@props(['admin' => false])
@php
    $groups = $admin ? [
        ['OPERATIONS', [['/admin/dashboard', 'Overview', 'grid'], ['/admin/tickets', 'Tickets', 'ticket'], ['/admin/tickets/kanban', 'Kanban board', 'columns']]],
        ['PEOPLE', [['/admin/customers', 'Customers', 'users'], ['/admin/agents', 'Agents', 'headset'], ['/admin/teams', 'Teams', 'team']]],
        ['SERVICE', [['/admin/sla', 'SLA monitor', 'clock'], ['/admin/knowledge-base', 'Knowledge base', 'book'], ['/admin/canned-responses', 'Canned responses', 'reply'], ['/admin/reports', 'Reports', 'chart'], ['/admin/audit-logs', 'Audit log', 'activity'], ['/admin/settings', 'Settings', 'settings']]],
    ] : [
        ['WORKSPACE', [['/user/dashboard', 'Dashboard', 'grid'], ['/user/tickets', 'My tickets', 'ticket'], ['/user/tickets/create', 'Create ticket', 'plus']]],
        ['RESOURCES', [['/user/knowledge-base', 'Knowledge base', 'book'], ['/user/notifications', 'Notifications', 'bell']]],
        ['ACCOUNT', [['/user/profile', 'My profile', 'user'], ['/user/settings', 'Settings', 'settings']]],
    ];
@endphp
<aside class="sidebar" id="sidebar">
    <a class="brand" href="{{ $admin ? route('admin.dashboard') : route('user.dashboard') }}"><span class="brand-symbol">P</span><span class="brand-name"><strong>Problinx</strong><small>{{ $admin ? 'SUPPORT CONSOLE' : 'SERVICE HUB' }}</small></span><button class="sidebar-close" data-action="close-drawer" aria-label="Close menu">×</button></a>
    <div class="workspace-chip"><span class="workspace-mark">N</span><span><strong>Northstar Labs</strong><small>Workspace</small></span><span aria-hidden="true">⌄</span></div>
    <nav class="side-nav" aria-label="{{ $admin ? 'Admin' : 'User' }} navigation">
        @foreach ($groups as [$group, $items])
            <div class="nav-group"><div class="nav-label">{{ $group }}</div>
                @foreach ($items as [$url, $label, $icon])
                    <a class="nav-link {{ request()->is(ltrim($url, '/')) ? 'active' : '' }}" href="{{ $url }}"><span class="nav-icon" aria-hidden="true">{{ ['grid' => '▦', 'ticket' => '▤', 'columns' => '▥', 'users' => '♙', 'headset' => '◉', 'team' => '♧', 'clock' => '◷', 'book' => '◇', 'reply' => '↩', 'chart' => '▤', 'activity' => '◷', 'settings' => '⚙', 'plus' => '＋', 'bell' => '♧', 'user' => '◉'][$icon] }}</span>{{ $label }}@if ($label === 'My tickets')<span class="nav-count" id="sidebar-ticket-count"></span>@endif</a>
                @endforeach
            </div>
        @endforeach
        <div class="nav-group"><div class="nav-label">WORKSPACE</div><a class="nav-link" href="{{ $admin ? route('user.dashboard') : route('admin.login') }}"><span class="nav-icon">⇄</span>{{ $admin ? 'User portal' : 'Admin login' }}</a><button class="nav-link logout-link" data-action="logout"><span class="nav-icon">↗</span>Log out</button></div>
    </nav>
    <div class="sidebar-footer"><div class="sidebar-help"><span class="help-mark">?</span><span><strong>Need assistance?</strong><small>Visit the help center</small></span></div><button class="sidebar-user" data-action="profile-menu"><x-avatar name="{{ $admin ? 'Jordan Lee' : 'John Davis' }}" /><span><strong id="sidebar-name">{{ $admin ? 'Jordan Lee' : 'John Davis' }}</strong><small>{{ $admin ? 'Administrator' : 'Customer account' }}</small></span><span class="more">···</span></button></div>
</aside>
