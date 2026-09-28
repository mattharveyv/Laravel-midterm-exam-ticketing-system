import { store, saveStore, escapeHtml, toast, getSession } from './core.js';

const categories = ['IT Support', 'Hardware', 'Software', 'Network', 'Account', 'Billing', 'Security', 'Bug Report', 'Feature Request', 'General Inquiry'];
const idNumber = (ticketId) => Number(String(ticketId).match(/(\d+)$/)?.[1] || 0);
const nextTicketId = () => `NX-${new Date().getFullYear()}-${String(Math.max(4281, ...store.tickets.map((ticket) => idNumber(ticket.id))) + 1).padStart(6, '0')}`;

function setWizardStep(step) {
    document.querySelectorAll('[data-step]').forEach((panel) => panel.classList.toggle('active', Number(panel.dataset.step) === step));
    document.querySelectorAll('[data-step-indicator]').forEach((indicator) => {
        const number = Number(indicator.dataset.stepIndicator);
        indicator.classList.toggle('active', number === step);
        indicator.classList.toggle('complete', number < step);
    });
    if (step === 4) updateReview();
}

function updateReview() {
    const form = document.querySelector('#ticket-create-form');
    if (!form) return;
    const values = new FormData(form);
    document.querySelector('[data-review="category"]').textContent = values.get('category') || 'Choose a category';
    document.querySelector('[data-review="subject"]').textContent = values.get('subject') || '—';
    document.querySelector('[data-review="priority"]').textContent = values.get('priority') || 'Medium';
    document.querySelector('[data-review="description"]').textContent = values.get('description') || '—';
    const files = [...(document.querySelector('#ticket-files')?.files || [])].map((file) => file.name);
    document.querySelector('[data-review="attachments"]').textContent = files.length ? files.join(', ') : 'None';
}

function submitTicket(form) {
    const values = new FormData(form);
    const session = getSession() || {};
    const id = nextTicketId();
    const ticket = {
        id,
        subject: String(values.get('subject')).trim(),
        category: String(values.get('category')),
        priority: String(values.get('priority')),
        status: 'New',
        agent: 'Unassigned',
        team: String(values.get('department')),
        email: session.email || 'user@nexadesk.com',
        updated: 'Just now',
        created: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
        description: String(values.get('description')).trim(),
        device: String(values.get('device') || ''),
        location: String(values.get('location') || ''),
        attachments: [...(document.querySelector('#ticket-files')?.files || [])].map((file) => file.name),
        messages: [{ author: session.name || 'John Davis', role: 'Customer', text: String(values.get('description')).trim(), time: 'Just now' }],
    };
    store.tickets.unshift(ticket);
    saveStore();
    sessionStorage.setItem('nexadesk-last-ticket', JSON.stringify(ticket));
    window.location.assign(`/user/tickets/submitted?id=${encodeURIComponent(id)}`);
}

const createForm = document.querySelector('#ticket-create-form');
if (createForm) {
    document.addEventListener('click', (event) => {
        const next = event.target.closest('[data-next-step]');
        const previous = event.target.closest('[data-prev-step]');
        if (next) {
            const current = Number(next.closest('.wizard-panel').dataset.step);
            if (current === 1 && !createForm.querySelector('[name="category"]:checked')) {
                toast('Choose a category to continue.', 'error');
                return;
            }
            if (current === 2) {
                const subject = createForm.querySelector('[name="subject"]');
                const description = createForm.querySelector('[name="description"]');
                if (!subject.reportValidity() || !description.reportValidity()) return;
            }
            setWizardStep(Number(next.dataset.nextStep));
        }
        if (previous) setWizardStep(Number(previous.dataset.prevStep));
    });
    document.querySelector('#ticket-files')?.addEventListener('change', (event) => {
        const preview = document.querySelector('#attachment-preview');
        preview.innerHTML = [...event.target.files].map((file) => `<div class="attachment-item"><span>▧ ${escapeHtml(file.name)}</span><small>${(file.size / 1024).toFixed(0)} KB</small></div>`).join('');
    });
    createForm.addEventListener('submit', (event) => {
        event.preventDefault();
        if (!createForm.querySelector('[name="category"]:checked')) {
            setWizardStep(1);
            return toast('Choose a ticket category first.', 'error');
        }
        if (!createForm.reportValidity()) return;
        submitTicket(createForm);
    });
}

if (window.location.pathname === '/user/tickets/submitted') {
    let ticket = null;
    try { ticket = JSON.parse(sessionStorage.getItem('nexadesk-last-ticket') || 'null'); } catch { ticket = null; }
    const queryId = new URLSearchParams(window.location.search).get('id');
    ticket = store.tickets.find((item) => item.id === queryId) || ticket;
    if (ticket) {
        document.querySelector('#success-ticket-id').textContent = ticket.id;
        document.querySelector('#success-category').textContent = ticket.category;
        document.querySelector('#success-priority').textContent = ticket.priority;
        document.querySelector('#success-team').textContent = ticket.team;
        document.querySelector('#success-view-ticket').href = `/user/tickets/${encodeURIComponent(ticket.id)}`;
    }
}

function renderTicketList() {
    const container = document.querySelector('#my-ticket-list');
    if (!container) return;
    const query = document.querySelector('#ticket-search')?.value.toLowerCase() || '';
    const status = document.querySelector('#ticket-status-filter')?.value || '';
    const priority = document.querySelector('#ticket-priority-filter')?.value || '';
    const session = getSession() || {};
    const visible = store.tickets.filter((ticket) => ticket.email === (session.email || 'user@nexadesk.com'))
        .filter((ticket) => `${ticket.id} ${ticket.subject} ${ticket.category}`.toLowerCase().includes(query))
        .filter((ticket) => !status || ticket.status === status)
        .filter((ticket) => !priority || ticket.priority === priority);
    container.innerHTML = visible.map((ticket) => `<article class="ticket-card" data-ticket-card data-status="${escapeHtml(ticket.status)}" data-priority="${escapeHtml(ticket.priority)}"><div class="ticket-card-top"><a class="ticket-id" href="/user/tickets/${encodeURIComponent(ticket.id)}">${escapeHtml(ticket.id)}</a><span class="badge badge-status-${ticket.status.toLowerCase().replaceAll(' ', '-')}">${escapeHtml(ticket.status)}</span></div><a class="ticket-card-title" href="/user/tickets/${encodeURIComponent(ticket.id)}">${escapeHtml(ticket.subject)}</a><div class="ticket-card-meta"><span>${escapeHtml(ticket.category)}</span><span class="badge badge-priority-${ticket.priority.toLowerCase()}">${escapeHtml(ticket.priority)}</span></div><div class="ticket-card-footer"><span>Assigned: ${escapeHtml(ticket.agent)}</span><span>${escapeHtml(ticket.updated)}</span></div></article>`).join('');
    const empty = document.querySelector('.ticket-card-list + .empty-state');
    if (empty) empty.hidden = visible.length > 0;
}

['ticket-search', 'ticket-status-filter', 'ticket-priority-filter'].forEach((id) => {
    const control = document.getElementById(id);
    control?.addEventListener(id === 'ticket-search' ? 'input' : 'change', renderTicketList);
});
if (document.querySelector('#my-ticket-list')) renderTicketList();

function renderTicketDetail() {
    const shell = document.querySelector('#app-shell[data-ticket-id]');
        if (!shell?.dataset.ticketId) return null;
    const ticketId = decodeURIComponent(shell.dataset.ticketId || '');
    const ticket = store.tickets.find((item) => item.id === ticketId) || store.tickets[0];
    if (!ticket) return null;
    document.querySelector('#detail-subject').textContent = ticket.subject;
    document.querySelector('#detail-id').textContent = ticket.id;
    document.querySelector('#detail-status').innerHTML = `<span class="badge badge-status-${ticket.status.toLowerCase().replaceAll(' ', '-')}">${escapeHtml(ticket.status)}</span>`;
    const sideStatus = document.querySelector('#detail-status-side');
    if (sideStatus) sideStatus.innerHTML = `<span class="badge badge-status-${ticket.status.toLowerCase().replaceAll(' ', '-')}">${escapeHtml(ticket.status)}</span>`;
    const text = (selector, value) => { const element = document.querySelector(selector); if (element) element.textContent = value; };
    text('#detail-priority', ticket.priority);
    text('#detail-category', ticket.category);
    text('#detail-agent', ticket.agent);
    text('#detail-team', ticket.team);
    text('#detail-updated', ticket.updated);
    text('#detail-customer', ticket.email === 'user@nexadesk.com' ? 'John Davis' : ticket.email.split('@')[0]);
    text('#detail-email', ticket.email);
    const conversation = document.querySelector('#ticket-conversation');
    if (conversation) conversation.innerHTML = ticket.messages.map((message) => {
        const role = message.role === 'Customer' ? 'customer' : message.role === 'System' ? 'system' : 'agent';
        const initials = message.author.split(' ').map((part) => part[0]).slice(0, 2).join('');
        return `<div class="conversation-message ${role}">${role !== 'system' ? `<span class="avatar">${escapeHtml(initials)}</span>` : ''}<div class="message-content"><div class="message-meta"><strong>${escapeHtml(message.author)}</strong><span>${escapeHtml(message.time)}</span></div><div class="message-bubble">${escapeHtml(message.text)}</div></div></div>`;
    }).join('');
    const closeButton = document.querySelector('[data-action="close-ticket"]');
    const reopenButton = document.querySelector('[data-action="reopen-ticket"]');
    if (closeButton) closeButton.hidden = ['Closed', 'Resolved'].includes(ticket.status);
    if (reopenButton) reopenButton.hidden = !['Closed', 'Resolved'].includes(ticket.status);
    return ticket;
}

const detailTicket = renderTicketDetail();
const replyForm = document.querySelector('#reply-form');
replyForm?.addEventListener('submit', (event) => {
    event.preventDefault();
    const input = document.querySelector('#reply-message');
    const text = input.value.trim();
    if (!text || !detailTicket) return;
    const session = getSession() || {};
    detailTicket.messages.push({ author: session.name || 'John Davis', role: 'Customer', text, time: 'Just now' });
    detailTicket.status = 'Waiting';
    detailTicket.updated = 'Just now';
    saveStore();
    input.value = '';
    renderTicketDetail();
    toast('Your reply was added to the conversation.');
});

document.addEventListener('click', (event) => {
    const action = event.target.closest('[data-action]')?.dataset.action;
    if (action === 'insert-emoji') {
        const input = document.querySelector('#reply-message');
        if (input) { input.value += ' 🙂'; input.focus(); }
    }
    if (action === 'close-ticket' || action === 'reopen-ticket') {
        if (!detailTicket) return;
        detailTicket.status = action === 'close-ticket' ? 'Closed' : 'Open';
        detailTicket.updated = 'Just now';
        saveStore();
        renderTicketDetail();
        toast(`Ticket ${detailTicket.status.toLowerCase()}.`);
    }
    const markRead = event.target.closest('[data-action="mark-read"]');
    if (markRead) {
        const item = store.notifications[Number(markRead.dataset.index)];
        if (item) { item.read = true; saveStore(); markRead.closest('[data-notification]')?.classList.remove('unread'); markRead.remove(); }
    }
    if (action === 'mark-all-read') {
        store.notifications.forEach((item) => { item.read = true; });
        saveStore();
        document.querySelectorAll('[data-notification]').forEach((item) => item.classList.remove('unread'));
        toast('All notifications marked as read.');
    }
});

document.querySelectorAll('[data-notification-filter]').forEach((button) => button.addEventListener('click', () => {
    document.querySelectorAll('[data-notification-filter]').forEach((item) => item.classList.toggle('active', item === button));
    document.querySelectorAll('[data-notification]').forEach((item) => {
        const notification = store.notifications[Number(item.dataset.index)];
        const filter = button.dataset.notificationFilter;
        item.hidden = filter === 'Unread' ? notification.read : filter !== 'All' && notification.type !== filter;
    });
}));
