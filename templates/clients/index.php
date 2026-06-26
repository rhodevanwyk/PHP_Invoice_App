<?php
$title = 'Clients';
require __DIR__ . '/../layout/header.php';
?>
<?php if (!empty($flash)): ?>
    <div class="alert alert--<?= htmlspecialchars($flash['type']) === 'error' ? 'error' : 'success' ?> mb-6" role="status">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="page-header page-header--row">
    <div>
        <h2 class="page-header__title">Clients</h2>
        <p class="page-header__subtitle">Manage the people and companies you invoice</p>
    </div>
    <a href="<?= url('/clients/create') ?>" class="btn btn--primary btn--sm page-header__action">Add client</a>
</div>

<div class="panel">
    <div class="panel__header">
        <h3 class="panel__title">All clients</h3>
        <?php if (!empty($clients)): ?>
            <span class="text-small"><?= (int) $pagination['total'] ?> total</span>
        <?php endif; ?>
    </div>
    <div class="panel__body panel__body--flush">
        <?php if (empty($clients)): ?>
            <div class="empty-state">
                <div class="empty-state__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h4 class="empty-state__title">No clients yet</h4>
                <p class="empty-state__text">Add your first client to start creating invoices for them.</p>
                <a href="<?= url('/clients/create') ?>" class="btn btn--primary">Add your first client</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="data-table__actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                        <tr>
                            <td data-label="Name">
                                <span class="data-table__primary"><?= htmlspecialchars($client['name']) ?></span>
                                <?php if (!empty($client['address'])): ?>
                                    <span class="data-table__secondary"><?= htmlspecialchars($client['address']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Email"><?= htmlspecialchars($client['email'] ?? '—') ?></td>
                            <td data-label="Phone"><?= htmlspecialchars($client['phone'] ?? '—') ?></td>
                            <td data-label="Actions">
                                <div class="table-actions">
                                    <a href="<?= url('/clients/' . $client['id'] . '/edit') ?>" class="btn btn--ghost btn--sm">Edit</a>
                                    <a href="<?= url('/clients/' . $client['id'] . '/delete') ?>" class="btn btn--danger btn--sm">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pagination['lastPage'] > 1): ?>
            <div class="pagination">
                <?php if ($pagination['page'] > 1): ?>
                    <a href="<?= url('/clients?page=' . ($pagination['page'] - 1)) ?>" class="btn btn--secondary btn--sm">Previous</a>
                <?php else: ?>
                    <span class="btn btn--secondary btn--sm is-disabled">Previous</span>
                <?php endif; ?>

                <span class="pagination__info">Page <?= (int) $pagination['page'] ?> of <?= (int) $pagination['lastPage'] ?></span>

                <?php if ($pagination['page'] < $pagination['lastPage']): ?>
                    <a href="<?= url('/clients?page=' . ($pagination['page'] + 1)) ?>" class="btn btn--secondary btn--sm">Next</a>
                <?php else: ?>
                    <span class="btn btn--secondary btn--sm is-disabled">Next</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
