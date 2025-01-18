<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../config/db.php'; ?>

<main class="main-content">
    <div class="category-form-container">
        <h2>Crear Nova Categoria</h2>
        
        <!-- Mostrem notificacions si existeixen -->
        <?php include __DIR__ . '/../includes/message.php'; ?>

        
        <!-- Formulari per crear una nova categoria -->
        <form action="process_category.php" method="POST" class="elegant-form">
            <div class="form-group">
                <label for="nom_categoria">
                    <i class="ri-price-tag-3-line"></i> Nom de la Categoria
                </label>
                <div class="input-wrapper">
                    <input type="text" id="nom_categoria" name="nom_categoria" 
                           placeholder="Escriu el nom de la categoria" 
                           required 
                           minlength="2" 
                           maxlength="50">
                </div>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="ri-add-circle-line"></i> Crear Categoria
            </button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>