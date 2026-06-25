<?php $title = 'Forgot Password'; require __DIR__ . '/../layout/header.php'; ?>
<div class="auth-card__header">
    <h1 class="text-heading">Reset password</h1>
    <p class="text-body">Enter your email and we'll send you a reset link</p>
</div>
<div class="auth-card__body">
    <?php if (!empty($error)): ?>
        <div class="alert alert--error mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert--success"><?= htmlspecialchars($success) ?></div>
    <?php else: ?>
        <form method="POST" action="<?= url('/forgot-password') ?>" class="form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="form-input" type="email" id="email" name="email" placeholder="you@company.com" required>
            </div>
            <button type="submit" class="btn btn--primary btn--block">Send reset link</button>
        </form>
    <?php endif; ?>
    <div class="auth-links mt-6">
        <a href="<?= url('/') ?>">Back to sign in</a>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
