<?php
if (!isset($_SESSION)) {
    session_start();
}
include __DIR__ . '/../config/db.php';

// Funció per validar una categoría
function validateCategory($nom_categoria, $db) {
    if (empty($nom_categoria)) {
        return "El nom de la categoria no pot estar buit.";
    }

    if (!preg_match('/^[a-zA-ZàèìòùáéíóúñçÀÈÌÒÙÁÉÍÓÚÑÇ\s]+$/', $nom_categoria)) {
        return "El nom de la categoria només pot contenir lletres.";
    }

    $stmt = $db->prepare("SELECT COUNT(*) as count FROM categories WHERE LOWER(nombre) = LOWER(?)");
    $stmt->bind_param("s", $nom_categoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['count'] > 0) {
        return "La categoria ja existeix.";
    }

    return null;
}

// Processem la categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_categoria = trim($_POST['nom_categoria']);

    $error = validateCategory($nom_categoria, $db);

    if ($error === null) {
        $stmt = $db->prepare("INSERT INTO categories (nombre) VALUES (?)");
        $stmt->bind_param("s", $nom_categoria);

        try {
            if ($stmt->execute()) {
                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => "La categoria '{$nom_categoria}' s'ha creat amb èxit!"
                ];
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => "Error en crear la categoria: " . $db->error
                ];
            }
            $stmt->close();
        } catch (Exception $e) {
            $_SESSION['message'] = [
                'type' => 'error', 
                'text' => "S'ha produït un error inesperat: " . $e->getMessage()
            ];
        }
    } else {
        $_SESSION['message'] = [
            'type' => 'warning',
            'text' => $error
        ];
    }

} 

header("Location: create_category.php");
exit;