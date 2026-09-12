<?php
$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);
?>
<?php if ($notification): ?>
    <div class="notification <?= $notification['type'] === 'error' ? 'notification-error' : 'notification-success' ?>" role="alert">
        <span><?= htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8') ?></span>
        <button type="button" aria-label="Cerrar notificación">&times;</button>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/notification.js" defer></script>
<?php endif; ?>
