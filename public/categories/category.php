<?php 
if (!isset($_SESSION)) {
    session_start();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../config/db.php';

// Comprovem que hi hagi una ID de categoria
$categoria_id = isset($_GET['id']) ? $_GET['id'] : null;

// Obtenim el nom de la categoria
$query_categoria = "SELECT nombre FROM categories WHERE id = ?";
$stmt_categoria = $db->prepare($query_categoria);
$stmt_categoria->bind_param("i", $categoria_id);
$stmt_categoria->execute();
$result_categoria = $stmt_categoria->get_result();


$categoria = $result_categoria->fetch_assoc();

// Obtenim les entrades i la ID de l'usuari que les crea d'aquesta categoría
$query_entrades = "SELECT e.id, e.titol, e.descripcio, e.data, e.usuari_id, u.nom AS usuari_nom
                   FROM entrades e
                   INNER JOIN usuaris u ON e.usuari_id = u.id
                   WHERE e.categoria_id = ?
                   ORDER BY e.data DESC";
$stmt_entrades = $db->prepare($query_entrades);
$stmt_entrades->bind_param("i", $categoria_id);
$stmt_entrades->execute();
$result_entrades = $stmt_entrades->get_result();
?>

<!-- Mostrem els resultats -->
<div class="main-content container">
    <h1 class="page-title">Entrades de la Categoria: <?php echo htmlspecialchars($categoria['nombre']); ?></h1>

    <!-- En cas que hi hagin entrades les mostrem -->
    <?php if ($result_entrades->num_rows > 0): ?>
        <?php while ($entrada = $result_entrades->fetch_assoc()): ?>

            <!-- Entrada -->
            <div class="blog-entry">
                <div class="blog-entry-header">
                    <h2 class="blog-entry-title"><a href="/entries/entry_details.php?id=<?php echo $entrada['id']; ?>" style="text-decoration: none; color: inherit;">
                        <?php echo htmlspecialchars($entrada['titol']); ?>
                    </a></h2>
                    <div class="blog-entry-meta">
                        <span class="meta-info">
                            <i class="icon-user"></i> <?php echo htmlspecialchars($entrada['usuari_nom']); ?> | 
                            <i class="icon-calendar"></i> <?php echo date('d/m/Y', strtotime($entrada['data'])); ?>
                        </span>
                        <?php 

                        // En cas que l'usuari sigui l'autor, fem que pugui editar i eliminar
                        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $entrada['usuari_id']) {
                            ?>
                            <div class="entry-actions">
                                <a href="../entries/edit_entry.php?id=<?php echo $entrada['id']; ?>" class="btn btn-edit">
                                    <i class="icon-edit"></i> Editar
                                </a>
                                <a href="../entries/delete_entry.php?id=<?php echo $entrada['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Estàs segur que vols eliminar aquesta entrada?')">
                                   <i class="icon-delete"></i> Eliminar
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="blog-entry-body">
                    <p class="card-text" style="overflow: hidden; text-overflow: ellipsis;">
                    <?php echo nl2br(htmlspecialchars($entrada['descripcio'])); ?>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-entries">No hi ha entrades en aquesta categoria.</div>
    <?php endif; ?>
</div>

<?php 
$db->close();
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/footer.php';
?>
