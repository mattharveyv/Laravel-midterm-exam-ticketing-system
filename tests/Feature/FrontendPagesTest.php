<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_landing_and_auth_pages_render(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('Support made');
        $this->get(route('login'))->assertOk()->assertSee('Welcome back');
        $this->get(route('register'))->assertOk()->assertSee('Create your account');
        $this->get(route('forgot-password'))->assertOk()->assertSee('Forgot your password');
        $this->get(route('admin.login'))->assertOk()->assertSee('Admin sign in');
    }

    public function test_user_ticket_and_support_pages_render(): void
    {
        $paths = [
            route('user.dashboard'),
            route('user.tickets'),
            route('user.tickets.create'),
            route('user.tickets.submitted'),
            route('user.tickets.show', ['ticketId' => 'NX-2026-004281']),
            route('user.knowledge'),
            route('user.knowledge.show', ['slug' => 'article-1']),
            route('user.notifications'),
            route('user.profile'),
            route('user.settings'),
        ];

        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_admin_support_pages_render(): void
    {
        $paths = [
            route('admin.dashboard'),
            route('admin.tickets'),
            route('admin.kanban'),
            route('admin.tickets.show', ['ticketId' => 'NX-2026-004281']),
            route('admin.customers'),
            route('admin.customers.show', ['customerId' => 'user@nexadesk.com']),
            route('admin.agents'),
            route('admin.agents.show', ['agentId' => 'alex@nexadesk.com']),
            route('admin.teams'),
            route('admin.sla'),
            route('admin.knowledge-base'),
            route('admin.canned-responses'),
            route('admin.reports'),
            route('admin.audit-logs'),
            route('admin.settings'),
        ];

        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }
    }
}
