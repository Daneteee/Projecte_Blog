<aside>
    <h2>Menú</h2>
    <ul>
        <?php if (!isset($_SESSION['user_id'])):?>
            <li>
                <h3>Iniciar Sesión</h3>

                <?php if (!empty($_SESSION['errors']['login'])): ?>
                    <p style="color:red;"><?php echo $_SESSION['errors']['login']; ?></p>
                    <?php unset($_SESSION['errors']['login']);?>
                <?php endif; ?>
                
                <form action="/user/login.php" method="POST">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" required>
                    <input type="submit" value="Iniciar sesión">
                </form>
            </li> 
            <li>
                <br><br>
                <h3>Registrar Usuario</h3>
                <?php include __DIR__ . '/../user/register.php'; ?>
            </li>
        <?php else: ?>
            <li><a href="/categories/create_category.php">Crear categoría</a></li>
            <li><a href="/entries/create_entry.php">Crear entrada</a></li>
            <li><a href="/profile/edit_profile.php">Editar perfil</a></li>
            <li><a href="/other/contact.php">Contacte</a></li>
            <li><a href="/other/about.php">Sobre nosaltres</a></li>
            <li><a href="/user/logout.php">Cerrar sesión</a></li>
        <?php endif; ?>
    </ul>
</aside>
