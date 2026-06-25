<?php
$layout = $layout ?? 'auth';
$bodyClass = $layout === 'app' ? 'layout-app' : 'layout-auth';
$userInitial = isset($user['name']) ? strtoupper(mb_substr($user['name'], 0, 1)) : 'U';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'InvoiceHub') ?> — InvoiceHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= url('/assets/css/style.css') ?>">
</head>
<body class="<?= $bodyClass ?>">
<?php if ($layout === 'app'): ?>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar__header">
            <a href="<?= url('/dashboard') ?>" class="logo">
                <span class="logo__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </span>
                InvoiceHub
            </a>
        </div>
        <nav class="sidebar__nav">
            <a href="<?= url('/dashboard') ?>" class="sidebar__link sidebar__link--active">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <span class="sidebar__link sidebar__link--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Invoices
            </span>
            <span class="sidebar__link sidebar__link--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Clients
            </span>
        </nav>
        <div class="sidebar__footer">
            <div class="user-chip">
                <div class="user-chip__avatar"><?= htmlspecialchars($userInitial) ?></div>
                <div class="user-chip__info">
                    <div class="user-chip__name"><?= htmlspecialchars($user['name'] ?? 'User') ?></div>
                    <div class="user-chip__email"><?= htmlspecialchars($user['email'] ?? '') ?></div>
                </div>
            </div>
        </div>
    </aside>
    <div class="app-main">
        <header class="topbar">
            <h1 class="topbar__title"><?= htmlspecialchars($title ?? 'Dashboard') ?></h1>
            <form method="POST" action="<?= url('/logout') ?>">
                <button type="submit" class="btn btn--ghost btn--sm">Logout</button>
            </form>
        </header>
        <main class="page-content">
<?php else: ?>
<div class="auth-shell">
    <header class="auth-header">
        <a href="<?= url('/') ?>" class="logo">
            <span class="logo__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </span>
            InvoiceHub
        </a>
    </header>
    <main class="auth-main">
        <div class="auth-card card card--elevated">
<?php endif; ?>
