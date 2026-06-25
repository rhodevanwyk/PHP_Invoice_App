<?php
$title = 'Dashboard';
$layout = 'app';
require __DIR__ . '/layout/header.php';
?>
<div class="page-header">
    <h2 class="page-header__title">Good <?= date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') ?>, <?= htmlspecialchars($user['name'] ?? 'there') ?></h2>
    <p class="page-header__subtitle">Here's an overview of your invoicing activity</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__label">Total Revenue</div>
        <div class="stat-card__value">$0.00</div>
        <div class="stat-card__change">No invoices yet</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Outstanding</div>
        <div class="stat-card__value">$0.00</div>
        <div class="stat-card__change">0 pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Paid Invoices</div>
        <div class="stat-card__value">0</div>
        <div class="stat-card__change">This month</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__label">Clients</div>
        <div class="stat-card__value">0</div>
        <div class="stat-card__change">Active accounts</div>
    </div>
</div>

<div class="panel">
    <div class="panel__header">
        <h3 class="panel__title">Recent Invoices</h3>
        <button class="btn btn--secondary btn--sm" disabled>New Invoice</button>
    </div>
    <div class="panel__body">
        <div class="empty-state">
            <div class="empty-state__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <h4 class="empty-state__title">No invoices yet</h4>
            <p class="empty-state__text">Create your first invoice to start tracking payments and growing your business.</p>
            <button class="btn btn--primary" disabled>Create your first invoice</button>
        </div>
    </div>
</div>
<?php require __DIR__ . '/layout/footer.php'; ?>
