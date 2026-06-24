<?php $title = 'Register';
require __DIR__ . '/../layout/header.php'; ?>

<h1>Register</h1>

<form method="POST" action="/register">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <div>
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
        <?php if (isset($errors['name'])): ?><span class="error"><?= $errors['name'] ?></span><?php endif; ?>
    </div>
    <button type="submit">Register</button>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>