<?php $title = 'Reset Password'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Reset Password</h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST" action="<?= url('/reset-password') ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <input type="hidden" name="reset_token" value="<?= htmlspecialchars($token) ?>">
    <div>
        <label>New Password (min 8 characters)</label>
        <input type="password" name="password" required minlength="8">
    </div>
    <div>
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required minlength="8">
    </div>
    <button type="submit">Reset Password</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>