<?php $title = 'Forgot Password'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Forgot Password</h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php else: ?>
    <form method="POST" action="<?= url('/forgot-password') ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <button type="submit">Send Reset Link</button>
    </form>
<?php endif; ?>
<?php require __DIR__ . '/../layout/footer.php'; ?>