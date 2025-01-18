<?php 

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../config/db.php'; 

$user_id = $_SESSION['user_id'];

// Preparem una consulta per agafar el nom i l'email de l'usuari
$query = "SELECT nom, email FROM usuaris WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>


<!-- Formulari d'ediciṕ -->
<main class="main-content">
    <div class="category-form-container">
        <h2>Editar Perfil</h2>
        
        <?php include __DIR__ . '/../includes/message.php'; 
        
        if (!empty($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach ($_SESSION['errors'] as $field => $error): ?>
                    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <form action="process_edit_profile.php" method="POST" class="elegant-form" style= "max-width: 800px;">
            
            <!-- Nom -->
            <div class="form-group">
                <label for="nom">
                    <i class="ri-user-line"></i> Nom
                </label>
                <div class="input-wrapper">
                    <input type="text" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($user['nom']); ?>" 
                           placeholder="Escriu el teu nom" 
                           required>
                    <span class="input-icon"></span>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">
                    <i class="ri-mail-line"></i> Correu Electrònic
                </label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" 
                        value="<?php echo htmlspecialchars($user['email']); ?>" 
                        placeholder="Escriu el teu correu electrònic" 
                        required>
                    <?php if (isset($_SESSION['errors']['email'])): ?>
                        <span class="error-message"><?= htmlspecialchars($_SESSION['errors']['email']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contrasenya actual -->
            <div class="form-group">
                <label for="current_password">
                    <i class="ri-lock-line"></i> Contrasenya Actual
                </label>
                <div class="input-wrapper">
                    <input type="password" id="current_password" name="current_password" 
                        placeholder="Escriu la contrasenya actual">
                    <?php if (isset($_SESSION['errors']['current_password'])): ?>
                        <span class="error-message"><?= htmlspecialchars($_SESSION['errors']['current_password']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Nova contrasenya -->
            <div class="form-group">
                <label for="password">
                    <i class="ri-lock-line"></i> Nova Contrasenya (opcional)
                </label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" 
                           placeholder="Escriu la nova contrasenya">
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="ri-save-line"></i> Actualitzar
            </button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>