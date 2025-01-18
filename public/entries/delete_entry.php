<?php
session_start();
include __DIR__ . '/../config/db.php'; 

// Verifiquem si l'usuari está autenticat
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Verifiquem si s'ha enviat l'ID de l'entrada
if (isset($_GET['id'])) {
    $entrada_id = $_GET['id'];

    // Verifiquem que l'usuari és l'autor de l'entrada
    $query_verificar = "SELECT usuari_id FROM entrades WHERE id = ?";
    $stmt = $db->prepare($query_verificar);
    $stmt->bind_param("i", $entrada_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $entrada = $result->fetch_assoc();

    // Eliminem l'entrada
    $stmt = $db->prepare("DELETE FROM entrades WHERE id = ?");
    $stmt->bind_param("i", $entrada_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Entrada eliminada correctament.'
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error en eliminar l\'entrada.'
        ];
    }
} 

header("Location: /index.php");
exit();
?>
