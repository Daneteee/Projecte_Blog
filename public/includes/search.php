<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
if (!isset($_SESSION)) {
    session_start();
}

require __DIR__ . '/../config/db.php';

if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
    echo "<div class='error-message'>Si us plau, introdueix un títol per cercar.</div>";
    exit;
}

$query = trim($_GET['query']);
$search_query = "SELECT e.id, e.titol, e.descripcio, e.data, u.nom AS usuari_nom, c.nombre AS categoria_nombre
                 FROM entrades e
                 INNER JOIN usuaris u ON e.usuari_id = u.id
                 INNER JOIN categories c ON e.categoria_id = c.id
                 WHERE e.titol LIKE ?
                 ORDER BY e.data DESC";
$stmt = $db->prepare($search_query);
$search_term = "%" . $query . "%";
$stmt->bind_param('s', $search_term);
$stmt->execute();
$result = $stmt->get_result();
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
</div>

<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
