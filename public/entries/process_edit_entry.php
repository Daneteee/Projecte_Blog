<?php
session_start();
include __DIR__ . '/../config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtenim les dades del formulari
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrada_id = $_POST['id'];
    $titol = trim($_POST['titol']);
    $descripcio = trim($_POST['descripcio']);
    $categoria_id = $_POST['categoria_id'];

    // Verifiquem que l'usuari sigui l¡autor de l'entrada
    $query_verificar = "SELECT usuari_id FROM entrades WHERE id = ?";
    $stmt = $db->prepare($query_verificar);
    $stmt->bind_param("i", $entrada_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $entrada = $result->fetch_assoc();

    // Actualitzem l'entrada
    $stmt = $db->prepare("UPDATE entrades SET titol = ?, descripcio = ?, categoria_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $titol, $descripcio, $categoria_id, $entrada_id);

    // Missatge informatiu
    if ($stmt->execute()) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Entrada actualitzada correctament.'
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error en actualitzar l\'entrada.'
        ];
    }

    header("Location: edit_entry.php?id=" . $entrada_id);
    exit();
}
?>
