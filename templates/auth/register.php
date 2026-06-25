<?php $title = 'Register'; require __DIR__ . '/../layout/header.php'; ?>
<div class="auth-card__header">
    <h1 class="text-heading">Create your account</h1>
    <p class="text-body">Start managing invoices in minutes</p>
</div>
<div class="auth-card__body">
    <form method="POST" action="<?= url('/register') ?>" class="form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div class="form-group">
            <label class="form-label" for="name">Full name</label>
            <input class="form-input" type="text" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="Jane Smith" required>
            <?php if (isset($errors['name'])): ?><span class="field-error"><?= htmlspecialchars($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input class="form-input" type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="you@company.com" required>
            <?php if (isset($errors['email'])): ?><span class="field-error"><?= htmlspecialchars($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-input" type="password" id="password" name="password" placeholder="Min. 8 characters" required>
            <?php if (isset($errors['password'])): ?><span class="field-error"><?= htmlspecialchars($errors['password']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm password</label>
            <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" required>
        </div>
        <button type="submit" class="btn btn--primary btn--block">Create account</button>
    </form>
</div>
<div class="auth-card__footer">
    <span class="text-small">Already have an account?</span>
    <a href="<?= url('/') ?>">Sign in</a>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
