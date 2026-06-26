<?php
$title = 'Delete Client';
require __DIR__ . '/../layout/header.php';
?>
<div class="page-header">
    <h2 class="page-header__title">Delete client</h2>
    <p class="page-header__subtitle">This action cannot be undone</p>
</div>

<div class="panel panel--narrow">
    <div class="panel__body">
        <?php if (!empty($hasInvoices)): ?>
            <div class="alert alert--error mb-6" role="alert">
                <strong><?= htmlspecialchars($client['name']) ?></strong> has linked invoices and cannot be deleted. Remove or reassign those invoices first.
            </div>
            <div class="form-actions">
                <a href="<?= url('/clients') ?>" class="btn btn--primary">Back to clients</a>
            </div>
        <?php else: ?>
            <div class="alert alert--error mb-6" role="alert">
                Are you sure you want to delete <strong><?= htmlspecialchars($client['name']) ?></strong>?
            </div>

            <form method="POST" action="<?= url('/clients/' . $client['id'] . '/delete') ?>" class="form-actions">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <a href="<?= url('/clients') ?>" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--danger">Yes, delete client</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
