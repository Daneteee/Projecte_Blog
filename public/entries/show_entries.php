<?php
if (!isset($db)) {
    include 'config/db.php'; 
}

// Obtenim les 5 primeres entrades
$query = "SELECT e.id, e.titol, e.descripcio, e.data, u.nom AS usuari_nom, c.nombre AS categoria_nombre
          FROM entrades e
          INNER JOIN usuaris u ON e.usuari_id = u.id
          INNER JOIN categories c ON e.categoria_id = c.id
          ORDER BY e.data DESC
          LIMIT 5";

$result = $db->query($query);

// Comprovem si hem obtingut resultats de la consulta
if ($result->num_rows > 0) {
    while ($entrada = $result->fetch_assoc()) {
        ?>

        <!-- Mostrem les entrades -->
        <div class="blog-entry">
            <div class="blog-entry-header">
                <h2 class="blog-entry-title"><a href="entries/entry_details.php?id=<?php echo $entrada['id']; ?>" style="text-decoration: none; color: inherit;">
                        <?php echo htmlspecialchars($entrada['titol']); ?>
                    </a></h2>
                <div class="blog-entry-meta">
                    <span class="meta-info">
                        <i class="icon-category"></i> <?php echo htmlspecialchars($entrada['categoria_nombre']); ?> | 
                        <i class="icon-user"></i> <?php echo htmlspecialchars($entrada['usuari_nom']); ?> | 
                        <i class="icon-calendar"></i> <?php echo date('d/m/Y', strtotime($entrada['data'])); ?>
                    </span>
                </div>
            </div>

            <div class="blog-entry-body">
                <p class="card-text" style="overflow: hidden; text-overflow: ellipsis;">
                    <?php echo nl2br(htmlspecialchars($entrada['descripcio'])); ?>
                </p>
            </div>
            <?php 

            // En cas que l'usuari sigui l'autor donem l'opció d'editar i eliminar entrada
            if (isset($_SESSION['user_id'])) {
                $query_autor = "SELECT usuari_id FROM entrades WHERE id = " . $entrada['id'];
                $result_autor = $db->query($query_autor);
                $autor = $result_autor->fetch_assoc();

                if ($autor['usuari_id'] == $_SESSION['user_id']) {
                    ?>
                    <div class="entry-actions">
                        <a href="entries/edit_entry.php?id=<?php echo $entrada['id']; ?>" class="btn btn-edit">
                            <i class="icon-edit"></i> Editar
                        </a>
                        <a href="entries/delete_entry.php?id=<?php echo $entrada['id']; ?>" 
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

        <?php
    }
    ?>

    <!-- Botó per veure les entrades -->
    <div class="view-all">
        <a href="entries/all_entries.php" class="btn btn-view-all">Veure totes les entrades</a>
    </div>
    <?php
} else {
    echo "<div class='no-entries'>Encara no hi han entrades.</div>"; 
}
?>
