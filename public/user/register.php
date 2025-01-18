<!-- Formulari de registre -->
<form action="user/validate_register.php" method="POST">
    <label>Nom: </label><input type="text" name="nom" required>
    <!-- Mostrem l'error si existeix -->
    <span class="error"><?= $_SESSION['errors']['nom'] ?? ''; ?></span>

    <label>Cognom: </label><input type="text" name="cognom" required>
    <span class="error"><?= $_SESSION['errors']['cognom'] ?? ''; ?></span>

    <label>Email: </label><input type="email" name="email" required>
    <span class="error"><?= $_SESSION['errors']['email'] ?? ''; ?></span>

    <label>Contrasenya: </label><input type="password" name="password" required>
    <span class="error"><?= $_SESSION['errors']['password'] ?? ''; ?></span>
    
    <input type="submit" value="Registrar-se">
</form>

<?php
// Netegem errors
unset($_SESSION['errors']);
?>
