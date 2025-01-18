<?php 
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $iconClass = match($message['type']) {
        'success' => 'ri-checkbox-circle-line text-green',
        'error' => 'ri-close-circle-line text-red',
        'warning' => 'ri-warning-line text-orange',
        default => ''
    };
    ?>
    <div class="notification <?= $message['type'] ?>">
        <i class="<?= $iconClass ?> notification-icon"></i>
        <span><?= htmlspecialchars($message['text']) ?></span>
    </div>
    <?php 
    unset($_SESSION['message']); 
} 
?>