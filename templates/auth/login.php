<?php $title = 'Login'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Login</h1>
<?php if (!empty($flash)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>
<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($errors['general']) ?></div>
<?php endif; ?>
<form method="POST" action="<?= url('/login') ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <div>
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label>
            <input type="checkbox" name="remember"> Remember me
        </label>
    </div>
    <button type="submit">Login</button>
</form>
<p><a href="<?= url('/forgot-password') ?>">Forgot password?</a></p>
<p>Don't have an account? <a href="<?= url('/register') ?>">Register</a></p>
<?php require __DIR__ . '/../layout/footer.php'; ?>