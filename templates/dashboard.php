<?php $title = 'Dashboard'; require __DIR__ . '/layout/header.php'; ?>
<h1>Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($user['name'] ?? 'User') ?>!</p>
<form method="POST" action="<?= url('/logout') ?>">
    <button type="submit">Logout</button>
</form>
<?php require __DIR__ . '/layout/footer.php'; ?>