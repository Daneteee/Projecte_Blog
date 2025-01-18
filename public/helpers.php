<?php

// Funció per llistar les categories
function llistarCategories($db) {
    $query = "SELECT id, nombre FROM categories ORDER BY id ASC";

    $result = $db->query($query);

    // Comprovem si hi han resultats
    if ($result && $result->num_rows > 0) {
        $categories = [];
        
        while ($category = $result->fetch_assoc()) {
            $categories[] = $category;
        }

        return $categories;
    } else {
        return [];
    }
}

// Funció per mostrar les categories
function mostraCategories($arrayCategories) {

    // Comprovem si hi han categories
    if (!empty($arrayCategories)) {
        echo '<ul>';
        // Mostrem les categories en una llista desordenada
        foreach ($arrayCategories as $category) {

            // Fiquem un link amb la ID de la categoría per trobarla més endavant
            echo '<li><a href="/categories/category.php?id=' . $category['id'] . '">' . htmlspecialchars($category['nombre']) . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No hi han categories disponibles.</p>';
    }
}

function cleanData($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Funció per validar la contrasenya
function validar_contrasenya($password) {
    return !empty($password) &&
        preg_match('/[A-Z]/', $password) &&  
        preg_match('/[a-z]/', $password) &&  
        preg_match('/[0-9]/', $password) &&  
        preg_match('/[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]+/', $password) && 
        strlen($password) >= 8;
}

// Funció per validar l'email
function validar_email($email) {
    return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>
