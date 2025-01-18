<?php include __DIR__ . '/../includes/header.php'; ?>

<?php 
if (!isset($_SESSION)) {
    session_start();
}

require __DIR__ . '/../config/db.php'; 

// Obtenim el nombre total d'entrades
$query_total = "SELECT COUNT(*) AS total FROM entrades";
$result_total = $db->query($query_total);
$row_total = $result_total->fetch_assoc();
$total_entries = $row_total['total'];

// Configurem la paginació
$entries_per_page = 3; 
$total_pages = ceil($total_entries / $entries_per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages)); 
$offset = ($current_page - 1) * $entries_per_page;

// Consultem les entrades de la pagina acutal
$query = "SELECT e.id, e.titol, e.descripcio, e.data, u.nom AS usuari_nom, c.nombre AS categoria_nombre
          FROM entrades e
          INNER JOIN usuaris u ON e.usuari_id = u.id
          INNER JOIN categories c ON e.categoria_id = c.id
          ORDER BY e.data DESC
          LIMIT $offset, $entries_per_page";

$result = $db->query($query);
?>

<div class="main-content container">
    <h1 class="page-title">Totes les Entrades</h1>
    <?php 
    if ($result->num_rows > 0) {
        while ($entrada = $result->fetch_assoc()) {
            ?>
            <!-- Mostrem les entrades -->
            <div class="blog-entry">
                    <div class="blog-entry-header">
                        <h2 class="blog-entry-title"><a href="entry_details.php?id=<?php echo $entrada['id']; ?>" style="text-decoration: none; color: inherit;">
                            <?php echo htmlspecialchars($entrada['titol']); ?>
                        </a></h2>
                        <div class="blog-entry-meta">
                            <span class="meta-info">
                                <i class="icon-category"></i> <?php echo htmlspecialchars($entrada['categoria_nombre']); ?> | 
                                <i class="icon-user"></i> <?php echo htmlspecialchars($entrada['usuari_nom']); ?> | 
                                <i class="icon-calendar"></i> <?php echo date('d/m/Y', strtotime($entrada['data'])); ?>
                            </span>
                            <?php 
                            if (isset($_SESSION['user_id'])) {
                                $query_autor = "SELECT usuari_id FROM entrades WHERE id = " . $entrada['id'];
                                $result_autor = $db->query($query_autor);
                                $autor = $result_autor->fetch_assoc();

                                if ($autor['usuari_id'] == $_SESSION['user_id']) {
                                    ?>
                                    <div class="entry-actions">
                                        <a href="edit_entry.php?id=<?php echo $entrada['id']; ?>" class="btn btn-edit">
                                            <i class="icon-edit"></i> Editar
                                        </a>
                                        <a href="delete_entry.php?id=<?php echo $entrada['id']; ?>" 
                                        class="btn btn-delete" 
                                        onclick="return confirm('Estàs segur que vols eliminar aquesta entrada?')">
                                        <i class="icon-delete"></i> Eliminar
                                        </a>
                                    </div>
                                    <?php
                                }
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
            <?php
        }
    } else {
        echo "<div class='no-entries'>Encara no hi han entrades.</div>"; 
    }
    ?>

    <!-- Paginació -->
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="all_entries.php?page=<?php echo $current_page - 1; ?>" class="btn-pagination">Anterior</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="all_entries.php?page=<?php echo $i; ?>" 
               class="btn-pagination <?php echo $i === $current_page ? 'active' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($current_page < $total_pages): ?>
            <a href="all_entries.php?page=<?php echo $current_page + 1; ?>" class="btn-pagination">Següent</a>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
