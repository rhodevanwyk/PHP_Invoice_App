<?php
$title = 'Add Client';
require __DIR__ . '/../layout/header.php';
?>
<div class="page-header">
    <h2 class="page-header__title">Add client</h2>
    <p class="page-header__subtitle">Create a new client for your invoices</p>
</div>

<div class="panel panel--narrow">
    <div class="panel__body">
        <form method="POST" action="<?= url('/clients') ?>" class="form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="form-group">
                <label class="form-label" for="name">Name *</label>
                <input class="form-input" type="text" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="Acme Corp" required>
                <?php if (isset($errors['name'])): ?><span class="field-error"><?= htmlspecialchars($errors['name']) ?></span><?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="form-input" type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="billing@acme.com">
                <?php if (isset($errors['email'])): ?><span class="field-error"><?= htmlspecialchars($errors['email']) ?></span><?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Address</label>
                <textarea class="form-input form-input--textarea" id="address" name="address" rows="3" placeholder="Street, city, state, zip"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone</label>
                <input class="form-input" type="text" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="+1 (555) 000-0000">
            </div>

            <div class="form-actions">
                <a href="<?= url('/clients') ?>" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--primary">Save client</button>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
