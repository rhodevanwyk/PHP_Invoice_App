<?php $title = 'Register'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Register</h1>
<form method="POST" action="<?= url('/register') ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <div>
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
        <?php if(isset($errors['name'])): ?><span class="error"><?= htmlspecialchars($errors['name']) ?></span><?php endif; ?>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        <?php if(isset($errors['email'])): ?><span class="error"><?= htmlspecialchars($errors['email']) ?></span><?php endif; ?>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password">
        <?php if(isset($errors['password'])): ?><span class="error"><?= htmlspecialchars($errors['password']) ?></span><?php endif; ?>
    </div>
    <div>
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation">
    </div>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="<?= url('/') ?>">Login</a></p>
<?php require __DIR__ . '/../layout/footer.php'; ?>