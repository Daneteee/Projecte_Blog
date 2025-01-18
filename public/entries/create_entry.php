<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Obtenim les categories que ja hi ha
$categories_query = "SELECT * FROM categories ORDER BY nombre";
$categories_result = $db->query($categories_query);
$categories = [];
if ($categories_result) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<!-- Formulari per crear una nova entrada -->
<main class="main-content">
    <div class="category-form-container">
        <h2>Crear Nova Entrada</h2>
        
        <?php include __DIR__ . '/../includes/message.php'; ?>

        <!-- Formulari -->
        <form action="process_entry.php" method="POST" class="elegant-form">
            <div class="form-group">
                <label for="titol">
                    <i class="ri-text"></i> Títol de l'Entrada
                </label>
                <div class="input-wrapper">
                    <input type="text" id="titol" name="titol" 
                           placeholder="Escriu el títol de l'entrada" 
                           required 
                           minlength="2" 
                           maxlength="100">
                    <span class="input-icon"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="contingut">
                    <i class="ri-edit-line"></i> Contingut
                </label>
                <div class="input-wrapper">
                    <textarea id="contingut" name="contingut" 
                              placeholder="Escriu el contingut de l'entrada" 
                              required 
                              minlength="10" 
                              maxlength="5000" 
                              rows="5"
                              style="resize: none; width: 300px;"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="categoria_id">
                    <i class="ri-folder-line"></i> Categoria
                </label>
                <div class="input-wrapper">
                    <select id="categoria_id" name="categoria_id" required>
                        <option value="">Selecciona una categoria</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>">
                                <?= htmlspecialchars($category['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="ri-add-circle-line"></i> Crear Entrada
            </button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
