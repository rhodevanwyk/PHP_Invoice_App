<?php $title = 'Login'; require __DIR__ . '/../layout/header.php'; ?>
<div class="auth-card__header">
    <h1 class="text-heading">Welcome back</h1>
    <p class="text-body">Sign in to manage your invoices</p>
</div>
<div class="auth-card__body">
    <?php if (!empty($flash)): ?>
        <div class="alert alert--success mb-4"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert--error mb-4"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= url('/login') ?>" class="form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input class="form-input" type="email" id="email" name="email" placeholder="you@company.com" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-input" type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
            <label class="form-checkbox">
                <input type="checkbox" name="remember">
                Remember me
            </label>
        </div>
        <button type="submit" class="btn btn--primary btn--block">Sign in</button>
    </form>
    <div class="auth-links">
        <a href="<?= url('/forgot-password') ?>">Forgot password?</a>
    </div>
</div>
<div class="auth-card__footer">
    <span class="text-small">Don't have an account?</span>
    <a href="<?= url('/register') ?>">Create an account</a>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
