<?php $layout = $layout ?? 'auth'; ?>
<?php if ($layout === 'app'): ?>
        </main>
    </div>
</div>
<script src="<?= url('/assets/js/app.js') ?>"></script>
<?php else: ?>
        </div>
    </main>
</div>
<?php endif; ?>
</body>
</html>
