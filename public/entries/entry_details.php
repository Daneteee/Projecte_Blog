<?php include __DIR__ . '/../includes/header.php'; ?>

<?php 
if (!isset($_SESSION)) {
    session_start();
}

require __DIR__ . '/../config/db.php'; 

$entry_id = (int)$_GET['id'];

// Agafem els detalls de l'entrada
$query = "SELECT e.id, e.titol, e.descripcio, e.data, u.nom AS usuari_nom, c.nombre AS categoria_nombre
          FROM entrades e
          INNER JOIN usuaris u ON e.usuari_id = u.id
          INNER JOIN categories c ON e.categoria_id = c.id
          WHERE e.id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $entry_id);
$stmt->execute();
$result = $stmt->get_result();


// Comprovem que hi hagi una entrada
if ($result->num_rows === 1) {
    $entrada = $result->fetch_assoc();
    ?>

    <!-- Mostrem el contingut de l'entrada -->
    <div class="main-content container" style="max-width: 800px;">
        <h1 style="word-wrap: break-word;">
        <?php echo htmlspecialchars($entrada['titol']); ?></h1>
        <?php 

        $query_autor = "SELECT usuari_id FROM entrades WHERE id = " . $entrada['id'];
        $result_autor = $db->query($query_autor);
        $autor = $result_autor->fetch_assoc();

        // Comprovem que l'usuari sigui l'autor de l'entrada
        if ($autor['usuari_id'] == $_SESSION['user_id']) {
            ?>
            <div style="display: flex; gap: 10px; margin: 10px;">
                <a href="edit_entry.php?id=<?php echo $entrada['id']; ?>" class="btn btn-edit" >
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
        ?>
        
        <!-- Mostrem les dades -->
        <div class="entry-details">
            <div class="entry-meta">
                <span class="meta-info">
                    <i class="icon-category"></i> Categoria: <?php echo htmlspecialchars($entrada['categoria_nombre']); ?> |
                    <i class="icon-user"></i> Autor: <?php echo htmlspecialchars($entrada['usuari_nom']); ?> |
                    <i class="icon-calendar"></i> Publicat el: <?php echo date('d/m/Y', strtotime($entrada['data'])); ?>
                </span>
            </div>

            <div class="entry-content">
                <p style="word-wrap: break-word;">
                    <?php echo nl2br(htmlspecialchars($entrada['descripcio'])); ?>
                </p>
            </div>
        </div>
    </div>
    <?php
} else {
    echo "<div class='error-message'>No s'ha trobat l'entrada.</div>";
}

$stmt->close();
$db->close();
?>

<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
