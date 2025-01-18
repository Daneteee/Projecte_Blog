<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="main-content">
    <div class="category-form-container">
        <h2>Contacte</h2>

        <?php if ($message): ?>
            <div class="alert <?= $message['type'] ?>">
                <?= htmlspecialchars($message['text'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="mail.php" method="POST" class="elegant-form">
            <div class="form-group">
                <label for="name">
                    <i class="ri-user-line"></i> Nom:
                </label>
                <div class="input-wrapper">
                    <input type="text" id="name" name="name" 
                        placeholder="El teu nom" 
                        required minlength="2" maxlength="100">
                    <span class="input-icon"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="ri-mail-line"></i> Email:
                </label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" 
                        placeholder="El teu correu electrònic" 
                        required>
                    <span class="input-icon"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="message">
                    <i class="ri-message-line"></i> Mensaje:
                </label>
                <div class="input-wrapper">
                    <textarea id="message" name="message" 
                            placeholder="Escriu un missatge!" 
                            required minlength="10" maxlength="500" 
                            rows="5" style="resize: none; width: 300px;"></textarea>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="ri-send-plane-line"></i> Enviar
            </button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
