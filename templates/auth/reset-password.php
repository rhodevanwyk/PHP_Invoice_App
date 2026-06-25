<?php $title = 'Reset Password'; require __DIR__ . '/../layout/header.php'; ?>
<div class="auth-card__header">
    <h1 class="text-heading">Set new password</h1>
    <p class="text-body">Choose a strong password for your account</p>
</div>
<div class="auth-card__body">
    <?php if (!empty($error)): ?>
        <div class="alert alert--error mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= url('/reset-password') ?>" class="form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="reset_token" value="<?= htmlspecialchars($token) ?>">
        <div class="form-group">
            <label class="form-label" for="password">New password</label>
            <input class="form-input" type="password" id="password" name="password" placeholder="Min. 8 characters" required minlength="8">
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm password</label>
            <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" required minlength="8">
        </div>
        <button type="submit" class="btn btn--primary btn--block">Update password</button>
    </form>
    <div class="auth-links mt-6">
        <a href="<?= url('/') ?>">Back to sign in</a>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
